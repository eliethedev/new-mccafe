<?php

class RiderMiddleware {
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
        $role = $user['role'] ?? '';

        // Riders and admins may use the rider workspace.
        if ($role !== ROLE_RIDER && $role !== ROLE_ADMIN) {
            if ($request->isAjax()) {
                $r = new Response(json_encode([
                    'success' => false,
                    'message' => 'Rider access required'
                ]), 403);
                $r->setHeader('Content-Type', 'application/json');
                return $r;
            }

            Session::flash('error', 'Rider access required');
            $r = new Response('', 302);
            $r->setHeader('Location', '/dashboard');
            return $r;
        }

        return true;
    }
}
