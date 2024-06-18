<?php
class CategoryController {
    private $db;

    // Constructor to initialize the database connection
    public function __construct($dbConnection) {
        $this->db = $dbConnection;
    }

    // Function to create a new category
    public function createCategory($categoryData) {
        $sql = "INSERT INTO Categories (TenantID, Name, Description) VALUES (?, ?, ?)";
        $stmt = mysqli_prepare($this->db, $sql);
        mysqli_stmt_bind_param($stmt, 'iss', 
            $categoryData['TenantID'], 
            $categoryData['Name'], 
            $categoryData['Description']
        );
        mysqli_stmt_execute($stmt);
        return mysqli_insert_id($this->db);
    }

    // Function to retrieve a category by ID, including tenant isolation
    public function getCategory($categoryId, $tenantId) {
        $sql = "SELECT * FROM Categories WHERE CategoryID = ? AND TenantID = ?";
        $stmt = mysqli_prepare($this->db, $sql);
        mysqli_stmt_bind_param($stmt, 'ii', $categoryId, $tenantId);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        return mysqli_fetch_assoc($result);
    }

    // Function to update an existing category, including tenant isolation
    public function updateCategory($categoryId, $newData, $tenantId) {
        $sql = "UPDATE Categories SET Name = ?, Description = ? WHERE CategoryID = ? AND TenantID = ?";
        $stmt = mysqli_prepare($this->db, $sql);
        mysqli_stmt_bind_param($stmt, 'ssii', 
            $newData['Name'], 
            $newData['Description'], 
            $categoryId, 
            $tenantId
        );
        mysqli_stmt_execute($stmt);
        return mysqli_stmt_affected_rows($stmt);
    }

    // Function to delete a category, including tenant isolation
    public function deleteCategory($categoryId, $tenantId) {
        $sql = "DELETE FROM Categories WHERE CategoryID = ? AND TenantID = ?";
        $stmt = mysqli_prepare($this->db, $sql);
        mysqli_stmt_bind_param($stmt, 'ii', $categoryId, $tenantId);
        mysqli_stmt_execute($stmt);
        return mysqli_stmt_affected_rows($stmt);
    }

    // Function to list all categories within a tenant
    public function listCategories($tenantId) {
        $sql = "SELECT * FROM Categories WHERE TenantID = ?";
        $stmt = mysqli_prepare($this->db, $sql);
        mysqli_stmt_bind_param($stmt, 'i', $tenantId);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }
}
?>
