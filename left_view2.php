<?php 
// include 'db-connect-reports.php';
include 'create_folder.php';
include 'upload_handler.php';
?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- <title>image & pfd's upload</title> -->
    <link rel="stylesheet" type="text/css" href="css/left_view.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
</head>

<body background="black">

<!-- *****LEFTSIDE MENU STARTS FROM HERE***** -->
<!-- <div class="row">  -->
<div class="roll" style="margin-top: -2vh; margin-bottom: -11vh; width: 89%;">

<button onclick='changeTags()' style="hover: grey;margin-top: 1vh;margin-left: 1vw;">Change Tags</button>;

<div class="search-print-container" style="display: inline-block; vertical-align: top;">
    <form id="searchForm" method="get" action="./index.php" style="display: flex; justify-content: center; margin-top: -13.5vh;margin-left: 28.5vw;">
        <input type="hidden" name="page" value="right_view">
        <input type="text" id="search" name="search" class="search-input" autocomplete="off" placeholder="Search by tag" style="margin-left: 13vw;">
        <input type="submit" class="search" value="Search" >
    </form>
</div>
    
<!-- <div class="folder"> -->

        <?php
        // $target_dir = "uploads/";
        // if(isset($_FILES["fileToUpload"])) {
        //     $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
            
        //     if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
        //         echo "The file ". htmlspecialchars( basename( $_FILES["fileToUpload"]["name"])). " has been uploaded.";
        //     } else {
        //         echo "Sorry, there was an error uploading your file.";
        //     }
        // } 
        ?>
    <!-- </div>***class folder*** -->

    <?php

$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';
if (!empty($searchTerm)) {
    // Use prepared statements to prevent SQL injection
    $stmt = $conn->prepare("SELECT * FROM files WHERE tags LIKE ? OR label LIKE ?");
    $searchTerm = "%$searchTerm%";
    $stmt->bind_param("ss", $searchTerm, $searchTerm);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows > 0){
        $fetch = $result->fetch_assoc();// Fetch first row
    
        $tags = $fetch['tags']; 
        $label = $fetch['label'];
        $id = $fetch['id'];

        echo "<button onclick='changeTags()' style='margin-left: -6vw; margin-top: 1vh; hover: grey;'>Change Tags</button>";
        // echo "<button id='download-button' style='margin-left: 1vw; margin-top: 1vh;'>Download</button>";
        echo "<div style='margin-top: 0vh; margin-left: -16vw;'>";
        echo "<span style='margin-right: 2vw; margin-left: 18vw; background: #f0ffff; border-radius: 5px; width: 14rem; display: inline-block;'><b>Tags:</b> $tags</span>";
        echo "<span style='margin-right: 2vw; background: #f0ffff; border-radius: 5px; width: 14rem; display: inline-block;'><b>Label:</b> $label</span>";
        echo "</div>";
    }
}
?>

    <?php
    $sql = "SELECT * FROM files";
    $result = mysqli_query($conn , $sql);
    if(mysqli_num_rows($result)>0){
        while($fetch = mysqli_fetch_assoc($result)){
        $fileType = pathinfo($fetch['file'], PATHINFO_EXTENSION);
        $tag = $fetch['tags']; // fetch the tag from the current row
        $id = $fetch['id'];
        $filePath = 'upload/ Facade Application wise/' . $fetch['file'];
        
        if (!file_exists($filePath)) {
            continue; // Skip this iteration if file doesn't exist
        }
        
        if ($fileType == 'pdf') {
            echo "<embed src='$filePath' width='178.4' height='178.4' type='application/pdf'>";
        } else if ($fileType == 'mp4' || $fileType == 'mov' || $fileType == 'avi' || $fileType == 'flv' || $fileType == 'wmv' ||
        $fileType == 'MP4' || $fileType == 'MOV' || $fileType == 'AVI' || $fileType == 'FLV' || $fileType == 'WMV') {
            echo "<video width='165' height='165' controls style='margin-right: 5px; margin-left: 2px; margin-bottom: 5px; margin-top:'>";
            echo "<source src='$filePath' type='video/$fileType'>";
            echo "Your browser does not support the video tag.";
            echo "</video>";
        } else {
            $id = $fetch['id'];
            echo "<div style='display:inline-block; position:relative; margin-right: 6px; margin-bottom: 6px; margin-left: 2px;'>";
            echo "<img src='$filePath' class='myImg' width='165' height='165' style='padding: 2px;' data-tag='".$tag."'>"; 
            echo "<span style='position:absolute; top: 6px; left: 6px; color:white; background-color: dodgerblue; font-size: 10px; padding: 2px;'>".$tag."</span>"; // Display the tag name on top of the image
            echo "<input type='checkbox' class='file-checkbox' data-id='".$id."' style='position:absolute; top:1px; left:-0.15vw;'>";
            echo "</div>";
        }
        }//while clause
    }//if clause
