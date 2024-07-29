<?php
   $conn = mysqli_connect('localhost','root','','tms_db');
   if(!$conn){
    echo 'Connection Failed';
}


//for left_view and right_view query is starting from here

// Get the label options from the database
$labelOptions = array();
$sql = "SHOW COLUMNS FROM files LIKE 'label'";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
$enumStr = $row['Type'];
preg_match("/^enum\(\'(.*)\'\)$/", $enumStr, $matches);
$enumValues = explode("','", $matches[1]);
// Get the label options from the database ---> end's here


// *****for inserting the images , video and pdf's into the database along with their tags name *****
if(isset($_POST['submit'])){
    $fileCount = count($_FILES['files']['name']);
    // Check if 'tags' is set in $_POST
    $tags = isset($_POST['tags']) ? $_POST['tags'] : '';
    // Check if 'label' is set in $_POST
    $label = isset($_POST['label']) ? $_POST['label'] : '';
    // Get the folder name from the uploaded file's path
    $folderName = isset($_POST['folder_name']) ? $_POST['folder_name'] : '';
    
    $result = false; // Initialize result as false
    
    for($i=0; $i<$fileCount; $i++){
        $fileName = $_FILES['files']['name'][$i];
        $fileTempName = $_FILES['files']['tmp_name'][$i];
        $fileSizeKB = $_FILES['files']['size'][$i]; // Get the file size in bytes
        
        // Get the selected subfolder name
        $subfolderName = $_POST['subfolder'];
        
        // Use the subfolder name in the target path
        $targetPath = "./upload/" . $subfolderName . "/" . $fileName;
        
        // Prepare statement
        $stmt = $conn->prepare("SELECT * FROM files WHERE file= ?");
        $stmt->bind_param("s", $fileName); // "s" means the database expects a string
        $stmt->execute();
        
        // Get the result
        $result = $stmt->get_result();
        
       // Check if the file already exists in the database
       if ($result->num_rows > 0) {
        // Return a JSON response indicating the duplicate file
        http_response_code(400); // Set HTTP status code to indicate client error
        echo json_encode(array("error" => "File '$fileName' already exists."));
        continue; // Terminate further execution
       }
       
       
       if(move_uploaded_file($fileTempName, $targetPath)){
        // Get the MIME type of the file
        $mime = mime_content_type($targetPath);
        // Extract the extension from the MIME type
        $type = explode('/', $mime)[1];
        // Save the folder name as the local computer name
        $localPath = $_SERVER['REMOTE_ADDR']; // Get the user's IP address as the folder name
            
        // Get the selected subfolder name
        $subfolderName = $_POST['subfolder'];
            
        // Create the folder if it doesn't exist
        $folderPath = "./upload/" . $subfolderName;
        if (!is_dir($folderPath)) {
            mkdir($folderPath, 0777, true);
        }
            
        // Move the uploaded file to the created folder
        $newPath = $folderPath.'/'.$fileName;
        rename($targetPath, $newPath);
            
        // Include the tags, type, and localPath in your SQL INSERT statement
        $sql = "INSERT INTO files(file, tags, type, folder_name, path, label, size) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssssd", $fileName, $tags, $type, $folderName, $newPath, $label, $fileSizeKB); // "i" for integer (file size)
            
        // Execute the prepared statement
        $result = $stmt->execute();
            
        if($result){
            echo "File '$fileName' uploaded successfully.";
        }else{
            echo "Error: " . $conn->error;
        }
        }
    }
}
//for left_view and right_view query is ending here


//for changing the tag's name   ---> starts from here  
if (isset($_POST['id']) && isset($_POST['tag'])) {
    $id = $_POST['id'];
    $tag = $_POST['tag'];

    $sql = "UPDATE files SET tags = ? WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'si', $tag, $id);
    mysqli_stmt_execute($stmt);

    // Check if the query was successful
    if (mysqli_stmt_affected_rows($stmt) > 0) {
        // Tag update successful
        echo json_encode(array("status" => "success"));
    } else {
        // Tag update failed
        echo json_encode(array("status" => "error"));
    }
    
    // Close the statement and connection
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
} 
// else {
//     // Invalid request
//     echo json_encode(array("status" => "error", "message" => "Invalid request"));
// }
//for changing the tag's name   ---> end's here


//for deleting the images from the database in right_view.php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['files'])) {
    $fileIds = explode(',', $_POST['files']);
    $deletedFiles = 0;

    foreach ($fileIds as $fileId) {
        // Retrieve the file information from the database using the file ID
        $stmt = $conn->prepare("SELECT path, file FROM files WHERE id = ?");
        $stmt->bind_param("i", $fileId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            $fullPath = $row['path']; // Use the actual path from the database
            $fileName = $row['file'];

            // Now attempt to delete the image file
            if (unlink($fullPath)) {
                // If the file was successfully deleted, proceed to delete the record from the database
                $stmt = $conn->prepare("DELETE FROM files WHERE id = ?");
                $stmt->bind_param("i", $fileId);
                $stmt->execute();

                // Check if the query was successful
                if ($stmt->affected_rows > 0) {
                    $deletedFiles++;
                } else {
                    echo "Error deleting file from the database: " . $fileName . "<br>";
                }
            } else {
                echo "Error deleting file from the directory: " . $fileName . "<br>";
            }
        } else {
            echo "File not found in the database: " . $fileId . "<br>";
        }

        // Close the statement and result set
        $stmt->close();
        $result->close();
    }

    echo $deletedFiles . " files deleted successfully.";
}
//for deleting the images from the database in right_view.php

?>