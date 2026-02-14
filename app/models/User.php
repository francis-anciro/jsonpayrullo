<?php

class User{
    private $db;

    public function __construct(){
        $this->db = new Database();
    }
    public function getUsers(){
        $this->db->query("SELECT id, username, email, password_hash, role, is_active, created_at FROM users");
        return $this->db->resultSet();
    }
    public function findUserByEmail($email) {
        $this->db->query("SELECT * FROM users WHERE email = :email");
        $this->db->bind(':email', $email);
        return $this->db->single();
    }
}