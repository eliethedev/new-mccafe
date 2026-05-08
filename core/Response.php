<?php

class Response {
    private $content;
    private $statusCode;
    private $headers;
    
    public function __construct($content = '', $statusCode = 200) {
        $this->content = $content;
        $this->statusCode = $statusCode;
        $this->headers = [];
    }
    
    public function setContent($content) {
        $this->content = $content;
        return $this;
    }
    
    public function getContent() {
        return $this->content;
    }
    
    public function setStatusCode($statusCode) {
        $this->statusCode = $statusCode;
        return $this;
    }
    
    public function getStatusCode() {
        return $this->statusCode;
    }
    
    public function setHeader($name, $value) {
        $this->headers[$name] = $value;
        return $this;
    }
    
    public function getHeaders() {
        return $this->headers;
    }
    
    public function send() {
        // Set status code
        http_response_code($this->statusCode);
        
        // Set headers
        foreach ($this->headers as $name => $value) {
            header("$name: $value");
        }
        
        // Send content
        echo $this->content;
    }
    
    public function json($data, $statusCode = 200) {
        $this->setStatusCode($statusCode);
        $this->setHeader('Content-Type', 'application/json');
        $this->setContent(json_encode($data));
        return $this;
    }
    
    public function redirect($url, $statusCode = 302) {
        $this->setStatusCode($statusCode);
        $this->setHeader('Location', $url);
        return $this;
    }
    
    public function withCookie($name, $value, $expires = 0, $path = '/', $domain = '', $secure = false, $httpOnly = true) {
        setcookie($name, $value, $expires, $path, $domain, $secure, $httpOnly);
        return $this;
    }
    
    public function withSession($key, $value) {
        Session::set($key, $value);
        return $this;
    }
    
    public function withFlash($key, $value) {
        Session::flash($key, $value);
        return $this;
    }
    
    public function withError($message) {
        Session::flash('error', $message);
        return $this;
    }
    
    public function withSuccess($message) {
        Session::flash('success', $message);
        return $this;
    }
}
