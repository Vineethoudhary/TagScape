<?php 
include 'db-connect-reports.php';
include 'create_folder.php';
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
    <style>
        .myImg {
            cursor: pointer;
            transition: 0.3s;
        }
        
        .myImg:hover {opacity: 0.7;}
        
        /* The Modal (background) */
        .modal {
            display: none;
            position: fixed;
            padding-top: 10vh; /* 100px */
            z-index: 1050;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            overflow: auto;
            background-color: rgb(0,0,0);
            background-color: rgba(0,0,0,0.9);
        }
        
        /* Modal Content (Image) */
        .modal-content {
            margin: auto;
            display: block;
            /* width: 80vw; */
            max-width: 50vw;
        }
        
        /* Caption of Modal Image (Image Text) - Same Width as the Image */
        #caption {
            margin: auto;
            display: block;
            width: 80vw;
            max-width: 70vw; /* 700px */
            text-align: center;
            color: #ccc;
            padding: 1vh 0; /* 10px 0 */
            height: 15vh; /* 150px */
        }
        
        /* Add Animation - Zoom in the Modal */
        .modal-content, #caption { 
            animation-name: zoom;
            animation-duration: 0.6s;
        }
        
        @keyframes zoom {
            from {transform:scale(0)} 
            to {transform:scale(1)}
        }
        
        /* The Close Button */
        .close {
            position: absolute;
            top: 8.5vh; /* 50px -> 5vh */
            right: 23vw; /* 307px -> 30.7vw */
            font-weight: bold;
            transition: 0.3s;
        }
        
        .close:hover,
        .close:focus {
            text-decoration: none;
            cursor: pointer;
        }
        
        
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
            /* opacity: 0; */
            cursor: pointer;
        }

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
            bottom: 1px;
            left: 1px;
            cursor: pointer;
            z-index: 1;
            /* opacity: 0; */
            cursor: pointer;    
        }    
        
        /* .video-container {
            display: flex;
            flex-direction: column;
            position: relative;
            margin: 0; 
            transition: padding 0.3s ease, border-radius 0.3s ease;
            border-radius: 30px;
        }
        
        .video-container:hover {
            border: 3px solid white;
            border-radius: 5px;
        } */
        
        .video {
            width: 12.7vw;
            height: 24.5vh;
            background-color: #2C2C2C;
            margin: 0; /* Set margin to zero */
        }
        
        /* Add a pointer when hovering over the next and previous buttons */
        .prev, .next {
            cursor: pointer;
            position: absolute;
            top: 50%;
            width: auto;
            padding: 16px;
            margin-top: -50px;
            color: white;
            font-weight: bold;
            font-size: 20px;
            transition: 0.6s ease;
            border-radius: 0 3px 3px 0;
            user-select: none;
        }
        
        
        /* Position the "next button" to the right */
        .next {
            right: 0;
            border-radius: 3px 0 0 3px;
        }

</style>
</head>

