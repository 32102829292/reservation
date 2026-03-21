<?php

namespace App\Controllers;

/**
 * Trait PdfAiExtractor
 *
 * Drop-in for AdminController and SkController.
 * The browser now extracts text with pdf.js and POSTs { pdf_text: "..." }.
 * No base64 decoding, no temp files, no server-side PDF parsing needed.
 *
 * Usage in controller:
 *   use PdfAiExtractor;
 *   public function extractPdfWithAI() { return $this->_doExtractPdfWithAI(); }
 */
trait PdfAiExtractor
{
    public function extractPdfWithAI()
    {
        try {
            return $this->_doExtractPdfWithAI();
        } catch (\Throwable $e) {
            log_message('error', '[PdfAiExtractor] crashed: ' . $e->getMessage()
                . ' in ' . $e->getFile() . ':' . $e->getLine());
            return $this->response->setJSON([
                'ok'    => false,
                'error' => 'PHP error: ' . $e->getMessage()
                         . ' (line ' . $e->getLine()
                         . ' in ' . basename($e->getFile()) . ')',
            ]);
        }
    }

    private function _doExtractPdfWithAI(): \CodeIgniter\HTTP\ResponseInterface
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(400)
                ->setJSON(['ok' => false, 'error' => 'Invalid request.']);
        }

        if (!session()->has('user_id')) {
            return $this->response->setStatusCode(401)
                ->setJSON(['ok' => false, 'error' => 'Unauthorized. Please log in again.']);
        }

        $json    = $this->request->getJSON(true) ?? [];

        // ★ Browser now sends extracted plain text — no base64 / no temp files
        $pdfText = trim($json['pdf_text'] ?? '');

        if (empty($pdfText) || strlen($pdfText) < 20) {
            return $this->response->setJSON([
                'ok'    => false,
                'error' => 'No readable text received. Make sure the PDF has selectable text (not a scanned image).',
            ]);
        }

        if (!function_exists('curl_init')) {
            return $this->response->setJSON(['ok' => false, 'error' => 'cURL is not enabled on this server.']);
        }

        $apiKey = env('GROQ_API_KEY')
               ?: getenv('GROQ_API_KEY')
               ?: ($_ENV['GROQ_API_KEY']    ?? null)
               ?: ($_SERVER['GROQ_API_KEY'] ?? null);

        if (!$apiKey) {
            return $this->response->setJSON(['ok' => false, 'error' => 'GROQ_API_KEY is not set in your .env file.']);
        }

        // Trim to 8 000 chars so we stay well inside Groq's context window
        $pdfText = mb_substr($pdfText, 0, 8000);

        $prompt = <<<'PROMPT'
You are a book cataloging assistant. Your ONLY job is to return a single JSON object.

CRITICAL RULES — follow without exception:
1. Output ONLY the raw JSON object. No prose, no markdown, no backticks, no explanation.
2. Never use sentences as field values (except "preface"). Never say "unknown" or "not found".
3. For "title" and "author" you MUST provide your best guess — even if uncertain.
4. For "call_number" extract any Dewey Decimal (e.g. 823.914) or LC call number (e.g. PR6045.A97) visible in the text; leave empty string if none found.

Return exactly this structure:
{"title":"","author":"","genre":"","published_year":"","isbn":"","call_number":"","preface":""}

PDF TEXT:
PROMPT;

        $prompt .= $pdfText;

        $payload = json_encode([
            'model'       => 'llama-3.3-70b-versatile',
            'max_tokens'  => 800,
            'temperature' => 0,
            'messages'    => [['role' => 'user', 'content' => $prompt]],
        ]);

        $ch = curl_init('https://api.groq.com/openai/v1/chat/completions');
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => $payload,
            CURLOPT_TIMEOUT        => 30,
            CURLOPT_CONNECTTIMEOUT => 10,
            CURLOPT_HTTPHEADER     => [
                'Content-Type: application/json',
                'Authorization: Bearer ' . $apiKey,
            ],
        ]);

        $response   = curl_exec($ch);
        $httpStatus = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError  = curl_error($ch);
        curl_close($ch);

        if ($curlError) {
            return $this->response->setJSON(['ok' => false, 'error' => 'Network error reaching Groq: ' . $curlError]);
        }
        if (empty($response)) {
            return $this->response->setJSON(['ok' => false, 'error' => 'Groq returned an empty response.']);
        }

        $groqData = json_decode($response, true);

        if ($httpStatus !== 200) {
            $msg = $groqData['error']['message'] ?? ('Groq HTTP ' . $httpStatus . ': ' . substr($response, 0, 200));
            return $this->response->setJSON(['ok' => false, 'error' => 'Groq error: ' . $msg]);
        }

        $rawText = $groqData['choices'][0]['message']['content'] ?? '';

        if (empty(trim($rawText))) {
            return $this->response->setJSON(['ok' => false, 'error' => 'Groq returned empty content.']);
        }

        // Strip markdown fences if present
        $clean = trim(preg_replace('/^```(?:json)?\s*/m', '', $rawText));
        $clean = trim(preg_replace('/```\s*$/m', '', $clean));
        if (preg_match('/\{[\s\S]*\}/m', $clean, $m)) {
            $clean = $m[0];
        }

        $extracted = json_decode($clean, true);

        if (!is_array($extracted)) {
            $lowerRaw = strtolower($rawText);
            if (str_contains($lowerRaw, 'collection of images')
                || str_contains($lowerRaw, 'does not contain')
                || str_contains($lowerRaw, 'no readable text')
                || str_contains($lowerRaw, 'impossible to')
                || str_contains($lowerRaw, 'cannot determine')
            ) {
                return $this->response->setJSON([
                    'ok'    => false,
                    'error' => 'This PDF does not contain enough readable text. Please fill in book details manually.',
                ]);
            }
            return $this->response->setJSON([
                'ok'    => false,
                'error' => 'AI response was not valid JSON. Got: ' . mb_substr($rawText, 0, 150),
            ]);
        }

        // Ensure all expected keys exist
        foreach (['title', 'author', 'genre', 'published_year', 'isbn', 'call_number', 'preface'] as $key) {
            if (!isset($extracted[$key])) $extracted[$key] = '';
        }

        $missingFields = [];
        if (empty(trim($extracted['title'])))  $missingFields[] = 'title';
        if (empty(trim($extracted['author']))) $missingFields[] = 'author';

        // Return CSRF token so the browser can refresh it after this AJAX call
        $this->response->setHeader('X-CSRF-TOKEN', csrf_hash());

        if (!empty($missingFields)) {
            $fieldList = implode(' and ', $missingFields);
            return $this->response->setJSON([
                'ok'      => true,
                'warning' => true,
                'message' => ucfirst($fieldList) . ' wasn\'t detected — please fill '
                           . (count($missingFields) === 1 ? 'that in' : 'those in') . ', then hit Save.',
                'data'    => $extracted,
            ]);
        }

        return $this->response->setJSON(['ok' => true, 'data' => $extracted]);
    }
}