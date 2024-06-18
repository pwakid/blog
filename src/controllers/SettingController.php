<?php
class SettingController {
    private $db;

    // Constructor to initialize the database connection
    public function __construct($dbConnection) {
        $this->db = $dbConnection;
    }

    // Function to update a specific setting, including tenant isolation
    public function updateSetting($tenantID, $key, $value) {
        $sql = "UPDATE Settings SET Value = ? WHERE TenantID = ? AND Key = ?";
        $stmt = mysqli_prepare($this->db, $sql);
        mysqli_stmt_bind_param($stmt, 'sis', $value, $tenantID, $key);
        mysqli_stmt_execute($stmt);
        return mysqli_stmt_affected_rows($stmt);
    }

    // Function to retrieve a specific setting value, including tenant isolation
    public function getSetting($tenantID, $key) {
        $sql = "SELECT Value FROM Settings WHERE TenantID = ? AND Key = ?";
        $stmt = mysqli_prepare($this->db, $sql);
        mysqli_stmt_bind_param($stmt, 'is', $tenantID, $key);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        return mysqli_fetch_assoc($result)['Value'];
    }
}
?>
