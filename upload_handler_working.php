<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['files']) && isset($_POST['subfolder'])) {
    $selectedSubfolder = $_POST['subfolder'];
    $currentDate = date('Y-m-d');
    $uploadDir = 'upload/' . $selectedSubfolder . '/';
    
    // Create the upload directory if it doesn't exist
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $uploadedFiles = [];

    // Loop through each uploaded file
    foreach ($_FILES['files']['tmp_name'] as $key => $tmp_name) {
        $fileName = $_FILES['files']['name'][$key];
        $newFileName = pathinfo($fileName, PATHINFO_FILENAME) . '_' . $currentDate . '.' . pathinfo($fileName, PATHINFO_EXTENSION);
        $uploadFile = $uploadDir . $newFileName;

        if (move_uploaded_file($tmp_name, $uploadFile)) {
            $uploadedFiles[] = $uploadFile;
        }
    }

    // Perform database insertion with $uploadedFiles array

    echo "File(s) uploaded successfully.";
}
?>