?>
 <!-- For zooming the image by double click ===> start's from here -->
 <div id="myModal" class="modal">
    <!-- The Close Button -->
    <span class="close">&times;</span>
    <!-- Modal Content (The Image) -->
    <img class="modal-content" id="img01">
    <!-- Modal Caption (Image Text) -->
    <div id="caption"></div>
</div>
<!-- For zooming the image by double click ===> end's here -->

</div> <!-- ***class scroll*** -->
<!-- *****lEFTSIDE MENU END'S HERE***** -->



<!-- *****MIDDLE MENU STARTS FROM HERE***** -->
<div class="formdesign" style="width: 13.19%;">

    <form action="" method="POST" enctype="multipart/form-data">

    <form action="" method="post" >
        <label style="display: flex; flex: 1; text-align: left; color: ivory; margin-left: 4vw; margin-top: 2vh;">Create Folder </label>
        <input type="text" name="folder_name" placeholder="Enter folder name here" style="margin-top: 0vh; margin-left: 2vw">
        <input type="submit" value="Create Folder" name="create_folder" style="margin-left: 4vw;><br>
        </form>
    <input type="hidden" name="id" value="<?php echo $id; ?>">
    <input type="hidden" name="tag" value="<?php echo $tags; ?>">

    <hr style="border-top: 1px solid white;">
    <h2 style="color: ivory; font-size: 20px; text-shadow: 1px 1px 2px black; margin-top: 3vh;" align="center">Please Select Image(s) or PDF(s)</h2>
        <div class="center">
            <input type="file" id="fileInput" name="files[]" accept="image/*,video/*,.pdf" multiple style="display: none;">
            <div id="dropArea" ondrop="handleDrop(event)" ondragover="handleDragOver(event)" ondragenter="handleDragEnter(event)" ondragleave="handleDragLeave(event)">
                <p style="color:#c1bdbd; text-shadow: 1px 1px 2px black; margin-left: 2vw;">Drag and drop files here, or <a href="#" onclick="browseFiles()" style="color: lemonchiffon;">browse</a> to select files.</p>
                
                <?php
                // if(isset($_GET['msg']) AND $_GET['msg']=='ins'){
                //     echo "<p style='text-align: center; font-type:bold; color: ivory; background-color:#07a407; border-radius: 12px; padding: 4px; box-shadow: 2px 4px 6px #6ad56a;'>File Uploaded Successfully..!!</p>";
                // }
                ?>                               
            
            <hr style="border-top: 1px solid white; width: 16vw; margin-left: -1vw;">
            <label for="label"style="color: ivory; text-shadow: 1px 1px 2px black; margin-top: 0px; margin-left: -1vw;">Select Label:</label><br>
            <select name="label" id="label" style="margin-left: 0vw; margin-top: -3vh; width: 11vw; font-size: 1rem;">
            <option value="" disabled selected>SELECT</option><br>
            <?php
            // Assuming you have retrieved the enum values from the database and stored them in an array called $enumValues
            foreach ($enumValues as $value) {
                echo "<option value=\"$value\">$value</option>";
            }
            ?>
            </select></br><br>

            <label for="subfolder" style="color: ivory;">Select Folder:</label><br>
            <select name="subfolder" id="subfolder">
                <option value="" disabled selected>SELECT</option><br>
                <?php
                $folderPath = './upload/';
                $subfolders = array_filter(glob($folderPath . '*'), 'is_dir');
                echo "<$subfolder>";
                foreach ($subfolders as $subfolder) {
                    $subfolderName = basename($subfolder);
                    echo "<option value=\"$subfolderName\">$subfolderName</option>";
                }
                ?>
            </select></br>

            <label style="color: ivory; text-shadow: 1px 1px 2px black; margin-top: 2vh; margin-left: 0vw;">Enter tags: </label><br>
            <input type="text" name="tags" id="tags" style="margin-top: 0px; border-radius: 10px; box-shadow: 3px 3px 5px rgba(0, 0, 0, 0.3); margin-left: 1vw; transition: box-shadow 0.3s ease-in-out; width: 150px; height: 30px;" onfocus="this.style.boxShadow='0 0 10px blue';" onblur="this.style.boxShadow='3px 3px 5px rgba(0, 0, 0, 0.3)';">
            <button type="submit" name="submit" onclick="return showPopup()" style="box-shadow: 3px 3px 5px rgba(0, 0, 0, 0.3); border-radius: 10px; transition: box-shadow 0.3s ease-in-out;" onfocus="this.style.boxShadow='0 0 10px blue';" onblur="this.style.boxShadow='3px 3px 5px rgba(0, 0, 0, 0.3)';">
            <i class="fa fa-upload" aria-hidden="true"></i>
            </button><br><br>

        </div><br>

        <!-- Saving the files under the subfolder present in the folder structure in local system start's from here -->
        <?php
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['file']) && isset($_POST['subfolder'])) {
            $selectedSubfolder = $_POST['subfolder']; // Get the selected subfolder
            $currentDate = date('Y-m-d'); // Get the current date
            $uploadDir = glob('upload/' . $selectedSubfolder . '/'); // Include the subfolder in the upload directory path
            $fileName = basename($_FILES['file']['name']);
            $fileNameWithoutExtension = pathinfo($fileName, PATHINFO_FILENAME);
            $extension = pathinfo($fileName, PATHINFO_EXTENSION);
            $newFileName = $fileNameWithoutExtension . '_' . $currentDate . '.' . $extension; // Append the date to the filename
            $uploadFile = $uploadDir . $newFileName;
            
            if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadFile)) {
                echo "File was successfully uploaded.";
            } else {
                echo "File upload failed.";
            }
        }
        ?>     
        <!-- Saving the files under the subfolder present in the folder structure end's here -->


        <!-- Creating the folder in the upload directory  > start's from here -->      
         <?php
         $target_dir = './upload/';

        if(isset($_POST['create_folder']) && !empty($_POST['folder_name'])) {
            $folder_name = $_POST['folder_name'];
            if (!is_dir($target_dir . $folder_name)) {
                mkdir($target_dir . $folder_name, 0777, true);
                echo "<span style='color:white;'>Folder '$folder_name' has been created.</span>";
            } else {
                echo "<span style='color:white;'>Folder already exists.</span>";
            }
        }
        ?>
