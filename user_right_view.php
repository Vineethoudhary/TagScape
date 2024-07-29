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
    <link rel="stylesheet" type="text/css" href="user_right_view.css">
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
        }
         */
        .video {
            width: 12.7vw;
            height: 24.5vh;
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
            bottom: 1px;
            left: 1px;
            cursor: pointer;
            z-index: 1;
            /* opacity: 0; */
            cursor: pointer;    
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

<body>

<div class="scroll" style="background-color: #c6c8cc; padding-left: 30px;">
    
    <!-- This is for the parameters used on the top -->
    <div class="parent-container" style="display: flex; align-items: center; justify-content: space-between;"> 
    
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
            echo "<div style='margin-top: 1.5vh; margin-left: -16.5vw; margin-bottom: 2.5vh;'>";
            echo "</div>";
        }
    }
    ?>
    </div><!-- This is for the parameters used on the top end's here-->
    
    
    
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

    // Create the base prepared statement
    $query = "SELECT * FROM files WHERE $conditionString";

    // Array to store excluded file types
    $fileTypes = [];

    // Add excluded file types to the query
    if (isset($_GET['filetype'])) {
        // Ensure $_GET['filetype'] is an array or a comma-separated string
        $fileTypes = is_array($_GET['filetype']) ? $_GET['filetype'] : explode(',', $_GET['filetype']);

        // Add file type exclusion conditions to the query
        $fileTypeConditions = [];

        // Create conditions to exclude each file type
        foreach ($fileTypes as $fileTypeGroup) {
            $extensions = explode(',', $fileTypeGroup);
            foreach ($extensions as $ext) {
                $fileTypeConditions[] = "file NOT LIKE '%.$ext'";
            }
        }

        if (!empty($fileTypeConditions)) {
            $query .= " AND (" . implode(' AND ', $fileTypeConditions) . ")";
        }
    }

    // Array to store excluded folders
    $folders = [];

    // Add excluded folders to the query
    if (isset($_GET['folder'])) {
        // Ensure $_GET['folder'] is an array or a comma-separated string
        $folders = is_array($_GET['folder']) ? $_GET['folder'] : explode(',', $_GET['folder']);

        // Add folder exclusion conditions to the query
        $folderConditions = [];

        // Create conditions to exclude each folder
        foreach ($folders as $folder) {
            $folderConditions[] = "path NOT LIKE '%/$folder/%'";
        }

        if (!empty($folderConditions)) {
            $query .= " AND (" . implode(' AND ', $folderConditions) . ")";
        }
    }

    $query .= " ORDER BY id ASC";

    // Create the prepared statement
    $stmt = $conn->prepare($query);

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

                echo "<div id='$fileName' style='position: relative; display: inline-block;'>";
                
                if ($fileType == 'pdf') {
                    echo <<<HTML
                    <label class="checkbox-container">
                        <div class='file-contain' style="position: relative;">
                            <div class='image-container'>
                            <embed src='upload/$subfolder/$fileName' class='myImg' width='165' height='165' type='application/pdf' style='margin-right: 8px; margin-bottom: 8px; height: 23.5vh; width: 13vw;' alt='Uploaded Image' data-tags='$tags' >
                                <input type='checkbox' class='file-checkbox' data-id='$id'>
                            </div>
                            <div class='tag-container'>
                                <!-- <button class='delete-button' onclick='deleteImage("$fileName")'><i class='fa fa-trash'></i></button> -->
                                <span style="overflow: hidden; text-overflow: ellipsis; white-space: nowrap; width: 13vw" class='tag'>$tags</span>
                            </div>
                        </div>
                    </label>
                    HTML;
                } else if (in_array($fileType, ['mp4', 'mov', 'avi', 'flv', 'wmv', 'MP4', 'MOV', 'AVI', 'FLV', 'WMV'])) {
                    echo <<<HTML
                    <div class="file-contain">
                        <div class='video-container'>
                            <video class='video' controls>
                                <source src='upload/$subfolder/$fileName' type='video/$fileType'>
                                Your browser does not support the video tag.
                            </video>
                            <input type='checkbox' class='file-checkbox' data-id='$id'>
                            <div class='tag-container'>
                                <!-- <button class='delete-button' onclick='deleteImage("$fileName")'><i class='fa fa-trash'></i></button> -->
                                <span class='tag'>$tags</span>
                            </div>
                        </div>
                    </div>
                    HTML;
                } else {
                    echo <<<HTML
                    <label class="checkbox-container">
                        <div class='file-contain' style="position: relative;">
                            <div class='image-container'>
                                <img src='./upload/$subfolder/$fileName' class='myImg' alt='Uploaded Image' data-tags='$tags'>
                                <input type='checkbox' class='file-checkbox' data-id='$id'>
                            </div>
                            <div class='tag-container'>
                                <!-- <button class='delete-button' onclick='deleteImage("$fileName")'><i class='fa fa-trash'></i></button> -->
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
                        bottom: 5px;
                        left: 2px;
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
                    color: #ffffff;
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
                    <p class="text">Oops! No match found.😟</p>
                   
                </div>
            </div>
            HTML;
        }
        echo "</div>";
    }
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
    margin: -80px auto;
    height: 85vh; 
    width: auto;
    top: 6vh;
}
</style>
    <!-- For zooming the image by double click ===> end's here -->

