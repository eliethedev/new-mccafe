<?php

class AuthMiddleware {
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
        
        return true;
    }
}
