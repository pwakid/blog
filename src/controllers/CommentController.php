<?php
class CommentController {
    private $db;

    // Constructor to initialize the database connection
    public function __construct($dbConnection) {
        $this->db = $dbConnection;
    }

    // Function to create a new comment
    public function createComment($commentData) {
        $sql = "INSERT INTO Comments (TenantID, PostID, UserID, Content, CommentDate) VALUES (?, ?, ?, ?, NOW())";
        $stmt = mysqli_prepare($this->db, $sql);
        mysqli_stmt_bind_param($stmt, 'iiis', 
            $commentData['TenantID'], 
            $commentData['PostID'], 
            $commentData['UserID'], 
            $commentData['Content']
        );
        mysqli_stmt_execute($stmt);
        return mysqli_insert_id($this->db);
    }

    // Function to retrieve a comment by ID, including tenant isolation
    public function getComment($commentId, $tenantId) {
        $sql = "SELECT * FROM Comments WHERE CommentID = ? AND TenantID = ?";
        $stmt = mysqli_prepare($this->db, $sql);
        mysqli_stmt_bind_param($stmt, 'ii', $commentId, $tenantId);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        return mysqli_fetch_assoc($result);
    }

    // Function to update an existing comment, including tenant isolation
    public function updateComment($commentId, $newData, $tenantId) {
        $sql = "UPDATE Comments SET Content = ? WHERE CommentID = ? AND TenantID = ?";
        $stmt = mysqli_prepare($this->db, $sql);
        mysqli_stmt_bind_param($stmt, 'sii', 
            $newData['Content'], 
            $commentId, 
            $tenantId
        );
        mysqli_stmt_execute($stmt);
        return mysqli_stmt_affected_rows($stmt);
    }

    // Function to delete a comment, including tenant isolation
    public function deleteComment($commentId, $tenantId) {
        $sql = "DELETE FROM Comments WHERE CommentID = ? AND TenantID = ?";
        $stmt = mysqli_prepare($this->db, $sql);
        mysqli_stmt_bind_param($stmt, 'ii', $commentId, $tenantId);
        mysqli_stmt_execute($stmt);
        return mysqli_stmt_affected_rows($stmt);
    }

    // Function to list all comments for a specific post within a tenant
    public function listCommentsByPost($postId, $tenantId) {
        $sql = "SELECT * FROM Comments WHERE PostID = ? AND TenantID = ?";
        $stmt = mysqli_prepare($this->db, $sql);
        mysqli_stmt_bind_param($stmt, 'ii', $postId, $tenantId);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }
}
?>
