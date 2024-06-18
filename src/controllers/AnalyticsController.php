<?php
class AnalyticsController {
    private $db;

    // Constructor to initialize the database connection
    public function __construct($dbConnection) {
        $this->db = $dbConnection;
    }

    // Function to record a visit to a page
    public function recordVisit($tenantID, $page) {
        $sql = "INSERT INTO Analytics (TenantID, Page, Visits) VALUES (?, ?, 1) 
                ON DUPLICATE KEY UPDATE Visits = Visits + 1";
        $stmt = mysqli_prepare($this->db, $sql);
        mysqli_stmt_bind_param($stmt, 'is', $tenantID, $page);
        mysqli_stmt_execute($stmt);
        return mysqli_stmt_affected_rows($stmt);
    }

    // Function to retrieve visit count for a specific page
    public function getPageVisits($tenantID, $page) {
        $sql = "SELECT Visits FROM Analytics WHERE TenantID = ? AND Page = ?";
        $stmt = mysqli_prepare($this->db, $sql);
        mysqli_stmt_bind_param($stmt, 'is', $tenantID, $page);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        return mysqli_fetch_assoc($result)['Visits'];
    }
}
?>
