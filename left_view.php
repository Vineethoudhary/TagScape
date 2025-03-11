<?php 
include 'db-connect-reports.php';
include 'create_folder.php';
// include 'upload_handler.php';
?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


<!DOCTYPE html>   <!-- this means we are using HTML5 version -->
<html lang="en">    <!-- tells the browser that the content of the webpages is primarily in English -->
<head>

    <meta charset="UTF-8">   <!-- this ensures that it support a wide range of characters from different languages and symbols -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BES Library</title>  <!-- It defines the title of the HTML documents, which appear in the browser's title bar or tab -->
    <link rel="stylesheet" type="text/css" href="css/left_view.css">   <!-- to define the relationship between the current HTML documents and external resources such as stylesheets, icons or pre-fetching data -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

    <!-- for searchng -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.13.0/jquery-ui.min.js"></script>
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.0/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <style>
    .file-contain {
        display: inline-block;
        position: relative;
        margin-top: 15px;
        margin-right: 10px;
        margin-bottom: 10px;
        margin-left: 5px;
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
        /* margin-top: 2vh; */
    }
            
    /* .image-contain:hover .myImg {
        transform: scale(1.1); 
    }
    */
          
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
                
    .checkbox-container {
        position: relative;
        display: inline-block;
    }
                
    .file-checkbox {
        position: absolute;
        top: 5px;
        left: 4px;
        cursor: pointer;  /*   CSS property changes the mouse cursor to a hand icon when hovering over an element, indicating it is clickable, typically used for links or interactive buttons.  */
        z-index: 1;
        /* opacity: 0; */
        cursor: pointer;
    }

    .tooltip {
        position: absolute;
        background-color: #fff; /* White background */
        color: #333; /* Dark text color */
        padding: 10px; /* More padding for a nicer look */
        border-radius: 8px; /* Rounded corners */
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); /* Subtle shadow */
        visibility: hidden;
        opacity: 0;
        transition: opacity 0.3s ease, visibility 0.3s ease; /* Smooth visibility */
        z-index: 2;
        white-space: nowrap; /* Prevent line breaks */
    }
                
    .file-contain {
        display: inline-block; /* Ensure the tooltip positions correctly */
    }
                
    .file-contain:hover .tooltip {
        visibility: visible;
        opacity: 1;
    }
    
    .tooltip {
        transition: opacity 0.3s ease, transform 0.3s ease; /* Smooth scaling */
    }
                
    .file-contain:hover .tooltip {
        transform: translate(-10%, -10px); /* Slight upward movement on hover */
    }
    </style>


</head>
<!-- Important for teh login -->
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

<body background="black">
    <div class="roll" style="padding-top: 30px; padding-left: 15px; width: 104.7%; background-color: #c6c8cc; padding-bottom: 25px">
    

    <?php
    $sql = "SELECT * FROM files";
    $result = mysqli_query($conn, $sql);
    if(mysqli_num_rows($result) > 0){
        while($fetch = mysqli_fetch_assoc($result)){
            $fileType = pathinfo($fetch['file'], PATHINFO_EXTENSION);
            $tag = $fetch['tags'];
            $id = $fetch['id'];
            $description = $fetch['description'];
            $filePath = 'upload/Architectural and Unique designs/' . $fetch['file'];

            if (!file_exists($filePath)) {
                continue; // Skip this iteration if file doesn't exist
            }

            if ($fileType == 'pdf') {
                echo "<embed src='$filePath' width='178.4' height='178.4' type='application/pdf'>";
            } else if ($fileType == 'mp4' || $fileType == 'mov' || $fileType == 'avi' || $fileType == 'flv' || $fileType == 'wmv' || $fileType == 'mts' ||
                $fileType == 'MP4' || $fileType == 'MOV' || $fileType == 'AVI' || $fileType == 'FLV' || $fileType == 'WMV' || $fileType == 'MTS') {
                echo "<video width='165' height='165' controls style='margin-right: 5px; margin-left: 2px; margin-bottom: 5px; margin-top:'>";
                echo "<source src='$filePath' type='video/$fileType'>";
                echo "Your browser does not support the video tag.";
                echo "</video>";
            } else {
                echo "<label class=\"checkbox-container\">";
                echo "<div style='display:inline-block; position:relative; margin-right: 6px; margin-bottom: 6px; margin-left: 2px;'>";
                echo "</div>";
                echo "</label>";

                echo <<<HTML
                <label class="checkbox-container">
                    <div class='file-contain' style="position: relative;">
                        <div class='image-container' style="border-radius: 5px; transition: filter 0.3s;">
                            <img src='$filePath' alt='$tag' class='myImg' data-tag='$tag' data-folder= 'Architectural and Unique designs' data-description='$description'>
                            <input type='checkbox' class='file-checkbox' data-id='$id'>
                            <div class="tooltip"><strong>Folder:</strong> Architectural and Unique designs<br><strong>Tag:</strong> $tag<br><strong>Description:</strong> $description</div>
                        </div>
                        <div class='tag-container'>
                            <span style="overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" class='tag'>$tag</span>
                        </div>
                    </div>
                </label>
                HTML;
            }
        }
    }
    ?>


