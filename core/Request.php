<?php

class Request {
    private $method;
    private $path;
    private $query;
    private $body;
    private $headers;
    private $files;
    
    public function __construct() {
        $this->method = $_SERVER['REQUEST_METHOD'];
        
        // Handle method override for HTML forms
        if ($this->method === 'POST' && isset($_POST['_method'])) {
            $overrideMethod = strtoupper($_POST['_method']);
            if (in_array($overrideMethod, ['PUT', 'DELETE', 'PATCH'])) {
                $this->method = $overrideMethod;
            }
        }
        
        $this->path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        
        // Remove base path for different server setups
        $basePath = '';
        
        // Check if running through Apache with .htaccess redirection
        if (strpos($this->path, '/MacCafe-MOR-DEMO/') === 0) {
            $basePath = '/MacCafe-MOR-DEMO';
        }
        // Check if running through PHP server in public directory
        elseif (strpos($this->path, '/MacCafe-MOR-DEMO/public') === 0) {
            $basePath = '/MacCafe-MOR-DEMO/public';
        }
        
        if ($basePath && strpos($this->path, $basePath) === 0) {
            $this->path = substr($this->path, strlen($basePath));
        }
        
        // Ensure path starts with /
        if (empty($this->path)) {
            $this->path = '/';
        } elseif ($this->path[0] !== '/') {
            $this->path = '/' . $this->path;
        }
        
        $this->query = $_GET;
        $this->body = $this->parseBody();
        $this->headers = $this->parseHeaders();
        $this->files = $_FILES;
    }
    
    public function getMethod() {
        return $this->method;
    }
    
    public function getPath() {
        return $this->path;
    }
    
    public function getQuery($key = null) {
        if ($key === null) {
            return $this->query;
        }
        return $this->query[$key] ?? null;
    }
    
    public function getBody($key = null) {
        if ($key === null) {
            return $this->body;
        }
        return $this->body[$key] ?? null;
    }
    
    public function getHeader($key) {
        return $this->headers[$key] ?? null;
    }
    
    public function getHeaders() {
        return $this->headers;
    }
    
    public function getFile($key) {
        return $this->files[$key] ?? null;
    }
    
    public function getFiles() {
        return $this->files;
    }
    
    public function isGet() {
        return $this->method === 'GET';
    }
    
    public function isPost() {
        return $this->method === 'POST';
    }
    
    public function isAjax() {
        return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
               strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    }
    
    public function input($key, $default = null) {
        $value = $this->getBody($key);
        if ($value !== null) {
            return $value;
        }
        
        $value = $this->getQuery($key);
        if ($value !== null) {
            return $value;
        }
        
        return $default;
    }
    
    public function all() {
        return array_merge($this->query, $this->body);
    }
    
    public function only($keys) {
        $data = $this->all();
        $result = [];
        
        foreach ($keys as $key) {
            if (isset($data[$key])) {
                $result[$key] = $data[$key];
            }
        }
        
        return $result;
    }
    
    public function except($keys) {
        $data = $this->all();
        
        foreach ($keys as $key) {
            unset($data[$key]);
        }
        
        return $data;
    }
    
    private function parseBody() {
        if ($this->method === 'POST') {
            $contentType = $this->getHeader('Content-Type');
            
            if ($contentType && strpos($contentType, 'application/json') !== false) {
                $input = file_get_contents('php://input');
                return json_decode($input, true) ?? [];
            }
            
            return $_POST;
        }
        
        if (in_array($this->method, ['PUT', 'DELETE', 'PATCH'])) {
            // Check if this is a method override from POST with multipart form data
            if (isset($_POST['_method'])) {
                return $_POST;
            }
            
            $input = file_get_contents('php://input');
            $contentType = $this->getHeader('Content-Type');
            
            if ($contentType && strpos($contentType, 'application/json') !== false) {
                return json_decode($input, true) ?? [];
            }
            
            parse_str($input, $data);
            return $data;
        }
        
        return [];
    }
    
    private function parseHeaders() {
        $headers = [];
        
        foreach ($_SERVER as $key => $value) {
            if (strpos($key, 'HTTP_') === 0) {
                $headerKey = str_replace(' ', '-', ucwords(str_replace('_', ' ', strtolower(substr($key, 5)))));
                $headers[$headerKey] = $value;
            }
        }
        
        return $headers;
    }
}
