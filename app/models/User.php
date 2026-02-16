<?php

class User{
    private $db;

    public function __construct(){
        $this->db = new Database();
    }
    public function getUsers(){
        $this->db->query("CALL getAllUsers()");
        return $this->db->resultSet();
    }
    public function findUserByEmail($email) {
        $this->db->query("CALL getUserRowByUsernameOrEmail(:login)");
        $this->db->bind(':login', $email);
        return $this->db->single();
    }
    public function getUserByID($User_id){
        $this->db->query("CALL getUserById(:User_id)");
        $this->db->bind(':User_id', $User_id);
        return $this->db->single();
    }
    public function insertUser($data){
        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        $this->db->query("CALL insertUser(:username, :email,:password, :role )");
        $this->db->bind(':username', $data['username']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':password', $data['password']);
        $this->db->bind(':role', $data['role']);
        return $this->db->execute();
    }
    public function getUserCountByDept($deptId) {
        $this->db->query("SELECT COUNT(*) as total FROM employees WHERE Department_ID = :dept_id");
        $this->db->bind(':dept_id', $deptId);

        $row = $this->db->single();
        return $row->total;
    }
//    public function deleteUser($User_id){
//        $this->db->query("CALL deleteUser(:User_id)");
//        $this->db->bind(':User_id', $User_id);
//        return $this->db->execute();
//    }
//    public function updateUser($data){
//        $this->db->query("CALL updateUser(:id, :username, :email, :role, :active)");
//
//        $this->db->bind(':id', $data['User_id']);
//        $this->db->bind(':username', $data['username']);
//        $this->db->bind(':email', $data['email']);
//        $this->db->bind(':role', $data['role']);
//        $this->db->bind(':active', $data['is_active']);
//
//        return $this->db->execute();
//    }

}