</form>


              <!-- Display folders -->
              <div style="color: ivory; text-shadow: 1px 1px 2px black; margin-top: -4vh;">
              <hr style="border-top: 1px solid white;">
              <p style="display: flex; flex: 1; text-align: left; color: ivory; margin-left: 1vw;">Folders:</p>
              <?php
              $folderPath = './upload/';
              $folders = array_filter(glob($folderPath . '*'), 'is_dir');

              foreach ($folders as $folder) {
                $folderName = basename($folder);
                echo "<div style='display: flex; align-items: center; margin-bottom: 10px;'>
                     <a href='?folder=$folderName' style='text-decoration: none; color: black;'>
                         <img src='./upload/uploads/folder-icon.jpeg' style='width: 20px; height: 20px; margin-left: 1vw;'>
                     </a>
                     <a href='?folder=$folderName' style='text-decoration: none; color: ivory; margin-left: 0.3vw;'>$folderName</a>
                </div>";
             }
   
             // Get files within the folder
             if (isset($_GET['folder'])) {
                $selectedFolder = $_GET['folder'];
                $folderPath = './upload/' . $selectedFolder;
                $files = glob($folderPath . '/*.{jpg,jpeg,png,gif}', GLOB_BRACE); // Fetch only image files
                
                echo "<ul>";
                foreach ($files as $file) {
                    $fileName = basename($file);
                    echo "<li><a href='$file' target='_blank'><img src='$file' alt='$fileName' style='width: 100px; height: auto;'></a></li>";
                }
                echo "</ul>";
            }
            ?>
          </div>
      
    </form>
    
