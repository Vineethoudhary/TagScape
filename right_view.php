<?php 
include 'db-connect-reports.php';
include 'create_folder.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>image & pfd's upload</title>
    <link rel="stylesheet" type="text/css" href="css/right_view.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <style>
        .file-contain {
            display: flex;
            flex-wrap: wrap;
        }
        
        .video-container {
            display: flex;
            flex-direction: column;
            position: relative;
            margin: 0; /* Set margin to zero */
            transition: padding 0.3s ease, border-radius 0.3s ease;
            border-radius: 30px;
        }
        
        .video-container:hover {
            border: 3px solid white;
            border-radius: 5px;
        }
        
        .video {
            width: 12.7vw;
            height: 25vh;
            background-color: #2C2C2C;
            margin: 0; /* Set margin to zero */
        }
        
        .tag-container {
            text-align: center;
            margin-top: -5px;
        }
        
        .tag {
            color: #bdbdc1;
            font-size: 16px;
            font-weight: 600;
            padding: 2px;
            background-color: #2C2C2C;
            display: block;
            width: 100%;
            box-sizing: border-box;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        
        
        
        /* CSS For Images */

        .file-contain {
                    display: inline-block;
                    position: relative;
                    margin-right: 10px;
                    margin-bottom: 10px;
                    margin-left: 2px;
                    transition: padding 0.3s ease, border-radius 0.3s ease; 
                    border-radius: 30px; 
                }
            
                .file-contain:hover {
                    border: 3px solid white; 
                    /* background-color: white;  */
                    border-radius: 5px; 
                }
            
                .image-contain {
                    position: relative;
                }
            
                .myImg {
                    width: 12.7vw;
                    height: 25vh;
                    padding: 2px;
                    background-color: #2C2C2C;
                    /* margin-top: 5vh; */
                }
            
                .tag-container {
                    text-align: center;
                }
            
                .tag {
                    color: #bdbdc1;
                    font-size: 16px;
                    font-weight: 600;
                    padding: 2px;
                    background-color: #2C2C2C;
                    display: block;
                    width: 12.8vw;
                    box-sizing: border-box;
                    overflow: hidden;    
                    text-overflow: ellipsis; 
                    white-space: nowrap;    
                }

                 /* Delete button css */
                 
                 .delete-button {
                     position: absolute;
                     buttom: 0;
                     right: 0;
                     cursor: pointer;
                     display: none;
                     background-color: #2c2c2c;
                     color: white;
                     border: none;
             
                 }
             
                 .file-contain:hover .delete-button {
                     display: block;
                     border: none;
                 }
             
                 .delete-button:hover {
                     color: red;
                     background-color: #2c2c2c;
                     border: none;
                 }
             
                 /* Check box css */
                 .checkbox-container {
                     position: relative;
                     display: inline-block;
                 }
             
                 .file-checkbox {
                    position: absolute;
                    bottom: 0;
                    left: 0;
                    cursor: pointer;
                    z-index: 1;
                    /* opacity: 0; */
                    cursor: pointer;    
                 }    
             
               /* .image-container input.file-checkbox {
                 position: absolute;
                 top: 50%; 
                 left: 0.3vw;
                 transform: translateY(-50%); 
                 opacity: 0;
                 cursor: pointer;
             }
              */
             /* .image-container input.file-checkbox:checked + img {
                 filter: grayscale(10%) brightness(100%) sepia(90%) hue-rotate(200deg);
             } */
    
</style>
</head>

<?php 
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    
    if(!isset($_SESSION['login_id']))
        header('location:login.php');
    include 'db_connect.php';
    ob_start();
    if(!isset($_SESSION['system'])){
        $system = $conn->query("SELECT * FROM system_settings")->fetch_array();
        foreach($system as $k => $v){
          $_SESSION['system'][$k] = $v;
        }
    }
    ob_end_flush();

    include 'header.php' 
?>
<body background-color: #404040;>


<!-- *****FILES FETCHING BY SEARCHING IN LEFTSIDE START'S FROM HERE**** -->
<!-- <div class="scroll1" style="margin-top: -2vh; margin-left: -2.1vw; margin-bottom: -11vh; width: 89%; background-color: #404040;"> -->
<div class="scroll1" style="width: 104.7%; background-color: #404040; padding-top: -4vh; padding-left: 1vw;">
    
    <!-- This is for the parameters used on the top -->
    <div class="parent-container" style="display: flex; align-items: center; justify-content: space-between;"> 
    <!-- <button id="download-button" style="margin-left: 1vw; margin-top: 1vh;">Download</button> -->
    <!-- <button onclick='changeTags()' style="color: #a4a4ae; background-color: #404040; font-weight: 550; border: none; padding: 2px 25px; cursor: pointer; transition: background-color 0.3s, box-shadow 0.3s; margin-top: 1vh; margin-left: 0vw; margin-right: 4vw; border-radius: 8px; height: 46px;"
    onmouseover="this.style.backgroundColor='#2C2C2C'; this.style.boxShadow='0 0 10px rgba(255, 255, 255, 0.5) inset'"
    onmouseout="this.style.backgroundColor='#404040'; this.style.boxShadow='none'">
    Change Tags
</button> -->
<!-- <button id="download-button"
    style="background-color: #404040; color: #a4a4ae; font-weight: 550; border: none; padding: 2px 25px; cursor: pointer; transition: background-color 0.15s, box-shadow 0.15s, color 0.15s, transform 0.15s, font-weight 0.15s; margin-top: 1vh; margin-left: -5.5vw; margin-right:25.5vw; border-radius: 8px; height: 45px;"
    onmouseover="this.style.backgroundColor='#2db875'; this.style.color='#FFFFFF'; this.style.boxShadow='0 0 10px rgba(255, 255, 255, 0.5) inset'; this.style.transform='scale(1.05)'; this.style.fontWeight='650';"
    onmouseout="this.style.backgroundColor='#404040'; this.style.color='#a4a4ae'; this.style.boxShadow='none'; this.style.transform='scale(1)'; this.style.fontWeight='550';">
    Download
</button>  -->



    <!-- // Display tags and label name before displaying files -->
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

        echo "<div style='margin-top: 1vh; margin-left: -16vw;'>";
        // echo "<span style='margin-right: 1vw; background: #f0ffff; border-radius: 5px; width: 20rem; height:4vh; display: inline-block; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;'><b>Tags:</b> $tags</span>";
        // echo "<span style='margin-right: 2vw; background: #f0ffff; border-radius: 5px; width: 14rem; height:4vh; display: inline-block;'><b>Label:</b> $label</span>";
        echo "</div>";
    }
}
// echo '<div class="search-print-container" style="display: inline-block; vertical-align: top; margin-top:-5vh;">
// <form id="searchForm" method="get" action="./index.php" style="display: flex; justify-content: center; margin-top: -10vh;margin-left: -5vw; margin-bottom: -1vh;">
//     <input type="hidden" name="page" value="right_view">
//     <input type="text" id="search" name="search" class="search-input" autocomplete="off" placeholder="Search by tag">
//     <input type="submit" class="search" value="Search" style="margin-right: 1vw;">
// </form>
// <div class="suggestion-list"></div> 
// </div>';
?>
</div>
 <!-- This is for the parameters used on the top end's here-->
 