<body>
    <!-- <div class="row">  -->
        <div class="roll" style=" margin-bottom: 2vh; width: 105%; padding-top: 5vh; background-color: #c6c8cc;">
        
        
        <div class="folder">
            <?php
            $target_dir = "uploads/";
            if(isset($_FILES["fileToUpload"])) {
                $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
                
                if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
                    echo "The file ". htmlspecialchars( basename( $_FILES["fileToUpload"]["name"])). " has been uploaded.";
                } else {
                    echo "Sorry, there was an error uploading your file.";
                }
            } 
            ?>
        </div><!-- ***class folder*** -->
        
        
        <!-- To fetch the images form the sub-folders on the frontend (middle part) starts from here -->
        <?php
        if (isset($_GET['folder'])) {
            $selectedFolder = $_GET['folder'];
            $folderPath = './upload/' . $selectedFolder;
            // Fetch only image files with case-insensitive extensions
            $files = glob($folderPath . '/*.{jpg,JPG,jpeg,JPEG,png,PNG,gif,bmp,BMP,GIF,pdf,mp4,mov,avi,flv,wmv,MP$,MOV,AVI,FLV,WMV}', GLOB_BRACE);
            
            echo "<ul style='list-style: none; display: flex; flex-wrap: wrap;'>";
            foreach ($files as $file) {
                $fileName = basename($file);
                // Decode the file name for database comparison
                $decodedFileName = urldecode($fileName);
                $fileType = strtolower(pathinfo($fileName, PATHINFO_EXTENSION)); // Convert extension to lowercase
                
                // Use a prepared statement to get the file's record from the database
                $stmt = $conn->prepare("SELECT * FROM files WHERE file = ? ORDER BY id ASC");
                $stmt->bind_param("s", $decodedFileName); // Bind the decoded file name as a parameter
                $stmt->execute();
                $result = $stmt->get_result();
                
                // Only proceed if a matching record is found in the database
                if($result->num_rows > 0) {
                    $fetch = $result->fetch_assoc();
                    $tag = $fetch['tags'];
                    $id = $fetch['id'];
                    
                    // Encode the file name for use in HTML and JavaScript
                    $escapedFileName = htmlspecialchars(json_encode($fileName), ENT_QUOTES, 'UTF-8');
                    
                    if($fileType == 'pdf') {
                        echo <<<HTML
                        <label class="checkbox-container">
                            <div class='file-contain' style="position: relative;">
                                <div class='image-container' style="border-radius: 5px; transition: filter 0.3s;">
                                <!-- Embed PDF instead of image -->
                                <embed src='upload/$selectedFolder/$fileName' type='application/pdf' alt='Uploaded PDF' class='myImg pdf-embed' style="height: 24.5vh;">
                                <input type='checkbox' class='file-checkbox' data-id='$id'>
                            </div>
                            
                            <div class='tag-container'> 
                                <!-- <button class='delete-button' onclick='deleteImage($escapedFileName)'><i class='fa fa-trash'></i></button> -->
                                <span overflow: hidden; text-overflow: ellipsis; white-space: nowrap; class='tag'>$tag</span>
                            </div>
                        </div>
                        </label>                   
                        HTML;
                    }else if (in_array($fileType, ['mp4', 'mov', 'avi', 'flv', 'wmv', 'MP4', 'MOV', 'AVI', 'FLV', 'WMV'])) {
                        echo <<<HTML
                        <div class="file-contain">
                            <div class='video-container'>
                                <video class='video' controls>
                                    <source src='$file' alt='$fileName' type='video/$fileType'>
                                    Your browser does not support the video tag.
                                </video>
                                <input type='checkbox' class='file-checkbox' data-id='$id'>
                                <div class='tag-container'>
                                    <button class='delete-button' onclick='deleteImage($escapedFileName)'><i class='fa fa-trash'></i></button>
                                    <span class='tag'>$tag</span>
                                </div>
                            </div>
                        </div>
                        HTML;
                    }else {
                        echo <<<HTML
                        <label class="checkbox-container">
                            <div class='file-contain' style="position: relative;">
                                <div class='image-container'  style="border-radius: 5px; transition: filter 0.3s;">
                                    <img src='$file' alt='$fileName' class='myImg'>
                                    <input type='checkbox' class='file-checkbox' data-id='$id'>
                                </div>
                                
                                <div class='tag-container'> 
                                    <!-- <button class='delete-button' onclick='deleteImage($escapedFileName)'><i class='fa fa-trash'></i></button> -->
                                    <span overflow: hidden; text-overflow: ellipsis; white-space: nowrap; class='tag'>$tag</span>
                                </div>
                            </div>
                        </label>
                        HTML;
                    }//else statement
                }//if statement
            }//forech
            echo "</ul>";
        }
        ?>
        </div><!-- div class=roll  -->
        
        
        <div id="myModal" class="modal">
            <span class="close" onclick="closeModal()">&times;</span>
            
            <!-- Carousel Controls(slideshow) -->
            <a class="prev" onclick="changeSlide(-1)">&#10094;</a>
            <a class="next" onclick="changeSlide(1)">&#10095;</a>
            
            <img class="modal-content" id="img01">
            <div id="caption"></div>
        </div>
        
        <style>
        .modal-content {
            margin: -20px auto;
            height: 85vh; 
            width: auto;
            }
        </style>




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
    }
    
    // Function to close modal
    function closeModal() {
        modal.style.display = "none";
    }
    
    
    // Function to change slide
    function changeSlide(n) {
        currentSlideIndex += n;
        if (currentSlideIndex >= images.length) { currentSlideIndex = 0; }
        if (currentSlideIndex < 0) { currentSlideIndex = images.length - 1; }
        modalImg.src = images[currentSlideIndex].src;
        captionText.innerHTML = images[currentSlideIndex].alt;
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

    document.addEventListener('DOMContentLoaded', function() {
    // Get all embed elements for PDFs
    var pdfEmbeds = document.getElementsByClassName('pdf-embed');

    // Function to open PDF in new window/tab
    function openPdf(index) {
        var pdfSrc = pdfEmbeds[index].src; // Use .src directly if you are sure about the attribute
        window.open(pdfSrc, '_blank'); // Open PDF in new window/tab
    }

    // Assign openPdf function to each embed element's double-click event
    for (let i = 0; i < pdfEmbeds.length; i++) {
        pdfEmbeds[i].ondblclick = function() {
            openPdf(i);
        };
    }
});

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
        
        
        // to download the multiple images at once 
        document.getElementById('download-button').addEventListener('click', function() {
            var checkboxes = document.getElementsByClassName('file-checkbox');
            var images = [];
            for(var i = 0; i < checkboxes.length; i++) {
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
        
        
        
        
        //To change the name of the tag starts from here
        function showCustomPrompt() {
            document.getElementById('customPrompt').style.display = 'block';
        }
        
        
        function changeTags() {
            var checkboxes = document.getElementsByClassName('file-checkbox');
            var ids = [];
            for(var i = 0; i < checkboxes.length; i++) {
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
        })();
        
        
        //for deleting the single images from right_view  ---> start's from here
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
        //for deleting the single images from right_view  ---> end's here
</script>

</body>
</html>
