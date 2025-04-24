<?php

namespace Model;

use Core\ORM;

class UserModel
{
    private $id;
    private $email;
    private $password;
    private $orm;
    private $table = 'users';

    public function __construct($email = null, $password = null)
    {
        $this->email = $email;
        $this->password = $password;
        $this->orm = new ORM();
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function create($data = [])
    {
        try {
            $fields = [];
            
            if (!empty($data)) {
                $fields = $data;
            } else {
                if ($this->email) $fields['email'] = $this->email;
                if ($this->password) $fields['password'] = $this->password;
            }
            
            if (empty($fields)) {
                return false;
            }
            
            $id = $this->orm->create($this->table, $fields);
            if ($id) {
                $this->id = $id;
                return $id;
            }
            return false;
        } catch (\Exception $e) {
            echo "Create error: " . $e->getMessage();
            return false;
        }
    }

    public function read($id = null)
    {
        try {
            $userId = $id ?? $this->id;
            
            if (!$userId) {
                return false;
            }
            
            return $this->orm->read($this->table, $userId);
        } catch (\Exception $e) {
            echo "Read error: " . $e->getMessage();
            return false;
        }
    }

    public function update($data = [])
    {
        try {
            $userId = isset($data['id']) ? $data['id'] : $this->id;
            
            if (!$userId) {
                return false;
            }
            
            $fields = [];
            
            if (!empty($data)) {
                // Ne pas inclure l'ID dans les champs à mettre à jour
                $fields = array_diff_key($data, ['id' => '']);
            } else {
                if ($this->email) $fields['email'] = $this->email;
                if ($this->password) $fields['password'] = $this->password;
            }
            
            if (empty($fields)) {
                return false;
            }
            
            return $this->orm->update($this->table, $userId, $fields);
        } catch (\Exception $e) {
            echo "Update error: " . $e->getMessage();
            return false;
        }
    }

    public function delete($id = null)
    {
        try {
            $userId = $id ?? $this->id;
            
            if (!$userId) {
                return false;
            }
            
            return $this->orm->delete($this->table, $userId);
        } catch (\Exception $e) {
            echo "Delete error: " . $e->getMessage();
            return false;
        }
    }

    public function read_all()
    {
        try {
            return $this->orm->find($this->table);
        } catch (\Exception $e) {
            echo "Read all error: " . $e->getMessage();
            return [];
        }
    }

    public function save()
    {
        return $this->create();
    }

    public function login()
    {
        try {
            if (!$this->email || !$this->password) {
                return false;
            }
            
            $params = [
                'WHERE' => "email = '{$this->email}' AND password = '{$this->password}'"
            ];
            
            $result = $this->orm->find($this->table, $params);
            
            return !empty($result) ? $result[0] : false;
        } catch (\Exception $e) {
            echo "Login error: " . $e->getMessage();
            return false;
        }
    }
}

$test = new UserModel();
$test->read_all();

// namespace Model;

// class UserModel
// {
//     private $id;
//     private $email;
//     private $password;
//     private $db;

//     public function __construct($email = null, $password = null)
//     {
//         $this->email = $email;
//         $this->password = $password;
//         $this->initDb();
//     }

//     private function initDb()
//     {
//         try {
//             $host = 'localhost';
//             $dbname = 'PiePHP';
//             $username = 'root';
//             $password = 'root';
            
//             $this->db = new \PDO("mysql:host=$host;port=8889;dbname=$dbname", $username, $password);
//             $this->db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
//         } catch (\PDOException $e) {
//             echo "Connection error: " . $e->getMessage();
//             exit;
//         }
//     }

//     public function setId($id)
//     {
//         $this->id = $id;
//     }

//     public function getId()
//     {
//         return $this->id;
//     }

//     public function setEmail($email)
//     {
//         $this->email = $email;
//     }

//     public function getEmail()
//     {
//         return $this->email;
//     }

//     public function setPassword($password)
//     {
//         $this->password = $password;
//     }

//     public function getPassword()
//     {
//         return $this->password;
//     }
//     public function create($data = [])
//     {
//         try {
//             $email = isset($data['email']) ? $data['email'] : $this->email;
//             $password = isset($data['password']) ? $data['password'] : $this->password;
            
//             $stmt = $this->db->prepare("INSERT INTO users (email, password) VALUES (:email, :password)");
//             $stmt->bindParam(':email', $email);
//             $stmt->bindParam(':password', $password);
            
//             if ($stmt->execute()) {
//                 return $this->db->lastInsertId();
//             }
//             return false;
//         } catch (\PDOException $e) {
//             echo "Create error: " . $e->getMessage();
//             return false;
//         }
//     }
//     public function read($id = null)
//     {
//         try {
//             $userId = $id ?? $this->id;
            
//             if (!$userId) {
//                 return false;
//             }
            
//             $stmt = $this->db->prepare("SELECT * FROM users WHERE id = :id");
//             $stmt->bindParam(':id', $userId);
//             $stmt->execute();
            
//             return $stmt->fetch(\PDO::FETCH_ASSOC);
//         } catch (\PDOException $e) {
//             echo "Read error: " . $e->getMessage();
//             return false;
//         }
//     }
//     public function update($data = [])
//     {
//         try {
//             $userId = isset($data['id']) ? $data['id'] : $this->id;
            
//             if (!$userId) {
//                 return false;
//             }
            
//             $updateFields = [];
//             $params = [];
            
//             if (isset($data['email']) || $this->email) {
//                 $updateFields[] = "email = :email";
//                 $params[':email'] = isset($data['email']) ? $data['email'] : $this->email;
//             }
            
//             if (isset($data['password']) || $this->password) {
//                 $updateFields[] = "password = :password";
//                 $params[':password'] = isset($data['password']) ? $data['password'] : $this->password;
//             }
            
//             if (empty($updateFields)) {
//                 return false;
//             }
            
//             $sql = "UPDATE users SET " . implode(", ", $updateFields) . " WHERE id = :id";
//             $params[':id'] = $userId;
            
//             $stmt = $this->db->prepare($sql);
            
//             return $stmt->execute($params);
//         } catch (\PDOException $e) {
//             echo "Update error: " . $e->getMessage();
//             return false;
//         }
//     }
//     public function delete($id = null)
//     {
//         try {
//             $userId = $id ?? $this->id;
            
//             if (!$userId) {
//                 return false;
//             }
            
//             $stmt = $this->db->prepare("DELETE FROM users WHERE id = :id");
//             $stmt->bindParam(':id', $userId);
            
//             return $stmt->execute();
//         } catch (\PDOException $e) {
//             echo "Delete error: " . $e->getMessage();
//             return false;
//         }
//     }
//     public function read_all()
//     {
//         try {
//             $stmt = $this->db->query("SELECT * FROM users");
//             return $stmt->fetchAll(\PDO::FETCH_ASSOC);
//         } catch (\PDOException $e) {
//             echo "Read all error: " . $e->getMessage();
//             return [];
//         }
//     }
//     public function save()
//     {
//         return $this->create();
//     }
//     public function login()
//     {
//         try {
//             $stmt = $this->db->prepare("SELECT * FROM users WHERE email = :email AND password = :password");
//             $stmt->bindParam(':email', $this->email);
//             $stmt->bindParam(':password', $this->password);
//             $stmt->execute();
//             return $stmt->fetch(\PDO::FETCH_ASSOC);
//         } catch (\PDOException $e) {
//             echo "Login error: " . $e->getMessage();
//             return false;
//         }
//     }
// }