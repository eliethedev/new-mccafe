<?php

class AdminController extends Controller {
    public function index(Request $request) {
        return $this->view('admin/dashboard', [
            'title' => 'Admin Dashboard'
        ]);
    }
}
