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
                    width: 100%;
                    box-sizing: border-box;
                    overflow: hidden;    
                    text-overflow: ellipsis; 
                    white-space: nowrap;    
                }
</style>
</head>

<body>

<!-- *****LEFTSIDE MENU STARTS FROM HERE***** -->
<!-- <div class="row">  -->
<div class="roll" style="margin-top: -2vh; margin-bottom: -11vh; width: 105%; background-color: #404040;">

<!-- <button onclick='changeTags()'>Change Tags</button>; -->

<div class="search-print-container" style="display: inline-block; vertical-align: top;">
    <form id="searchForm" method="get" action="./index.php" style="display: flex; justify-content: center; margin-top: -14.5vh;margin-left: 61.5vw;">
        <input type="hidden" name="page" value="user_right_view">
        <input type="text" id="search" name="search" class="search-input" autocomplete="off" placeholder="Search by tag" style="">
        <input type="submit" class="search" value="Search" >
    </form>
</div>
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
    <?php
   $sql = "SELECT * FROM files";
   $result = mysqli_query($conn , $sql);
   if(mysqli_num_rows($result)>0){
       echo "<div style='margin-top: -5vh; margin-left: 5.5vw; margin-bottom: 2.5vh;'>";
       echo "<span style='margin-right: 2vw; margin-left: 18vw; background: #f0ffff; border-radius: 5px; width: 14rem; height:4vh; display: inline-block;'><b>Tags:</b></span>";
       echo "<span style='margin-right: 2vw; background: #f0ffff; border-radius: 5px; width: 14rem; height:4vh; display: inline-block;'><b>Label:</b> </span>";
       echo "</div>";
       while($fetch = mysqli_fetch_assoc($result)){
           $fileType = pathinfo($fetch['file'], PATHINFO_EXTENSION);
           $tag = $fetch['tags']; 
           $id = $fetch['id'];
           $label = $fetch['label'];
           $filePath = 'upload/' . $fetch['file'];
   
           // Display each tag and label here
       }
   }
?>

    <!-- To fetch the images form the sub-folders on the frontend (middle part) starts from here -->
    <?php
    if (isset($_GET['folder'])) {
        $selectedFolder = $_GET['folder'];
        $folderPath = './upload/' . $selectedFolder;
        $files = glob($folderPath . '/*.{jpg,JPG,jpeg,JPEG,png,PNG,gif,GIF}', GLOB_BRACE); // Fetch only image files
        
        echo "<ul style='list-style: none; display: flex; flex-wrap: wrap;'>";
        foreach ($files as $file) {
            $fileName = basename($file);
        
            // Get the file's record from the database
            $sql = "SELECT * FROM files WHERE file LIKE '%$fileName%'";
            $result = mysqli_query($conn , $sql);
            
            // Only proceed if a matching record is found in the database
            if(mysqli_num_rows($result)>0){
                $fetch = mysqli_fetch_assoc($result);
            
                $tag = $fetch['tags']; // fetch the tag from the current row
                $id = $fetch['id'];
        
                echo "<li style='margin-right: 10px; position: relative;'>";
                    echo "<div class='file-contain'>";

                         echo "<div class='image-container'>";
                                 echo "<a href='$file'><img src='$file' alt='$fileName' class='myImg'></a>";
                         echo "</div>";
                         echo "<div class='tag-container'>"; 
                                echo "<span class='tag'>".$tag."</span>"; 
                        echo "</div>";
                        
                    echo "</div>"; // Display the tag name on top of the image
                // echo "<input type='checkbox' class='file-checkbox' data-id='".$id."' style='position:absolute; top:1px; left:-0.15vw;'>";
                echo "</li>";
            }
        }
        // echo "<span style='position:absolute; top: 6px; left: 6px; color:white; background-color: dodgerblue; font-size: 10px; padding: 2px;'>".$tag."</span>"; // Display the tag name on top of the image
    } else {
        $sql = "SELECT * FROM files";
        $result = mysqli_query($conn , $sql);
        if(mysqli_num_rows($result)>0){
            while($fetch = mysqli_fetch_assoc($result)){
                $fileType = pathinfo($fetch['file'], PATHINFO_EXTENSION);
                $tag = $fetch['tags']; // fetch the tag from the current row
                $id = $fetch['id'];
                if ($fileType == 'pdf') {
                    echo "<embed src='upload/" . $fetch['file'] . "' width='178.4' height='178.4' type='application/pdf'>";
                } else if ($fileType == 'mp4' || $fileType == 'mov' || $fileType == 'avi' || $fileType == 'flv' || $fileType == 'wmv' ||
                $fileType == 'MP4' || $fileType == 'MOV' || $fileType == 'AVI' || $fileType == 'FLV' || $fileType == 'WMV') {
                    echo "<video width='178.4' height='178.4' controls style='margin-right: 5px; margin-left: 5px; margin-bottom: 5px; margin-top:'>";
                    echo "<source src='upload/" . $fetch['file'] . "' type='video/$fileType'>";
                    echo "Your browser does not support the video tag.";
                    echo "</video>";
                } else {
                    $id = $fetch['id'];
                    echo "<div style='display:inline-block; position:relative; margin-right: 6px; margin-bottom: 12px; margin-left: 6px;'>";
                    echo "<img src='upload/" . $fetch['file'] . "' class='myImg' width='170' height='170' style='padding: 2px;' data-tag='".$tag."'>"; 
                    echo "<span style='position:absolute; top: 6px; left: 6px; color:white; background-color: dodgerblue; font-size: 10px; padding: 2px;'>".$tag."</span>"; // Display the tag name on top of the image
                    echo "<button onclick='changeTag(".$id.")' style='position:absolute; top:1px; right:98px; background-color: transparent; border: none; font-size: 17px; color: black;'>&#8942;</button>"; // Button with 3 vertical dots
                    echo "</div>";
                }
            }//while clause
        }//if clause    
    }
    ?>
        <!-- To fetch the images on the frontend (middle part) end's here -->

 
 
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





<script>
    //*****for drag and drop the images -->starts from here*****
    function handleDragOver(event) {
        event.preventDefault();
        var fileInput = document.getElementById("fileInput");
        fileInput.files = event.dataTransfer.files;
        document.getElementById("dropArea").classList.add("dragover");
        if(fileInput.files.length > 0) {
            window.alert(fileInput.files.length + " file's selected!");
        }
    }

    function handleDragEnter(event) {
        event.preventDefault();
        var fileInput = document.getElementById("fileInput");
        fileInput.files = event.dataTransfer.files;
        document.getElementById("dropArea").classList.add("dragover");
        if(fileInput.files.length > 0) {
            window.alert(fileInput.files.length + " file's selected!");
        }
    }

    function handleDragLeave(event) {
        event.preventDefault();
        var fileInput = document.getElementById("fileInput");
        fileInput.files = event.dataTransfer.files;
        document.getElementById("dropArea").classList.remove("dragover");
        if(fileInput.files.length > 0) {
            window.alert(fileInput.files.length + " file's selected!");
        }
    }
    // for drag and drop the images end's here

    //Browsing the files from the local desktop start's from here
    function browseFiles() {
    var fileInput = document.getElementById("fileInput");
    fileInput.click();
    
    fileInput.addEventListener("change", function() {
        var selectedFiles = fileInput.files.length;
        window.alert(selectedFiles + " file(s) selected!");
    });
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
