<?php

class AdminMiddleware {
    public function handle(Request $request) {
        if (!Session::has('user')) {
            if ($request->isAjax()) {
                return new Response(json_encode([
                    'success' => false,
                    'message' => 'Authentication required',
                    'redirect' => '/login'
                ]), 401);
            }
            
            Session::flash('error', 'Please login to continue');
            return new Response('', 302, ['Location' => '/login']);
        }
        
        $user = Session::get('user');
        
        if ($user['role'] !== ROLE_ADMIN) {
            if ($request->isAjax()) {
                return new Response(json_encode([
                    'success' => false,
                    'message' => 'Admin access required'
                ]), 403);
            }
            
            Session::flash('error', 'Admin access required');
            return new Response('', 302, ['Location' => '/dashboard']);
        }
        
        return true;
    }
}
