<?php

class GuestMiddleware {
    public function handle(Request $request) {
        if (Session::has('user')) {
            $user = Session::get('user');
            
            // Redirect based on user role
            $redirectUrl = '/dashboard';
            if ($user['role'] === ROLE_ADMIN) {
                $redirectUrl = '/admin';
            } elseif ($user['role'] === ROLE_STAFF) {
                $redirectUrl = '/staff';
            }
            
            if ($request->isAjax()) {
                return new Response(json_encode([
                    'success' => false,
                    'message' => 'Already authenticated',
                    'redirect' => $redirectUrl
                ]), 403);
            }
            
            $response = new Response('', 302);
            $response->setHeader('Location', $redirectUrl);
            return $response;
        }
        
        return true;
    }
}
