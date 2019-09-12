<?php

class DBHelper
{
    private $db;

    public function __construct(Array $config)
    {
        try {
            $this->db = new PDO(
                $config['dbSystem'].':dbname='.$config['dbName'].';host='.$config['dbHost'],
                $config['dbUser'],
                $config['dbPassword']
            );
        } catch (Exception $e) {
            throw new Exception('Error!The connection to db is failed: '.$e->getMessage());
        }
    }
    public function getUserList()
    {
        $result = $this->db->query(
            'SELECT id, username, name, email 
            FROM users
            ')->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
    public function getUserById($id, $forUpdate = false)
    {
        if($forUpdate){
            $sql = 'SELECT username, hash, name, email, created_at 
            FROM users
            where id=:id';
        } else {
            $sql = 'SELECT username, name, email 
            FROM users
            where id=:id';
        }

        $sth = $this->db->prepare($sql);
        $sth->execute([
            ':id' => $id
        ]);
        $result = $sth->fetch(PDO::FETCH_ASSOC);
        return $result;
    }

    public function addNewUser($username, $hash, $name, $email = null)
    {
        $createdAt = date('Y-m-d h:i:s');
        $updatedAt = date('Y-m-d h:i:s');

        $sql = 'INSERT INTO users 
            (username, hash, name, email, created_at, updated_at)
            VALUES 
            (:username, :hash, :name, :email, :created_at, :updated_at)';
        $sth = $this->db->prepare($sql);
        $result = $sth->execute([
            ':username' => $username,
            ':hash' => $hash,
            ':name' => $name,
            ':email' => $email,
            ':created_at' => $createdAt,
            ':updated_at' => $updatedAt,
        ]);
        return $result;
    }
    public function removeUser($id)
    {
        $sql = 'DELETE 
            FROM users
            where id=:id';
        $sth = $this->db->prepare($sql);
        $sth->execute([
            ':id' => $id
        ]);
        $result = $sth->rowCount();
        return $result;
    }
    public function updateUser($id, $username, $hash, $name, $email, $createdAt)
    {
        $updatedAt = date('Y-m-d h:i:s');

        $sql = 'UPDATE users 
            SET 
            username=:username, hash=:hash, name=:name, email=:email,
             created_at=:created_at, updated_at=:updated_at
            where id=:id';
        $sth = $this->db->prepare($sql);
        $result = $sth->execute([
            ':id' => $id,
            ':username' => $username,
            ':hash' => $hash,
            ':name' => $name,
            ':email' => $email,
            ':created_at' => $createdAt,
            ':updated_at' => $updatedAt,
        ]);
        return $result;
    }
    public function isNotUniqueUsername($username)
    {
        $sql = 'select id 
            FROM users
            where username=:username';
        $sth = $this->db->prepare($sql);
        $sth->execute([
            ':username' => $username
        ]);
        $result = $sth->fetch();
        return $result;
    }
}