<!-- For zooming the image by double click  Mrutyunjay-04-->
<div id="myModal" class="modal">
    <span class="close" onclick="closeModal()">&times;</span>
    
    <!-- Carousel Controls -->
    <a class="prev" onclick="changeSlide(-1)">&#10094;</a>
    <a class="next" onclick="changeSlide(1)">&#10095;</a>
    
    <div class="modal-contain">
        <img class="modal-content" id="img01">
        
        <!-- Tooltip with Folder and Tag Info -->
        <div id="imageDetails" class="modal-details">
            <!-- <strong>Folder:</strong> Architectural and Unique designs<br>
            <strong>Tag:</strong> $tag -->
        </div>
    </div>

    <div id="caption"></div>
    
    <!-- This will hold folder and tag info dynamically -->
    <!-- <div id="imageDetails" class="modal-details"></div>  -->
</div>
<style>
.modal-contain .tooltip {
    position: fixed;
    background-color: #fff;
    color: #333;
    padding: 10px;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    visibility: visible;
    opacity: 1;
    transition: opacity 0.3s ease;
    z-index: 2;
    /* white-space: nowrap; */
    top: 10vh; /* Adjust the top position */
    right: 5vw; /* Adjust the left position */
}

/* Show the tooltip on hover */
/* .modal-contain:hover .tooltip {
    visibility: visible;
    opacity: 1;
    transform: translate(-10%, -10px);
} */

/* Smooth transition for tooltip visibility */
/* .modal-contain .tooltip {
    transition: opacity 0.3s ease, transform 0.3s ease;
} */

/* .modal-contain:hover .tooltip {
    transform: translate(-10%, -10px);
} */

.modal-details {
    background-color: #fff;
    padding: 10px;
    font-size: 16px;
    color: #333;
    border-radius: 5px;
    box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
    width: 10vw;
    position: fixed; /* Change to fixed positioning */
    top: 15vh; /* Set a fixed position from the top of the viewport */
    right: 5vw; /* Set a fixed position from the right of the viewport */
}
.modal-content {
    margin: -20px auto;
    max-height: 85vh;
    max-width: 85vw;
    display: flex;
    justify-content: flex;
    align-items: center;
    position: relative;
    height: auto; 
    width: auto;
}

</style>

</div>


<script>
// Assuming 'images' is your collection of image elements with class 'myImg'(Zooming and slideshow)
var images = document.getElementsByClassName('myImg');
var modal = document.getElementById("myModal");
var modalImg = document.getElementById("img01");
var captionText = document.getElementById("caption");
var currentSlideIndex;

// Function to open modal with specific image
function openModal(index) {
    currentSlideIndex = index;
    modal.style.display = "block";
    modalImg.src = images[currentSlideIndex].src;
    captionText.innerHTML = images[currentSlideIndex].alt;

    // Get the tag and folder from the clicked image
    var tag = images[currentSlideIndex].getAttribute('data-tag');
    var folder = images[currentSlideIndex].getAttribute('data-folder');
    var description = images[currentSlideIndex].getAttribute('data-description');

    // Display tag and folder in the modal
    var detailsDiv = document.getElementById('imageDetails');
    detailsDiv.innerHTML = "<strong>Tag:</strong> " + tag + "<br><strong>Folder:</strong> " + folder + "<br><strong>Description:</strong> " + description;
}

// Function to close modal
function closeModal() {
    modal.style.display = "none";
}

// Function to change slide
// Function to change slide
function changeSlide(n) {
    currentSlideIndex += n;
    if (currentSlideIndex >= images.length) { currentSlideIndex = 0; }
    if (currentSlideIndex < 0) { currentSlideIndex = images.length - 1; }

    modalImg.src = images[currentSlideIndex].src;
    captionText.innerHTML = images[currentSlideIndex].alt;

    // Get the tag, folder, and description for the new image
    var tag = images[currentSlideIndex].getAttribute('data-tag');
    var folder = images[currentSlideIndex].getAttribute('data-folder');
    var description = images[currentSlideIndex].getAttribute('data-description');

    // Update the detailsDiv with new tag, folder, and description
    var detailsDiv = document.getElementById('imageDetails');
    detailsDiv.innerHTML = "<strong>Tag:</strong> " + tag + "<br><strong>Folder:</strong> " + folder + "<br><strong>Description:</strong> " + description;
}


