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
}