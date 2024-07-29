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
    <link rel="stylesheet" type="text/css" href="user_left_view.css">
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
            z-index: 1050;
            padding-top: 10vh; /* 100px */
            left: 0;
            top: 0;
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
            /* color: #bbb; */
            text-decoration: none;
            cursor: pointer;
        }
        
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
            width: 12.2vw;
            height: 24vh;
            padding: 2px;
            background-color: #2C2C2C;
            margin-top: 0.5vh;
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
            width: 12.25vw;
            box-sizing: border-box;
            overflow: hidden;    
            text-overflow: ellipsis; 
            white-space: nowrap;    
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
        /* On hover, add a black background color with a little bit see-through */
    </style>
</head>



<body>

    <!-- <div class="roll" style="margin-top: -2.5vh; margin-left: -1.2vw; margin-bottom: -11vh; margin-right: 5vw;  background-color: #404040;"> -->
    <div class="roll" style="padding-top: 30px; padding-left: 15px; padding-bottom: 25px; margin-right: 2vw; margin-left: -2vw; background-color: #404040;">
    
    <?php
    $sql = "SELECT * FROM files";
    $result = mysqli_query($conn , $sql);
    if(mysqli_num_rows($result)>0){
        while($fetch = mysqli_fetch_assoc($result)){
        $fileType = pathinfo($fetch['file'], PATHINFO_EXTENSION);
        $tag = $fetch['tags']; // fetch the tag from the current row
        $id = $fetch['id'];
        $label = $fetch['label'];
        $filePath = 'upload/Facade Application wise/' . $fetch['file'];

        
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
            echo "<label class=\"checkbox-container\">";
            echo "<div style='display:inline-block; position:relative; margin-right: 6px; margin-bottom: 6px; margin-left: 2px;'>";
            echo "</div>";
            echo "</label>";

            echo <<<HTML
            <label class="checkbox-container">
                <input type='checkbox' class='file-checkbox' data-id='$id'>
                <div class='file-contain' style="position: relative;">
                <div class='image-container' style="border-radius: 5px; transition: filter 0.3s;">
                <img src='$filePath' class='myImg' data-tag='$tag'>
                </div>
            <div class='tag-container'>
            <span style="overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" class='tag'>$tag</span>
                    </div>
                </div>
            </label>
            
            <style>
            .checkbox-container {
                margin-top: 1vh;
                position: relative;
                display: inline-block;
            }
            
            .file-checkbox {
                position: absolute;
                bottom: 11px;
                left: 5px;
                cursor: pointer;
                z-index: 1;
                background-color: #404040;
            }
            
          
            </style>
            HTML;
        }
        }//while clause
    }//if clause
  ?>

   <!-- For zooming the image by double click ===> start's from here -->
   <div id="myModal" class="modal">
    <span class="close" onclick="closeModal()">&times;</span>
    
    <!-- Carousel Controls -->
    <a class="prev" onclick="changeSlide(-1)">&#10094;</a>
    <a class="next" onclick="changeSlide(1)">&#10095;</a>

    <img class="modal-content" id="img01">
    <div id="caption"></div>
   </div>
   
   <style>
   .modal-content {
    margin: -50px auto;
    height: 88vh; 
    width: auto;
   }
   </style>
   <!-- For zooming the image by double click ===> end's here -->

</div> <!-- ***class scroll*** -->
<!-- *****lEFTSIDE MENU END'S HERE***** -->


<script>


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
  

    //Displaying the tag popup while searching ---> start's from here
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
    //Displaying the tag popup while searching ---> end's here
    
    
    // Assuming 'images' is your collection of image elements with class 'myImg'
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



</script>

</body>
</html>