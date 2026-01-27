<?php
namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class Role implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();

        if (!$session->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        if (!in_array($session->get('role'), $arguments)) {
            // Redirect to appropriate dashboard based on user role
            $role = $session->get('role');
            if ($role === 'chairman') {
                return redirect()->to('/admin/dashboard');
            } elseif ($role === 'sk') {
                return redirect()->to('/sk/dashboard');
            }
            // For any other role, don't redirect - let the route handle it
            return;
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null) {}
}
