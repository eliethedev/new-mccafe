<?php

class AuthMiddleware {
    public function handle(Request $request) {
        if (!Session::has('user')) {
            if ($request->isAjax()) {
                $r = new Response(json_encode([
                    'success' => false,
                    'message' => 'Session expired. Please log in again.',
                    'redirect' => '/login'
                ]), 401);
                $r->setHeader('Content-Type', 'application/json');
                return $r;
            }
            
            Session::flash('error', 'Please login to continue');
            $r = new Response('', 302);
            $r->setHeader('Location', '/login');
            return $r;
        }
        
        return true;
    }
}