<?php
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

if (!empty($searchTerm)) {
    // Split the search term into individual words
    $searchWords = explode(' ', $searchTerm);

    // Create an array to store the conditions for each word
    $conditions = [];

    // Build the conditions for each word
    foreach ($searchWords as $word) {
        $conditions[] = "tags LIKE ?";
    }

    // Combine the conditions using AND
    $conditionString = implode(' AND ', $conditions);

    // Create the prepared statement
    $stmt = $conn->prepare("SELECT * FROM files WHERE $conditionString");

    // Add wildcard to each word in the search term
    foreach ($searchWords as &$word) {
        $word = "%$word%";
    }

    // Bind parameters dynamically
    $stmt->bind_param(str_repeat('s', count($searchWords)), ...$searchWords);

    // Execute the statement
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Fetch and process all matching rows
        while ($fetch = $result->fetch_assoc()) {
            $id = $fetch['id'];
            $tags = $fetch['tags'];
            $fileName = $fetch['file'];
            $fileType = pathinfo($fileName, PATHINFO_EXTENSION);
            $subfolder = explode('/', $fetch['path'])[2];

            $encodedFileName = urlencode($fileName); // Encode the file name

            echo "<div id='$fileName' style='position: relative; display: inline-block;'>";

            if ($fileType == 'pdf') {
                echo "<embed src='upload/$subfolder/$encodedFileName' width='165' height='165' type='application/pdf' style='margin-right: 8px; margin-bottom: 8px;' alt='Uploaded Image' data-tags='$tags'>";
            } else if (in_array($fileType, ['mp4', 'mov', 'avi', 'flv', 'wmv', 'MP4', 'MOV', 'AVI', 'FLV', 'WMV'])) {
                echo <<<HTML
                <div class="file-contain">
                    <div class='video-container'>
                        <video class='video' controls>
                            <source src='upload/$subfolder/$encodedFileName' type='video/$fileType'>
                            Your browser does not support the video tag.
                        </video>
                        <div class='tag-container'>
                            <button class='delete-button' onclick='deleteImage("$encodedFileName")'><i class='fa fa-trash'></i></button>
                            <span class='tag'>$tags</span>
                        </div>
                    </div>
                </div>
                HTML;
            } else {
                echo <<<HTML
                <label class="checkbox-container" style="margin-top: 4vh;">
                    <div class='file-contain' style="position: relative;">
                        <div class='image-container'>
                            <img src='./upload/$subfolder/$encodedFileName' class='myImg' alt='Uploaded Image' data-tags='$tags'>
                            <input type='checkbox' class='file-checkbox' data-id='$id'>
                        </div>
                        <div class='tag-container'>
                            <button class='delete-button' onclick='deleteImage("$encodedFileName")'><i class='fa fa-trash'></i></button>
                            <span style="overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" class='tag'>$tags</span>
                        </div>
                    </div>
                </label>
                <style>
                 .checkbox-container {
                        position: relative;
                        display: inline-block;
                                 }  

                          .file-checkbox {
                           position: absolute;
                           bottom: 11px;
                           left: 4px;
                           cursor: pointer;
                           z-index: 1;
                           background-color: #404040;
                         }

   
                </style>
                HTML;
            }
            echo "</div>";
        }
    } else {
        echo <<<HTML
            <style>
                /* Style for the modal overlay */
                .modal-overlay {
                    display: none;
                    position: fixed;
                    top: 0;
                    left: 0;
                    width: 100%;
                    height: 100%;
                    background: rgba(0, 0, 0, 0.5);
                    justify-content: center;
                    align-items: center;
                    transition: opacity 0.3s ease; /* Set a faster transition duration */
                }
    
                .modal-content {
                    background: #2a5c75;
                    padding: 20px;
                    border-radius: 5px;
                    box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
                    text-align: center;
                    max-width: 300px;
                    max-height: 100px;
                    margin: 0 auto;
                    opacity: 0;
                    transition: opacity 0.3s ease;
                }
                .text {

                    color: #efe8ee;
                    font-weight: 550;
                    margin: 0 auto;
                    padding-top: 16px;
                    
                }
            </style>
    
            <script>
                document.addEventListener("DOMContentLoaded", function () {
                    var modalOverlay = document.querySelector('.modal-overlay');
                    var modalContent = document.querySelector('.modal-content');
    
                    modalOverlay.style.display = 'flex';
                    modalContent.style.opacity = '1'; /* Set opacity to 1 to trigger the fade-in transition */
    
                    modalOverlay.addEventListener('click', function (event) {
                        if (event.target === modalOverlay) {
                            modalOverlay.style.display = 'none';
                        }
                    });
                });
            </script>
    
            <!-- Modal HTML content -->
            <div class="modal-overlay">
                <div class="modal-content">
                    <p class="text">Oops!! No Match Found .😟</p>
                   
                </div>
            </div>
        HTML;
    }
    echo "</div>";
    
    
}
?>


