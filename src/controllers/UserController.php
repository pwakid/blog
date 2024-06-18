<?php
class UserController {
    private $db;

    // Constructor to initialize the database connection
    public function __construct($dbConnection) {
        $this->db = $dbConnection;
    }

    // Function to create a new user
    public function createUser($userData) {
        $sql = "INSERT INTO Users (TenantID, Username, Email, PasswordHash, Role, Enabled) VALUES (?, ?, ?, ?, ?, 1)";
        $stmt = mysqli_prepare($this->db, $sql);
        mysqli_stmt_bind_param($stmt, 'issss', 
            $userData['TenantID'], 
            $userData['Username'], 
            $userData['Email'], 
            $userData['PasswordHash'], 
            $userData['Role']
        );
        mysqli_stmt_execute($stmt);
        return mysqli_insert_id($this->db);
    }

    // Function to retrieve a user by ID, including tenant isolation
    public function getUser($userId, $tenantId) {
        $sql = "SELECT * FROM Users WHERE UserID = ? AND TenantID = ?";
        $stmt = mysqli_prepare($this->db, $sql);
        mysqli_stmt_bind_param($stmt, 'ii', $userId, $tenantId);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        return mysqli_fetch_assoc($result);
    }

    // Function to update an existing user, including tenant isolation
    public function updateUser($userId, $newData, $tenantId) {
        $sql = "UPDATE Users SET Username = ?, Email = ?, Role = ? WHERE UserID = ? AND TenantID = ?";
        $stmt = mysqli_prepare($this->db, $sql);
        mysqli_stmt_bind_param($stmt, 'sssii', 
            $newData['Username'], 
            $newData['Email'], 
            $newData['Role'], 
            $userId, 
            $tenantId
        );
        mysqli_stmt_execute($stmt);
        return mysqli_stmt_affected_rows($stmt);
    }

    // Function to delete a user, including tenant isolation
    public function deleteUser($userId, $tenantId) {
        $sql = "DELETE FROM Users WHERE UserID = ? AND TenantID = ?";
        $stmt = mysqli_prepare($this->db, $sql);
        mysqli_stmt_bind_param($stmt, 'ii', $userId, $tenantId);
        mysqli_stmt_execute($stmt);
        return mysqli_stmt_affected_rows($stmt);
    }

    // Function to list all users within a tenant
    public function listUsers($tenantId) {
        $sql = "SELECT * FROM Users WHERE TenantID = ?";
        $stmt = mysqli_prepare($this->db, $sql);
        mysqli_stmt_bind_param($stmt, 'i', $tenantId);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }
}
?>
