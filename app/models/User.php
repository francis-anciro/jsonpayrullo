<?php

class User{
    private $db;

    public function __construct(){
        $this->db = new Database();
    }
    public function getUsers() {
        $this->db->query("
        SELECT 
            u.*, 
            e.Department_ID,
            e.employee_code
        FROM users u
        INNER JOIN employees e ON u.User_ID = e.User_ID
    ");
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
    public function insertFullEmployee($data) {
        $password_hash = password_hash($data['password'], PASSWORD_DEFAULT);

        $this->db->query("CALL sp_InsertNewEmployee(
        :username, :email, :password_hash, :role,
        :employee_code, :first_name, :middle_name, :last_name,
        :phone, :address, :birthdate, :hire_date,
        :employment_type_id, :department_id, :position_id, :basic_salary,
        :shift_id, :leave_type_id, :allocated_days
    )");

        // Bind all 19 parameters in order
        $this->db->bind(':username', $data['username']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':password_hash', $password_hash);
        $this->db->bind(':role', $data['role']);
        $this->db->bind(':employee_code', $data['employee_code']);
        $this->db->bind(':first_name', $data['first_name']);
        $this->db->bind(':middle_name', $data['middle_name']);
        $this->db->bind(':last_name', $data['last_name']);
        $this->db->bind(':phone', $data['phone']);
        $this->db->bind(':address', $data['address']);
        $this->db->bind(':birthdate', $data['birthdate']);
        $this->db->bind(':hire_date', $data['hire_date']);
        $this->db->bind(':employment_type_id', $data['employment_type_id']);
        $this->db->bind(':department_id', $data['department_id']);
        $this->db->bind(':position_id', $data['position_id']);
        $this->db->bind(':basic_salary', $data['basic_salary']);
        $this->db->bind(':shift_id', $data['shift_id']);
        $this->db->bind(':leave_type_id', $data['leave_type_id']);
        $this->db->bind(':allocated_days', $data['allocated_days']);

        return $this->db->execute();
    }
    public function deleteUserByCode($code) {
        $this->db->query("CALL sp_DeleteUserByCode(:code)");
        $this->db->bind(':code', $code);

        return $this->db->execute();
    }

//    FOR EdIT USER

    public function getUserByCode($code) {
        $this->db->query("
        SELECT u.*, e.*, es.Shift_ID 
        FROM users u
        INNER JOIN employees e ON u.User_ID = e.User_ID
        INNER JOIN employee_shifts es ON e.Employee_ID = es.Employee_ID
        WHERE e.employee_code = :code
    ");
        $this->db->bind(':code', $code);
        return $this->db->single();
    }

    public function updateFullEmployee($data) {
        $this->db->query("CALL sp_UpdateEmployee(:code, :email, :role, :fname, :lname, :phone, :address, :dept, :pos, :salary, :shift)");

        $this->db->bind(':code', $data['employee_code']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':role', $data['role']);
        $this->db->bind(':fname', $data['first_name']);
        $this->db->bind(':lname', $data['last_name']);
        $this->db->bind(':phone', $data['phone']);
        $this->db->bind(':address', $data['address']);
        $this->db->bind(':dept', $data['department_id']);
        $this->db->bind(':pos', $data['position_id']);
        $this->db->bind(':salary', $data['basic_salary']);
        $this->db->bind(':shift', $data['shift_id']);

        return $this->db->execute();
    }
}