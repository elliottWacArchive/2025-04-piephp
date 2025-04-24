<?php

namespace Core;

class Request
{
    private $get = [];
    private $post = [];

    public function __construct()
    {
        foreach ($_GET as $key => $value) {
            $this->get[$key] = $this->sanitize($value);
        }

        foreach ($_POST as $key => $value) {
            $this->post[$key] = $this->sanitize($value);
        }
    }

    private function sanitize($value)
    {
        if (is_array($value)) {
            return array_map([$this, 'sanitize'], $value);
        }
        
        $value = trim($value);
        
        $value = stripslashes($value);
        
        $value = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
        
        return $value;
    }

    public function get($key = null, $default = null)
    {
        if ($key === null) {
            return $this->get;
        }
        
        return isset($this->get[$key]) ? $this->get[$key] : $default;
    }

    public function post($key = null, $default = null)
    {
        if ($key === null) {
            return $this->post;
        }
        
        return isset($this->post[$key]) ? $this->post[$key] : $default;
    }

    public function hasPost($key)
    {
        return isset($this->post[$key]);
    }

    public function hasGet($key)
    {
        return isset($this->get[$key]);
    }

    public function isPost()
    {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }

    public function isGet()
    {
        return $_SERVER['REQUEST_METHOD'] === 'GET';
    }
}