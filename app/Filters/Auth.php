<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class Auth implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();
        
        // Check if user is logged in
        if (!$session->get('isLoggedIn')) {
            return redirect()->to('/login');
        }
        
        // Ensure user_id is set in session
        if (!$session->get('user_id')) {
            // Try to get user_id from database using email or other identifier
            $userEmail = $session->get('email');
            if ($userEmail) {
                $db = \Config\Database::connect();
                $user = $db->table('users')
                    ->where('email', $userEmail)
                    ->where('status', 'active')
                    ->get()
                    ->getRowArray();
                    
                if ($user) {
                    $session->set('user_id', $user['id']);
                } else {
                    // Invalid session, force logout
                    $session->destroy();
                    return redirect()->to('/login')->with('error', 'Invalid session. Please login again.');
                }
            } else {
                // No email in session either, force logout
                $session->destroy();
                return redirect()->to('/login')->with('error', 'Session expired. Please login again.');
            }
        }
        
        // Optional: Verify user still exists and is active
        $db = \Config\Database::connect();
        $userExists = $db->table('users')
            ->where('id', $session->get('user_id'))
            ->where('status', 'active')
            ->countAllResults();
            
        if (!$userExists) {
            $session->destroy();
            return redirect()->to('/login')->with('error', 'User account no longer exists or is inactive.');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Nothing to do after
    }
}