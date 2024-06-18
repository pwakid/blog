<?php
class LogController {
    private $db;

    // Constructor to initialize the database connection
    public function __construct($dbConnection) {
        $this->db = $dbConnection;
    }

    // Function to create a log entry with more detailed information
    public function createLog($logData) {
        $sql = "INSERT INTO Logs (TenantID, UserID, Action, LogDate, IPAddress, UserAgent, AdditionalData) VALUES (?, ?, ?, NOW(), ?, ?, ?)";
        $stmt = mysqli_prepare($this->db, $sql);
        mysqli_stmt_bind_param($stmt, 'iissss',
            $logData['TenantID'],
            $logData['UserID'],
            $logData['Action'],
            $logData['IPAddress'],
            $logData['UserAgent'],
            $logData['AdditionalData']
        );
        mysqli_stmt_execute($stmt);
        return mysqli_insert_id($this->db);
    }

    // Function to list all logs for a specific tenant
    public function listLogs($tenantId) {
        $sql = "SELECT * FROM Logs WHERE TenantID = ?";
        $stmt = mysqli_prepare($this->db, $sql);
        mysqli_stmt_bind_param($stmt, 'i', $tenantId);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }
}
?>
