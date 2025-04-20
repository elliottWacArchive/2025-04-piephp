<?php

namespace Model;

class UserModel
{
    private $email;
    private $password;

    public function __construct($email = null, $password = null)
    {
        $this->email = $email;
        $this->password = $password;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }

    public function save()
    {
        $db = new \PDO('mysql:host=localhost;dbname=PiePHP', 'root', 'root');
        $stmt = $db->prepare("INSERT INTO users (email, password) VALUES (?, ?)");
        $stmt->execute([$this->email, $this->password]);
        return $db->lastInsertId();
    }
}
?>