<?php
// MODEL FOR ANALYTICS TO RETRIEVE DATA FOR ANALYTICS TAB
class Analytic {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function getEmployeeCountByStatus($status) {
        $this->db->query("SELECT COUNT(*) as count FROM employees WHERE employment_status = :status");
        $this->db->bind(':status', $status);
        $result = $this->db->single();
        return $result->count ?? $result['count'] ?? 0;
    }

    public function getDepartmentNames(){
        $this->db->query("SELECT name, department_id FROM departments");
        return $this->db->resultSet();
    }

    public function getEmployeeCountByDepartment($deptId) {
        $this->db->query("SELECT COUNT(*) as count FROM employees WHERE Department_ID = :deptId");
        $this->db->bind(':deptId', $deptId);
        $result = $this->db->single();
        return $result->count ?? $result['count'] ?? 0;
    }

    public function getPositionCount() {
        $this->db->query("SELECT COUNT(*) as count FROM positions");
        $result = $this->db->single();
        return $result->count ?? $result['count'] ?? 0;
    }

    public function getDepartmentCount() {
        $this->db->query("SELECT COUNT(*) as count FROM departments");
        $result = $this->db->single();
        return $result->count ?? $result['count'] ?? 0;
    }
}