// Assign openModal function to each image's double-click event
for (let i = 0; i < images.length; i++) {
    images[i].ondblclick = function() {
        openModal(i);
    };
}

// Close modal when clicking outside of the modal content
window.onclick = function(event) {
    if (event.target == modal) {
        closeModal();
    }
};

// Add event listener for keyboard input
window.addEventListener('keydown', function(event) {
    if (modal.style.display === "block") { // Check if modal is open
        if (event.key === 'ArrowLeft') { // Left arrow key
            changeSlide(-1);
        } else if (event.key === 'ArrowRight') { // Right arrow key
            changeSlide(1);
        } else if (event.key === 'Escape') { // Escape key
            closeModal(); // Close the modal if escape is pressed
        }
    }
});


//*****for drag and drop the images -->starts from here*****
// function handleDragOver(event) {
//     event.preventDefault();
//     event.stopPropagation();
// }
    
// function handleDragEnter(event) {
//     event.preventDefault();
//     event.stopPropagation();
//     var dropArea = document.getElementById("dropArea");
//     dropArea.classList.add("dragover");
// }
    
// function handleDragLeave(event) {
//     event.preventDefault();
//     event.stopPropagation();
//     var dropArea = document.getElementById("dropArea");
//     dropArea.classList.remove("dragover");
// }
    
// function handleDrop(event) {
//     event.preventDefault();
//     event.stopPropagation();
        
//     // Ensure the drop event isn't being handled multiple times
//     if (event.handled !== true) {
//         var fileInput = event.dataTransfer.files;
//         document.getElementById("dropArea").classList.remove("dragover");
//         if (fileInput.length > 0) {
//             window.alert(fileInput.length + " file(s) selected!");
//             showUploadForm(fileInput);
//         }
//         event.handled = true;
//     } else {
//         return false;
//     }
// }
    
//     // Attach drag and drop event listeners to the drop area
//     var dropArea = document.getElementById("dropArea");
    
//     // Check if the event listeners have already been attached
//     if (!dropArea.hasAttribute('data-listener-attached')) {
//         dropArea.addEventListener("dragenter", handleDragEnter, false);
//         dropArea.addEventListener("dragover", handleDragOver, false);
//         dropArea.addEventListener("dragleave", handleDragLeave, false);
//         dropArea.addEventListener("drop", handleDrop, false);
//         dropArea.setAttribute('data-listener-attached', 'true');
//     }
    // for drag and drop the images end's here



    

    

// function displayAndPrepareForUpload(file, tag) {
//     // Create a new div for the image and its tag
//     var div = document.createElement("div");

//     // Display a file preview - this assumes image files
//     var img = document.createElement("img");
//     img.src = URL.createObjectURL(file);
//     div.appendChild(img);

//     // Display the tag
//     var p = document.createElement("p");
//     p.textContent = tag;
//     div.appendChild(p);

//     // Add the div to the body of the document
//     document.body.appendChild(div);
// }

    

//Warning script while not giving the tags & label name while uploading
// function showPopup() {
//     var tags = document.getElementById("tags").value;
//     var label = document.getElementById("label").value;
        
//     if (tags === "" || label === "") {
//         var errorMessage = 'Please ensure the following before uploading:\n';

//         if(label === "")
//             errorMessage += 'Enter a label name.\n';
//         if(tags === "")
//             errorMessage += 'Enter a tag.\n';

//         Swal.fire({
//             icon: 'error',
//             title: 'Error',
//             text: errorMessage,
//         });
//         return false;
//     }
//     return true;
// }
// Waring code for tags and label ends here 


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

        


//To change the name of the tag starts from here
function showCustomPrompt() {
    document.getElementById('customPrompt').style.display = 'block';
}

function changeTags() {
    var checkboxes = document.getElementsByClassName('file-checkbox');
    var ids = [];
    for (var i = 0; i < checkboxes.length; i++) {
        if (checkboxes[i].checked) {
            ids.push(checkboxes[i].getAttribute('data-id'));
        }
    }

    // Perform an AJAX call to fetch_tags.php to get unique tags
    var xhttpFetchTags = new XMLHttpRequest();
    xhttpFetchTags.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            // Parse the JSON response
            var uniqueTags = JSON.parse(this.responseText);

            // Populate the datalist with unique tags for suggestions
            var dataList = document.getElementById('tagSuggestions');
            dataList.innerHTML = ""; // Clear existing options
            uniqueTags.forEach(function(tag) {
                var option = document.createElement('option');
                option.value = tag;
                dataList.appendChild(option);
            });

            // Show the custom prompt
            document.getElementById('customPrompt').style.display = 'block';
        }
    };
    xhttpFetchTags.open("GET", "fetch_tags.php", true);
    xhttpFetchTags.send();
}
//To change the name of the tag end's here