</div><br> <br> 
<!-- <div id="tag-input-wrapper">
    <input type="text" id="tag-input">
</div> -->

<!-- *****MIDDLE MENU END HERE***** -->

</div> <!-- div class row is ending here -->

<script>
    //*****for drag and drop the images -->starts from here*****
    function handleDragOver(event) {
    event.preventDefault();
    event.stopPropagation();
    document.getElementById("dropArea").classList.add("dragover");
}

function handleDragEnter(event) {
    event.preventDefault();
    event.stopPropagation();
    var dropArea = document.getElementById("dropArea");
    dropArea.classList.add("dragover");
}

function handleDragLeave(event) {
    event.preventDefault();
    event.stopPropagation();
    var dropArea = document.getElementById("dropArea");
    dropArea.classList.remove("dragover");
}

function handleDrop(event) {
    event.preventDefault();
    event.stopPropagation();
    var fileInput = event.dataTransfer.files;
    document.getElementById("dropArea").classList.remove("dragover");
    if(fileInput.length > 0) {
        window.alert(fileInput.length + " file's selected!");
        showUploadForm(fileInput);
    }
}

// Attach drag and drop event listeners to the drop area
var dropArea = document.getElementById("dropArea");
if (!dropArea.hasAttribute('listener')) {
    dropArea.addEventListener("dragenter", handleDragEnter, false);
    dropArea.addEventListener("dragleave", handleDragLeave, false);
    // dropArea.addEventListener("dragover", handleDragOver, false);
    dropArea.addEventListener("drop", handleDrop, false);
    dropArea.setAttribute('listener', 'true');
}
    // for drag and drop the images end's here

    //Browsing the files from the local desktop start's from here
    function browseFiles() {
    var fileInput = document.getElementById("fileInput");
    fileInput.click();
    
    fileInput.addEventListener("change", function() {
        var selectedFiles = fileInput.files;
        
        if (selectedFiles.length > 0) {
            showUploadForm(selectedFiles);
        }
    });
    } //function browseFiles end's here
    
    function showUploadForm(selectedFiles) {
    var popupContent = document.createElement("div");

     // Counter to track the number of loaded images
     var loadedImages = 0;

    
    // Add the selected files (images or videos) to the popup content
    function addImageToPopup(file) {
        var fileReader = new FileReader();
        fileReader.onload = function (event) {
            if (file.type.match('image.*')) {
                // It's an image
                var imageElement = document.createElement("img");
                imageElement.src = event.target.result;
                imageElement.style.maxWidth = "20vw";
                imageElement.style.maxHeight = "20vh";
                imageElement.style.marginLeft = "1vw";
                imageElement.style.marginBottom = "1vh";

                popupContent.appendChild(imageElement);
            } else if (file.type.match('video.*')) {
                // It's a video
                var videoElement = document.createElement("video");
                videoElement.src = event.target.result;
                videoElement.style.maxWidth = "20vw";
                videoElement.style.maxHeight = "20vh";
                videoElement.style.marginLeft = "1vw";
                videoElement.style.marginBottom = "1vh";
                videoElement.controls = true; // Add controls so the user can play the video

                popupContent.appendChild(videoElement);
            }
            
            // Increment the counter
            loadedImages++;
            
            // Check if all media files are loaded
            if (loadedImages === selectedFiles.length) {
                addFormElements();   // Add the form elements after all media files are loaded
            }
        };
        fileReader.readAsDataURL(file);
    }  //function addImageToPopup end's here
   
    
    // Function to add the form elements(Folder & tag) to the popup content
    function addFormElements() {
    var uploadForm = document.createElement("form");
    uploadForm.action = "upload_handler.php"; // Set your form action
    uploadForm.method = "post"; // Set your form method
    uploadForm.style.display = "flex"; // Use flexbox to make the form elements align horizontally


    // Create inputs and elements for the form
    var labelSelect = document.createElement("label");
    uploadForm.appendChild(labelSelect);

    var labelDropdown = document.createElement("select");
    labelDropdown.name = "label";
    labelDropdown.id = "label";
    labelDropdown.style.marginLeft = "0vw";
    labelDropdown.style.marginTop = "0vh";
    labelDropdown.style.width = "11vw";
    labelDropdown.style.height = "30px";
    labelDropdown.style.fontSize = "1rem";
    labelDropdown.innerHTML = "<option value='' disabled selected>SELECT</option>";

    // Assuming you have retrieved the enum values from the database and stored them in an array called $enumValues
    <?php foreach ($enumValues as $value) { ?>
        var option = document.createElement("option");
        option.value = "<?php echo $value; ?>";
        option.textContent = "<?php echo $value; ?>";
        labelDropdown.appendChild(option);
    <?php } ?>

    var subfolderLabel = document.createElement("label");
    subfolderLabel.textContent = "Select Folder:";
    uploadForm.appendChild(subfolderLabel);

    var subfolderDropdown = document.createElement("select");
    subfolderDropdown.name = "subfolder";
    subfolderDropdown.id = "subfolder";
    subfolderDropdown.innerHTML = "<option value='' disabled selected>SELECT</option>";

    // Assuming you have a list of subfolders
    <?php foreach ($subfolders as $subfolder) { ?>
        var subOption = document.createElement("option");
        subOption.value = "<?php echo basename($subfolder); ?>";
        subOption.textContent = "<?php echo basename($subfolder); ?>";
        subfolderDropdown.appendChild(subOption);
    <?php } ?>

    uploadForm.appendChild(subfolderDropdown);
    uploadForm.appendChild(document.createElement("br"));

    var tagsLabel = document.createElement("label");
    tagsLabel.textContent = "Enter tags:";
    uploadForm.appendChild(tagsLabel);

    var tagsInput = document.createElement("input");
    tagsInput.type = "text";
    tagsInput.name = "tags";
    tagsInput.id = "tags";
    tagsInput.style.marginTop = "0px";
    tagsInput.style.borderRadius = "10px";
    tagsInput.style.boxShadow = "3px 3px 5px rgba(0, 0, 0, 0.3)";
    tagsInput.style.marginLeft = "1vw";
    tagsInput.style.transition = "box-shadow 0.3s ease-in-out";
    tagsInput.style.width = "150px";
    tagsInput.style.height = "30px";

    tagsInput.addEventListener("focus", function() {
        this.style.boxShadow = "0 0 10px blue";
    });

    tagsInput.addEventListener("blur", function() {
        this.style.boxShadow = "3px 3px 5px rgba(0, 0, 0, 0.3)";
    });

    uploadForm.appendChild(tagsInput);

    var submitButton = document.createElement("button");
    submitButton.type = "submit1"; //Change the type to button to prevent form submission
    submitButton.name = "submit1";
    submitButton.textContent = "Upload";
    uploadForm.enctype = "multipart/form-data";
    submitButton.style.marginLeft = "1vw"; // Add margin for spacing between the last input and the button


    submitButton.addEventListener("click", function(event) {
    event.preventDefault();
    // Handle form submission logic here
    var subfolderValue = subfolderDropdown.value;
    var tagsValue = tagsInput.value;

    // Perform AJAX request to handle file upload
    var formData = new FormData();
    for (var i = 0; i < selectedFiles.length; i++) {
        formData.append("files[]", selectedFiles[i]);
    }
    formData.append("subfolder", subfolderValue);
    formData.append("tags", tagsValue);

    var xhr = new XMLHttpRequest();
    xhr.open("POST", "upload_handler.php", true);

    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4) {
            // Check if the request was successful
            if (xhr.status === 200) {
                // Handle the response from the server
                console.log(xhr.responseText);

                // Now make another AJAX request to insert data into db-connect-report.php
                var xhr2 = new XMLHttpRequest();
                xhr2.open("POST", "db-connect-reports.php", true);
                xhr2.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

                xhr2.onreadystatechange = function() {
                    if (xhr2.readyState === 4) {
                        // Check if the request was successful
                        if (xhr2.status === 200) {
                            // Handle the response from the server
                            console.log(xhr2.responseText);
                            // Optionally, you can close the popup here
                            // popup.close();
                        } else {
                            console.error("Database connection failed.");
                        }
                    }
                };

                xhr2.send(reportData); // Send the AJAX request to db-connect-report.php
            } else {
                console.error("File upload failed." + xhr.status);
            }
        }
    };

    xhr.send(formData); // Send the AJAX request to upload_handler.php

    // Close the popup after submission
    popup.close();
});

    uploadForm.appendChild(submitButton);

    // Append form and submit button to popup content
    popupContent.appendChild(uploadForm);
}

     // Add the selected files to the popup content
     for (var i = 0; i < selectedFiles.length; i++) {
        addImageToPopup(selectedFiles[i]);
    }

    // Open the popup window
    var popup = window.open("", "Image Upload Popup", "width=800,height=600");
    popup.document.body.appendChild(popupContent);
}

    //Browsing the files from the local desktop end's here



    function displayAndPrepareForUpload(file, tag) {
    // Create a new div for the image and its tag
    var div = document.createElement("div");

    // Display a file preview - this assumes image files
    var img = document.createElement("img");
    img.src = URL.createObjectURL(file);
    div.appendChild(img);

    // Display the tag
    var p = document.createElement("p");
    p.textContent = tag;
    div.appendChild(p);

    // Add the div to the body of the document
    document.body.appendChild(div);
    }

    

    //Warning script while not giving the tags & label name while uploading
    function showPopup() {
        var tags = document.getElementById("tags").value;
        var label = document.getElementById("label").value;
        
        if (tags === "" || label === "") {
            var errorMessage = 'Please ensure the following before uploading:\n';

            if(label === "")
               errorMessage += 'Enter a label name.\n';
            if(tags === "")
               errorMessage += 'Enter a tag.\n';

            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: errorMessage,
            });
            return false;
        }
        return true;
        }
        // Waring code for tags and label ends here 


        // for suggesting the tags name while searching ---> start's from here 
        $(function() {
            $("#search").focus(function() {
                $.getJSON('fetch_tags.php', function(data) {
                    $("#search").autocomplete({
                        source: data
                    });
                });
            });
        });
        // for suggesting the tags name while searching ---> end's here 

        


