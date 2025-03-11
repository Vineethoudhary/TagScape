<?php
include 'db-connect-reports.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['files']) && isset($_POST['subfolder'])) {
    $selectedSubfolder = $_POST['subfolder'];
    // $currentDate = date('Y-m-d');
    $uploadDir = './upload/' . $selectedSubfolder . '/';
    
    // Create the upload directory if it doesn't exist
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $uploadedFiles = [];
    $fileCount = count($_FILES['files']['name']);
    
    for ($i = 0; $i < $fileCount; $i++) {
        $fileName = $_FILES['files']['name'][$i];
        $fileTempName = $_FILES['files']['tmp_name'][$i];
        $fileSizeKB = $_FILES['files']['size'][$i];
        
        $tags = isset($_POST['tags']) ? $_POST['tags'] : '';
        $label = isset($_POST['label']) ? $_POST['label'] : '';
        $folderName = isset($_POST['folder_name']) ? $_POST['folder_name'] : '';
        $description = isset($_POST['description']) ? $_POST['description'] : ''; // New description field
        
        // Sanitize the filename and append the current date
        $sanitizedFileName = preg_replace('/[^a-zA-Z0-9-_\.]/', '_', $fileName);
        $newFileName = pathinfo($sanitizedFileName, PATHINFO_FILENAME) . '.' . pathinfo($sanitizedFileName, PATHINFO_EXTENSION);
        $targetPath = $uploadDir . $newFileName;
        
        $stmt = $conn->prepare("SELECT * FROM files WHERE file = ? AND size = ?");
        $stmt->bind_param("sd", $sanitizedFileName, $fileSizeKB); // "s" for string, "d" for double (size in KB)
        $stmt->execute();
        $result = $stmt->get_result();
        
       // Check if the file already exists in the database
       if ($result->num_rows > 0) {
           // Return a JSON response indicating the duplicate file
           http_response_code(400); // Set HTTP status code to indicate client error
           echo json_encode(array("File '$fileName' already exists.")) . "\n";
           continue; // Terminate further execution
       }else{
        echo json_encode(array("File '$fileName' uploaded successfully.")) . "\n";
       }

        if (move_uploaded_file($fileTempName, $targetPath)) {
            $mime = mime_content_type($targetPath);
            $type = explode('/', $mime)[1];
            
            // Store the sanitized file name in the database without URL encoding
            $sql = "INSERT INTO files(file, tags, type, folder_name, path, label, description, size) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssssssd", $sanitizedFileName, $tags, $type, $folderName, $targetPath, $label, $description, $fileSizeKB);
            
            // Execute the prepared statement
            $result = $stmt->execute();
        }
        
        if ($stmt !== null) {
            $stmt->close();
        }
    }
}

$conn->close();
?>