</div>
<!-- *****RIGHTSIDE MENUI END'S HERE***** -->
</div> <!-- div class row is ending here -->

<script>

// Applying the type condition after searching start's from here 
// document.addEventListener("DOMContentLoaded", function() {
//     // Get all file type checkboxes
//     var fileTypeCheckboxes = document.querySelectorAll('input[name="filetype"]');

//     // Add event listener to each checkbox
//     fileTypeCheckboxes.forEach(function(checkbox) {
//         checkbox.addEventListener('change', function() {
//             var fileType = this.value;

//             // Get all files of the changed type
//             var filesOfType = document.querySelectorAll('.file-contain[data-filetype="' + fileType + '"]');

//             // Show or hide files based on checkbox status
//             if (this.checked) {
//                 filesOfType.forEach(function(file) {
//                     file.style.display = 'block';
//                 });
//             } else {
//                 filesOfType.forEach(function(file) {
//                     file.style.display = 'none';
//                 });
//             }
//         });
//     });
// });
// Applying the type condition after searching end's here 



// Applying the type condition before searching start's from here 
// document.addEventListener("DOMContentLoaded", function() {
//     var fileTypeCheckboxes = document.querySelectorAll('input[name="filetype"]');
//     var allCheckbox = document.getElementById('all');

//     // Add event listener to each checkbox
//     fileTypeCheckboxes.forEach(function(checkbox) {
//         checkbox.addEventListener('change', function() {
//             if (this.id === 'all') {
//                 toggleAll(this);
//             } else {
//                 filterFiles();
//             }
//         });
//     });

//     function toggleAll(source) {
//         fileTypeCheckboxes.forEach(function(checkbox) {
//             checkbox.checked = source.checked;
//         });
//         filterFiles();
//     }

//     function filterFiles() {
//         var files = document.querySelectorAll('.file-contain');
//         var allSelected = allCheckbox.checked;

//         files.forEach(function(file) {
//             var fileType = file.getAttribute('data-filetype');
//             var isVisible = allSelected;

//             if (!allSelected) {
//                 fileTypeCheckboxes.forEach(function(checkbox) {
//                     if (checkbox.checked && checkbox.value.split(',').includes(fileType)) {
//                         isVisible = true;
//                     }
//                 });
//             }

//             file.style.display = isVisible ? 'block' : 'none';
//         });
//     }

//     // Call filterFiles function initially to apply initial filtering
//     filterFiles();
// });

// document.addEventListener("DOMContentLoaded", function() {
//     var fileTypeCheckboxes = document.querySelectorAll('input[name="filetype"]');
//     var folderCheckboxes = document.querySelectorAll('input[name="folder"]');
//     var allCheckbox = document.getElementById('all');
//     var filterForm = document.getElementById('filterForm');

//     // Add event listener to each checkbox
//     fileTypeCheckboxes.forEach(function(checkbox) {
//         checkbox.addEventListener('change', function() {
//             if (this.id === 'all') {
//                 toggleAll(this);
//             } else {
//                 updateAllCheckbox();
//                 filterFiles();
//             }
//             updateURL();
//         });
//     });

