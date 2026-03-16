<?php

namespace App\Controllers;

use CodeIgniter\Controller;

/**
 * RagController — RAG-powered book suggestion using Groq (free)
 * Route: POST /rag/suggest
 * .env: GROQ_API_KEY = gsk_...
 */
class RagController extends Controller
{
    public function suggest()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(403)->setJSON(['error' => 'Forbidden']);
        }

        $body  = $this->request->getJSON(true);
        $query = trim($body['query'] ?? '');

        if (strlen($query) < 2) {
            return $this->response->setStatusCode(400)->setJSON(['error' => 'Query too short.']);
        }

        // ── RETRIEVAL ────────────────────────────────────────────
        $db    = \Config\Database::connect();
        $words = array_filter(preg_split('/\s+/', strtolower($query)));
        $books = [];

        // Pass 1: search by each word across all fields
        if (!empty($words)) {
            $builder = $db->table('books');
            $builder->select('id, title, author, genre, preface, published_year, total_copies, available_copies');
            $builder->where('status', 'active');
            $builder->groupStart();
            foreach ($words as $word) {
                $builder->orLike('title',   $word)
                        ->orLike('author',  $word)
                        ->orLike('genre',   $word)
                        ->orLike('preface', $word);
            }
            $builder->groupEnd();
            $builder->limit(8);
            $books = $builder->get()->getResultArray();
        }

        // Pass 2: fallback — return all active books if no match found
        if (empty($books)) {
            $books = $db->table('books')
                ->select('id, title, author, genre, preface, published_year, total_copies, available_copies')
                ->where('status', 'active')
                ->orderBy('title', 'ASC')
                ->limit(8)
                ->get()->getResultArray();
        }

        if (empty($books)) {
            return $this->response->setJSON([
                'suggestion' => null,
                'books'      => [],
                'message'    => 'The library catalog is currently empty.',
            ]);
        }

        // ── AUGMENTATION ─────────────────────────────────────────
        $context = '';
        foreach ($books as $i => $book) {
            $avail   = (int)$book['available_copies'] > 0 ? 'Available' : 'Currently checked out';
            $preface = !empty($book['preface']) ? trim($book['preface']) : '(No description provided)';

            $context .= sprintf(
                "[%d] \"%s\" by %s | Genre: %s | Year: %s | %s\nDescription: %s\n\n",
                $i + 1,
                $book['title'],
                $book['author']         ?? 'Unknown',
                $book['genre']          ?? 'General',
                $book['published_year'] ?? 'N/A',
                $avail,
                $preface
            );
        }

        // ── GENERATION ───────────────────────────────────────────
        $apiKey = env('GROQ_API_KEY', '');

        if (empty($apiKey)) {
            return $this->response->setJSON([
                'suggestion' => 'Here are the closest matching books from our catalog.',
                'books'      => $books,
            ]);
        }

        $systemPrompt =
            'You are a warm, friendly community librarian for a barangay library in the Philippines. ' .
            'A resident described what they want to read. Using ONLY the book details provided, ' .
            'write a helpful recommendation in 2–3 sentences. ' .
            'Mention at least one specific book by title. ' .
            'If a book is checked out, still suggest it if it matches well. ' .
            'Never invent information not in the descriptions. ' .
            'Write in simple, natural Filipino-friendly English. No bullet points, just flowing text.';

        $userPrompt =
            "Resident is looking for: \"{$query}\"\n\n" .
            "Available books:\n\n" . $context .
            "Give a short, friendly, personalized reading suggestion.";

        $payload = json_encode([
            'model'       => 'llama-3.3-70b-versatile',
            'max_tokens'  => 250,
            'temperature' => 0.75,
            'messages'    => [
                ['role' => 'system', 'content' => $systemPrompt],
                ['role' => 'user',   'content' => $userPrompt],
            ],
        ]);

        $ch = curl_init('https://api.groq.com/openai/v1/chat/completions');
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST           => true,
            CURLOPT_TIMEOUT        => 25,
            CURLOPT_HTTPHEADER     => [
                'Authorization: Bearer ' . $apiKey,
                'Content-Type: application/json',
            ],
            CURLOPT_POSTFIELDS     => $payload,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
        ]);

        $raw      = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlErr  = curl_error($ch);
        curl_close($ch);

        // cURL failed
        if ($curlErr) {
            return $this->response->setJSON([
                'suggestion' => 'Could not reach the AI service right now. Here are the closest matching books.',
                'books'      => $books,
            ]);
        }

        // API error — still return books with graceful message
        if ($httpCode !== 200) {
            $decoded = json_decode($raw, true);
            $errMsg  = $decoded['error']['message'] ?? 'AI service unavailable.';
            return $this->response->setJSON([
                'suggestion' => 'The AI librarian is unavailable right now. Here are the closest matching books.',
                'books'      => $books,
                'ai_error'   => $errMsg,
            ]);
        }

        $ai         = json_decode($raw, true);
        $suggestion = trim($ai['choices'][0]['message']['content'] ?? '');

        if (empty($suggestion)) {
            $suggestion = 'Here are the closest matching books from our catalog.';
        }

        return $this->response->setJSON([
            'suggestion' => $suggestion,
            'books'      => $books,
        ]);
    }
}