// Function to submit the tag change
function submitTagChange() {
    var newTag = document.getElementById('newTagName').value;

    // Send AJAX request for each file to update the tags
    var checkboxes = document.getElementsByClassName('file-checkbox');
    var ids = [];

    for (var i = 0; i < checkboxes.length; i++) {
        if (checkboxes[i].checked) {
            ids.push(checkboxes[i].getAttribute('data-id'));
        }
    }

    if (ids.length === 0) {
        closeCustomPrompt(); // Close the custom prompt if no files are selected
        return; // Exit the function if no files are selected
    }

    var completedRequests = 0; // Track completed requests

    ids.forEach(function(id) {
        var xhttpUpdateTag = new XMLHttpRequest();
        xhttpUpdateTag.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                var response = JSON.parse(this.responseText);
                if (response.status === "success") {
                    completedRequests++;
                    if (completedRequests === ids.length) {
                        location.reload(); // Reload the page after all requests are completed
                        closeCustomPrompt(); // Close the custom prompt
                    }
                } else {
                    console.error("Error updating tag for file ID: " + id);
                }
            }
        };
        xhttpUpdateTag.open("POST", "db-connect-reports.php", true);
        xhttpUpdateTag.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhttpUpdateTag.send("id=" + id + "&tag=" + encodeURIComponent(newTag));
    });
}

// Function to close the custom prompt
function closeCustomPrompt() {
    document.getElementById('customPrompt').style.display = 'none';
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
});

   
   
   
 // image popup on double click script code start's from here
// var modal = document.getElementById("myModal");
// var img = document.getElementsByClassName('myImg');
// var modalImg = document.getElementById("img01"); 
// var captionText = document.getElementById("caption");

// for (let i = 0; i < img.length; i++) {
//     img[i].ondblclick = function(){
//         modal.style.display = "block";
//         modalImg.src = this.src;
//         captionText.innerHTML = this.alt;
//     }
// }

// var span = document.getElementsByClassName("close")[0];

// span.onclick = function() { 
//     modal.style.display = "none";
// }
//images popup on double click script code end's here


    
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




//for searching in the scattered way --> Starts from  here
function filterImages() {
    const searchInput = document.getElementById('search').value.toLowerCase();
    const images = document.querySelectorAll('#imageContainer img');

    for (let i = 0; i < images.length; i++) {
        const altText = images[i].getAttribute('alt').toLowerCase();
        const tags = altText.split(" ");
        let found = false;

        for (let j = 0; j < tags.length; j++) {
            if (tags[j].startsWith(searchInput)) {
                found = true;
                break;
            }
        }

        if (found) {
            images[i].style.display = 'block';
        } else {
            images[i].style.display = 'none';
        }
    }
}
 
// for searching in the scattered way --> End's here 


 //for deleting the multiple images from left_view  ---> start's from here
 function deleteSelectedFiles() {
    var checkboxes = document.getElementsByClassName('file-checkbox');
    var selectedFiles = [];

    // Iterate through all checkboxes and find the selected ones
    for (var i = 0; i < checkboxes.length; i++) {
        if (checkboxes[i].checked) {
            var fileId = checkboxes[i].getAttribute('data-id');
            selectedFiles.push(fileId);
        }
    }

    if (selectedFiles.length === 0) {
        alert("Please select at least one file to delete.");
        return;
    }

    var r = confirm("Are you sure you want to delete the selected files?");
    if (r == true) { // If the user clicked "OK"
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                // Remove the selected files from the DOM
                for (var i = 0; i < selectedFiles.length; i++) {
                    var element = document.getElementById(selectedFiles[i]);
                    if (element) {
                        element.parentNode.removeChild(element);
                    } else {
                        console.error('Element to remove not found');
                    }
                }
            }
        };
        xhttp.open("POST", "user_right_view.php", true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhttp.send("files=" + encodeURIComponent(selectedFiles.join(',')));
    } // If the user clicked "Cancel", nothing happens
    window.location.reload(); // To reload the page 
}
    //for deleting the multiple images from left_view  ---> end's here


</script>

</body>
</html>