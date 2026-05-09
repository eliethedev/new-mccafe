<?php

class Controller {
    protected $request;
    protected $response;
    
    public function __construct() {
        $this->response = new Response();
    }
    
    protected function view($template, $data = []) {
        extract($data);
        
        $templatePath = __DIR__ . '/../views/' . $template . '.php';
        
        if (!file_exists($templatePath)) {
            throw new Exception("View template not found: $template");
        }
        
        // Store view data for debugging
        $GLOBALS['__view_data'] = $data;
        
        ob_start();
        include $templatePath;
        $content = ob_get_clean();
        
        $this->response->setContent($content);
        return $this->response;
    }
    
    protected function json($data, $statusCode = 200) {
        $this->response->setStatusCode($statusCode);
        $this->response->setHeader('Content-Type', 'application/json');
        $this->response->setContent(json_encode($data));
        return $this->response;
    }
    
    protected function redirect($url, $statusCode = 302) {
        $this->response->setStatusCode($statusCode);
        $this->response->setHeader('Location', $url);
        return $this->response;
    }
    
    protected function validate($data, $rules) {
        $errors = [];
        
        foreach ($rules as $field => $fieldRules) {
            $rulesList = explode('|', $fieldRules);
            
            foreach ($rulesList as $rule) {
                if ($rule === 'required' && empty($data[$field])) {
                    $errors[$field][] = "The $field field is required.";
                }
                
                if (strpos($rule, 'min:') === 0) {
                    $minLength = (int)substr($rule, 4);
                    if (strlen($data[$field]) < $minLength) {
                        $errors[$field][] = "The $field must be at least $minLength characters.";
                    }
                }
                
                if ($rule === 'email' && !filter_var($data[$field], FILTER_VALIDATE_EMAIL)) {
                    $errors[$field][] = "The $field must be a valid email address.";
                }
            }
        }
        
        return $errors;
    }
    
    protected function getAuthenticatedUser() {
        return Session::get('user');
    }
    
    protected function isAuthenticated() {
        return !empty(Session::get('user'));
    }
}
