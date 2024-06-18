<?php
class PostController {
    private $db;

    // Constructor to initialize the database connection
    public function __construct($dbConnection) {
        $this->db = $dbConnection;
    }

    // Function to create a new post
    public function createPost($postData) {
        $sql = "INSERT INTO Posts (TenantID, AuthorID, Title, Content, PostDate, CategoryID, Enabled) VALUES (?, ?, ?, ?, NOW(), ?, 1)";
        $stmt = mysqli_prepare($this->db, $sql);
        mysqli_stmt_bind_param($stmt, 'iissi', 
            $postData['TenantID'], 
            $postData['AuthorID'], 
            $postData['Title'], 
            $postData['Content'], 
            $postData['CategoryID']
        );
        mysqli_stmt_execute($stmt);
        return mysqli_insert_id($this->db);
    }

    // Function to retrieve a post by ID, including tenant isolation
    public function getPost($postId, $tenantId) {
        $sql = "SELECT * FROM Posts WHERE PostID = ? AND TenantID = ?";
        $stmt = mysqli_prepare($this->db, $sql);
        mysqli_stmt_bind_param($stmt, 'ii', $postId, $tenantId);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        return mysqli_fetch_assoc($result);
    }

    // Function to update an existing post, including tenant isolation
    public function updatePost($postId, $newData, $tenantId) {
        $sql = "UPDATE Posts SET Title = ?, Content = ?, CategoryID = ? WHERE PostID = ? AND TenantID = ?";
        $stmt = mysqli_prepare($this->db, $sql);
        mysqli_stmt_bind_param($stmt, 'ssiii', 
            $newData['Title'], 
            $newData['Content'], 
            $newData['CategoryID'], 
            $postId, 
            $tenantId
        );
        mysqli_stmt_execute($stmt);
        return mysqli_stmt_affected_rows($stmt);
    }

    // Function to delete a post, including tenant isolation
    public function deletePost($postId, $tenantId) {
        $sql = "DELETE FROM Posts WHERE PostID = ? AND TenantID = ?";
        $stmt = mysqli_prepare($this->db, $sql);
        mysqli_stmt_bind_param($stmt, 'ii', $postId, $tenantId);
        mysqli_stmt_execute($stmt);
        return mysqli_stmt_affected_rows($stmt);
    }

    // Function to list all posts within a tenant
    public function listPosts($tenantId) {
        $sql = "SELECT * FROM Posts WHERE TenantID = ?";
        $stmt = mysqli_prepare($this->db, $sql);
        mysqli_stmt_bind_param($stmt, 'i', $tenantId);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }
}
?>