//     folderCheckboxes.forEach(function(checkbox) {
//         checkbox.addEventListener('change', function() {
//             filterFiles();
//             updateURL();
//         });
//     });

//     function toggleAll(source) {
//         fileTypeCheckboxes.forEach(function(checkbox) {
//             checkbox.checked = source.checked;
//         });
//         filterFiles();
//     }

//     function updateAllCheckbox() {
//         var allChecked = true;
//         fileTypeCheckboxes.forEach(function(checkbox) {
//             if (checkbox.id !== 'all' && !checkbox.checked) {
//                 allChecked = false;
//             }
//         });
//         allCheckbox.checked = allChecked;
//     }

//     function filterFiles() {
//         var files = document.querySelectorAll('.file-contain');
//         var allSelected = allCheckbox.checked;

//         files.forEach(function(file) {
//             var fileType = file.getAttribute('data-filetype');
//             var fileFolder = file.getAttribute('data-folder');
//             var isVisible = allSelected;

//             if (!allSelected) {
//                 // Check if the file's type matches any checked file type
//                 var fileTypeMatch = false;
//                 fileTypeCheckboxes.forEach(function(checkbox) {
//                     if (checkbox.checked && checkbox.value.split(',').includes(fileType)) {
//                         fileTypeMatch = true;
//                     }
//                 });

//                 // Check if the file's folder matches any checked folder
//                 var folderMatch = false;
//                 folderCheckboxes.forEach(function(checkbox) {
//                     if (checkbox.checked && checkbox.value === fileFolder) {
//                         folderMatch = true;
//                     }
//                 });

//                 // File is visible if both type and folder match
//                 isVisible = fileTypeMatch && folderMatch;
//             }

//             file.style.display = isVisible ? 'block' : 'none';
//         });
//     }

//     function updateURL() {
//         var params = new URLSearchParams(window.location.search);
//         var searchTerm = params.get('search') || '';
//         var fileTypes = [];
//         var folders = [];

//         fileTypeCheckboxes.forEach(function(checkbox) {
//             if (checkbox.checked && checkbox.id !== 'all') {
//                 fileTypes.push(checkbox.value);
//             }
//         });

//         folderCheckboxes.forEach(function(checkbox) {
//             if (checkbox.checked) {
//                 folders.push(checkbox.value);
//             }
//         });

//         params.set('search', searchTerm);

//         if (allCheckbox.checked) {
//             params.delete('filetype');
//         } else {
//             params.delete('filetype');
//             fileTypes.forEach(function(fileType) {
//                 params.append('filetype', fileType);
//             });
//         }

//         params.delete('folder');
//         folders.forEach(function(folder) {
//             params.append('folder', folder);
//         });

//         window.history.replaceState({}, '', `${window.location.pathname}?${params}`);
//     }

//     // Call filterFiles and updateURL function initially to apply initial filtering
//     filterFiles();
//     updateURL();
// });


// Applying the type condition after searching end's here 


// Function to add zoom functionality to the PDF embed
function addPDFZoom() {
    // Select all elements with the class 'myImg'
    var embeds = document.querySelectorAll('.myImg');

    // Loop through each embed element
    embeds.forEach(function(embed) {
        // Add event listeners for zooming when the embed is double-clicked
        embed.addEventListener('dblclick', function() {
            // Check if the element already has a zoom level
            if (!embed.dataset.zoomLevel) {
                // If not, set the initial zoom level to 1
                embed.dataset.zoomLevel = 1;
            }
            
            // Increase the zoom level by 0.1
            var newZoomLevel = parseFloat(embed.dataset.zoomLevel) + 0.1;

            // Update the zoom level attribute
            embed.dataset.zoomLevel = newZoomLevel;

            // Apply the zoom using CSS transform
            embed.style.transform = 'scale(' + newZoomLevel + ')';
        });
    });
}

// Call the addPDFZoom function when the document is ready
document.addEventListener('DOMContentLoaded', addPDFZoom);

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
// Zooming in and out/ slideshow script end's here 


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



    //for deleting the multiple images from right_view  ---> start's from here
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
    //for deleting the multiple images from right_view  ---> end's here



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

</script>

</body>
</html>