<!-- For zooming the image by double click ===> start's from here -->
    <div id="myModal" class="modal">
    <!-- The Close Button -->
    <span class="close">&times;</span>
    <!-- Modal Content (The Image) -->
    <img class="modal-content" id="img01">
    <!-- Modal Caption (Image Text) -->
    <div id="caption"></div>
<style>
.modal-content {
    margin: 0 auto;
    width: 80%; /* Set your desired width */
    height: 70vh; /* Set your desired height */
    /* object-fit: contain; */
}
</style>

    </div>

    <!-- For zooming the image by double click ===> end's here -->

</div>
</div> <!-- div class row is ending here -->









<script>

    //for displaying the tag name on the images ---> starts from here
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
    //For displaying the tag name on the images ---> end's here


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




    //for deleting the single images from right_view  ---> start's from here
    function deleteImage(fileName) {
    var r = confirm("Are you sure you want to delete this file?");
    if (r == true) { // If the user clicked "OK"
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                // Remove the image and button from the DOM
                var element = document.getElementById(fileName);
                if (element) {
                    element.parentNode.removeChild(element);
                } else {
                    console.error('Element to remove not found');
                }
            }
        };
        xhttp.open("POST", "right_view.php", true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhttp.send("file=" + encodeURIComponent(fileName));
    } // If the user clicked "Cancel", nothing happens
}
    //for deleting the single images from right_view  ---> end's here
    
    
    // for suggesting the tags name while searching ---> start's from here 
    $(function() {
            $("#search").autocomplete({
                source: function(request, response) {
                    $.getJSON('fetch_tags.php', function(data) {
                        const searchTerm = request.term.toLowerCase();
                        const suggestions = data.filter(function(tag) {
                            const tagWords = tag.toLowerCase().split(" ");
                            return searchTerm.split(" ").every(function(searchWord) {
                                return tagWords.some(function(tagWord) {
                                    return tagWord.includes(searchWord);
                                });
                            });
                        });
                        response(suggestions);
                    });
                },
                minLength: 2,
                open: function() {
                     $(this).autocomplete("widget").addClass("suggestion-list"); // Add a class to the autocomplete widget for styling
                }
            });
        });
    // for suggesting the tags name while searching ---> end's here 

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
};

