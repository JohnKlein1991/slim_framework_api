<?php

/**
 * @property DBHelper $dbHelper
 */

class UserModel
{
    public $id;
    public $username;
    public $hash;
    public $name;
    public $email;
    public $createdAt;
    public $updatedAt;

    private $dbHelper;

    public function __construct(array $data, $dbHelper)
    {
        $this->dbHelper = $dbHelper;
        foreach ($data as $key => $value){
            if($key == 'password'){
                $this->hash = password_hash($value, PASSWORD_DEFAULT);
            } else {
                $this->$key = $value;
            }
        }
    }
    public function save($newUser = false)
    {
        if(!$this->username || !$this->hash || !$this->name){
            return [
                'status' => false,
                'msg' => 'Not enough parameters'
            ];
        }
        $isUserExists = $this->dbHelper->isNotUniqueUsername($this->username);
        if($isUserExists){
            if(
                $newUser ||
                (isset($isUserExists['id']) && $isUserExists['id'] != $this->id)
            ){
                return [
                    'status' => false,
                    'msg' => 'This username is duplicated'
                ];
            }
        }
        $username = $this->username;
        $hash = $this->hash;
        $name = $this->name;
        $email = isset($this->email) ? $this->email : null;

        if($this->id){
            $result = $this->dbHelper->updateUser(
                $this->id,
                $username,
                $hash,
                $name,
                $email,
                $this->createdAt
            );
        } else {
            $result = $this->dbHelper->addNewUser(
                $username,
                $hash,
                $name,
                $email
            );
        }
        $msg = $result ? 'success' : 'Failed to add/update user';
        return [
            'status' => $result,
            'msg' => $msg
        ];
    }
}