//To change the name of the tag starts from here
function changeTags() {
    // Get all selected files
    var checkboxes = document.getElementsByClassName('file-checkbox');
    var ids = [];
    for (var i = 0; i < checkboxes.length; i++) {
        if (checkboxes[i].checked) {
            ids.push(checkboxes[i].getAttribute('data-id'));
        }
    }

    // Prompt for new tag
    var newTag = prompt("Enter new tag name:");
    if (newTag) {
        // Send AJAX request for each file
        for (var i = 0; i < ids.length; i++) {
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    document.getElementById("tag_" + ids[i]).textContent = newTag;
                }
            };
            xhttp.open("POST", "db-connect-reports.php", true);
            xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhttp.send("id=" + ids[i] + "&tag=" + newTag);
        }
    }
}
//To change the name of the tag end's here



 //for giving the multipe tags names to the uploaded images 
 (function(){
    // Initialize elements
    var tagInput = document.getElementById("tag-input");
    var tagWrapper = document.getElementById("tag-input-wrapper");

    // Add event listener to tag input
    tagInput.addEventListener('keydown', function(event) {
        // On Enter or Comma key press
        if (event.keyCode === 13 || event.keyCode === 188) {
            event.preventDefault();

            // Get tag from input
            var tag = this.value.trim();

            // Clear the input
            this.value = "";

            // Create new tag element
            var newTag = document.createElement('span');
            newTag.textContent = tag;

            // Insert new tag before the input
            tagWrapper.insertBefore(newTag, this);
        }
    });
    })();

    // image popup script code start's from here
    var modal = document.getElementById("myModal");
    var img = document.getElementsByClassName('myImg');
    var modalImg = document.getElementById("img01"); 
    var captionText = document.getElementById("caption");

    for (let i = 0; i < img.length; i++) {
        img[i].ondblclick = function(){
            modal.style.display = "block";
            modalImg.src = this.src;
            captionText.innerHTML = this.alt;
        }
    }

    var span = document.getElementsByClassName("close")[0];

    span.onclick = function() { 
        modal.style.display = "none";
    }
    //images popup script code end's here


</script>

</body>
</html>