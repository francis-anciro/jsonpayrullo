<?php
class Attendance {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function tapIn($employeeID) {
        $this->db->query("CALL employeeTapIn(:id)");
        $this->db->bind(':id', $employeeID);
        return $this->db->execute();
    }
    public function tapOut($employeeID) {
        $this->db->query("CALL employeeTapOut(:id)");
        $this->db->bind(':id', $employeeID);

        if (!$this->db->execute()) {
            throw new Exception("Database failed to execute stored procedure.");
        }

        return true;
    }

    public function getAttendanceHistory($employeeID) {
        $this->db->query("SELECT * FROM attendance WHERE Employee_ID = :id ORDER BY attendance_date DESC");
        $this->db->bind(':id', $employeeID);
        return $this->db->resultSet();
    }
}