// Close the modal when clicking outside the image
window.onclick = function(event) {
    if (event.target == modal) {
        modal.style.display = "none";
    }
};

    //images popup script code end's here



// to download the multiple images at once 
document.getElementById('download-button').addEventListener('click', function() {
    var checkboxes = document.getElementsByClassName('file-checkbox');
    var images = [];
    for (var i = 0; i < checkboxes.length; i++) {
        if (checkboxes[i].checked) {
            var img = checkboxes[i].previousElementSibling;
            images.push(img.src);
        }
    }
    downloadImages(images);
});

function downloadImages(images) {
    images.forEach((image) => {
        var a = document.createElement('a');
        a.href = image;
        a.download = image.split('/').pop();
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
    });
}
// to download the multiple images at once  END'S HERE

//*****for drag and drop the images -->starts from here*****
    function handleDragOver(event) {
        event.preventDefault();
        event.stopPropagation();
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
        
        // Ensure the drop event isn't being handled multiple times
        if (event.handled !== true) {
            var fileInput = event.dataTransfer.files;
            document.getElementById("dropArea").classList.remove("dragover");
            if (fileInput.length > 0) {
                window.alert(fileInput.length + " file(s) selected!");
                showUploadForm(fileInput);
            }
            event.handled = true;
        } else {
            return false;
        }
    }
    
    // Attach drag and drop event listeners to the drop area
    var dropArea = document.getElementById("dropArea");
    
    // Check if the event listeners have already been attached
    if (!dropArea.hasAttribute('data-listener-attached')) {
        dropArea.addEventListener("dragenter", handleDragEnter, false);
        dropArea.addEventListener("dragover", handleDragOver, false);
        dropArea.addEventListener("dragleave", handleDragLeave, false);
        dropArea.addEventListener("drop", handleDrop, false);
        dropArea.setAttribute('data-listener-attached', 'true');
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
}
    function showUploadForm(selectedFiles) {
    var popupContent = document.createElement("div");

     // Counter to track the number of loaded images
     var loadedImages = 0;

    // Add the selected files to the popup content
    function addImageToPopup(file) {
        var fileReader = new FileReader();
        fileReader.onload = function (event) {
            var imageElement = document.createElement("img");
            imageElement.src = event.target.result;
            imageElement.style.maxWidth = "20vw";
            imageElement.style.maxHeight = "20vh";
            imageElement.style.marginLeft = "1vw";
            imageElement.style.marginBottom = "1vh";

            popupContent.appendChild(imageElement);

            // Increment the counter
            loadedImages++;

            // Check if all images are loaded
            if (loadedImages === selectedFiles.length) {
                // Add the form elements after all images are loaded
                addFormElements();
            }
        };
        fileReader.readAsDataURL(file);
    }
   
    // Function to add the form elements to the popup content
    function addFormElements() {
    // Create form for folder, label, tags, and subfolder
    var uploadForm = document.createElement("form");
    uploadForm.action = "upload_handler.php"; // Set your form action
    uploadForm.method = "post"; // Set your form method
    uploadForm.style.display = "flex"; // Use flexbox to make the form elements align horizontally


    // Create inputs and elements for the form
    var labelSelect = document.createElement("label");
    // labelSelect.textContent = "Select Label:";
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

    // uploadForm.appendChild(labelDropdown);
    // uploadForm.appendChild(document.createElement("br"));
    // uploadForm.appendChild(document.createElement("br"));

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
        var labelValue = labelDropdown.value;
        var subfolderValue = subfolderDropdown.value;
        var tagsValue = tagsInput.value;

       // Perform AJAX request to handle file upload
    var formData = new FormData();
    for (var i = 0; i < selectedFiles.length; i++) {
        formData.append("files[]", selectedFiles[i]);
    }
    formData.append("label", labelValue);
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
                // Optionally, you can close the popup here
                popup.close();
            } else {
                console.error("File upload failed.");
            }
        }
    };

    xhr.send(formData);

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


    //for searching in the scattered way --> Starts from  here
function filterImages() {
    const searchInput = document.getElementById('search').value.toLowerCase();
    const images = document.querySelectorAll('#imageContainer img');

    for (let i = 0; i < images.length; i++) {
        const altText = images[i].getAttribute('alt').toLowerCase();
        if (altText.includes(searchInput)) {
            images[i].style.display = 'block';
        } else {
            images[i].style.display = 'none';
        }
    }
}
 
// for searching in the scattered way --> End's here 




</script>

</body>
</html>