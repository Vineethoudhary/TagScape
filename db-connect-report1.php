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
// ob_start(); // Start output buffering at the beginning of your script
if(isset($_POST['submit'])){
    $fileCount = count($_FILES['files']['name']);
    // Check if 'tags' is set in $_POST
    $tags = isset($_POST['tags']) ? $_POST['tags'] : '';
    // Check if 'label' is set in $_POST
    $label = isset($_POST['label']) ? $_POST['label'] : '';
    // Get the folder name from the uploaded file's path
    $folderName = isset($_POST['subfolder']) ? $_POST['subfolder'] : '';
        
    $result = false; // Initialize result as false
    
    for($i=0; $i<$fileCount; $i++){
        $fileName = $_FILES['files']['name'][$i];
        $fileTempName = $_FILES['files']['tmp_name'][$i];
        
        // Get the selected subfolder name
        $subfolderName = $_POST['subfolder'];
        
        // Use the subfolder name in the target path
        $targetPath = "./upload/" . $subfolderName . "/" . $fileName;
    
        // Check if file already exists in database
        $sql_check = "SELECT * FROM files WHERE file='$fileName'";
        $result_check = mysqli_query($conn, $sql_check);
    
        if(mysqli_num_rows($result_check) > 0) {
            echo "<script type='text/javascript'>alert('File \'$fileName\' already exists.');</script>";
            continue; // Skip this iteration and move on to the next file
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
    $sql = "INSERT INTO files(file, tags, type, folder_name, path, label) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ssssss', $fileName, $tags, $type, $folderName, $newPath, $label);
    $result = $stmt->execute();
        }
     }

    
    // if($result){
    //     header('Location: index.php?page=left_view.php&msg=ins');
    //     exit;
    // } else {
    //     echo "Error inserting file into database.";
    //     exit;
    // }
    // ob_end_flush(); // Flush the output buffer and turn off output buffering at the end of your script


    //for changing the tag's name   ---> starts from here  
    if(isset($_POST['id']) && isset($_POST['tag'])) {
        $id = $_POST['id'];
        $tag = $_POST['tag'];
    
        $sql = "UPDATE files SET tags = ? WHERE id = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, 'si', $tag, $id);
        mysqli_stmt_execute($stmt);
    }
    //for changing the tag's name   ---> end's here
}
//for left_view and right_view query is ending here


//for deleting the images from the database in right_view.php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['file'])) {
    $fileName = $_POST['file'];
    
    // Delete the image file
    if (unlink("upload/" . $fileName)) {     
      // Use prepared statements to prevent SQL injection
      $stmt = $conn->prepare("DELETE FROM files WHERE file = ?");
      $stmt->bind_param("s", $fileName);
      $stmt->execute();
  
      // Check if the query was successful
      if ($stmt->affected_rows > 0) {
          echo "Image and database record deleted successfully.";
      } else {
          echo "Error deleting image from the database.";
      }
      // Close the statement
      $stmt->close();
  
  }
}
//for deleting the images from the database in right_view.php

// File upload handling(calculation_check ---> semulation-->sumit sir)
    if (isset($_FILES['excelFile'])) {
    $fileData = file_get_contents($_FILES['excelFile']['tmp_name']);

    // Prepare and execute SQL statement
    $stmt = $conn->prepare("INSERT INTO calculation_check (name , created , type) VALUES ('$name', '$created' , '$type')");
    $stmt->bind_param("b", $fileData);
    $stmt->execute();

    // Check if the query was successful
    if ($stmt->affected_rows > 0) {
        echo "Excel file saved successfully in the database.";
    } else {
        echo "Error saving Excel file in the database.";
    }

    // Close the statement
    $stmt->close();
    echo $stmt->error;
}   

?>