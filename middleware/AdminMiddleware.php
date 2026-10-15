<?php

class AdminMiddleware {
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
        
        $user = Session::get('user');
        
        if ($user['role'] !== ROLE_ADMIN) {
            if ($request->isAjax()) {
                $r = new Response(json_encode([
                    'success' => false,
                    'message' => 'Admin access required'
                ]), 403);
                $r->setHeader('Content-Type', 'application/json');
                return $r;
            }
            
            Session::flash('error', 'Admin access required');
            $r = new Response('', 302);
            $r->setHeader('Location', '/dashboard');
            return $r;
        }
        
        return true;
    }
}
