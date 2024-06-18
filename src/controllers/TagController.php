<?php
class TagController {
    private $db;

    // Constructor to initialize the database connection
    public function __construct($dbConnection) {
        $this->db = $dbConnection;
    }

    // Function to create a new tag
    public function createTag($tagData) {
        $sql = "INSERT INTO Tags (TenantID, Name) VALUES (?, ?)";
        $stmt = mysqli_prepare($this->db, $sql);
        mysqli_stmt_bind_param($stmt, 'is', 
            $tagData['TenantID'], 
            $tagData['Name']
        );
        mysqli_stmt_execute($stmt);
        return mysqli_insert_id($this->db);
    }

    // Function to list all tags within a tenant
    public function listTags($tenantId) {
        $sql = "SELECT * FROM Tags WHERE TenantID = ?";
        $stmt = mysqli_prepare($this->db, $sql);
        mysqli_stmt_bind_param($stmt, 'i', $tenantId);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }
}
?>
