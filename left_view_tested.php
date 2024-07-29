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
</head>

<body background="black">

<!-- *****LEFTSIDE MENU STARTS FROM HERE***** -->
<!-- <div class="row">  -->
<div class="roll" style="margin-top: -2vh; margin-bottom: -11vh; width: 85.4%;">

<div class="search-print-container" style="display: inline-block; vertical-align: top;">
    <form id="searchForm" method="get" action="./index.php" style="display: flex; justify-content: center; margin-top: -13.5vh;margin-left: 28.5vw;">
        <input type="hidden" name="page" value="right_view">
        <input type="text" id="search" name="search" class="search-input" autocomplete="off" placeholder="Search by tag" style="margin-left: 13vw;">
        <input type="submit" class="search" value="Search" >
    </form>
</div>
    <div class="folder">

        <?php
        $target_dir = "/";
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
            echo "<img src='upload/" . $fetch['file'] . "' class='myImg' width='178' height='178' style='padding: 2px;' data-tag='".$tag."'>"; 
            echo "<span style='position:absolute; top: 6px; left: 6px; color:white; background-color: dodgerblue; font-size: 10px; padding: 2px;'>".$tag."</span>"; // Display the tag name on top of the image
            echo "<button onclick='changeTag(".$id.")' style='position:absolute; top:1px; left:-0.15vw; background-color: transparent; border: none; font-size: 17px; color: black;'>&#8942;</button>"; // Button with 3 vertical dots
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
<div class="formdesign" style="width: 16.19%;">

    <form action="" method="POST" enctype="multipart/form-data">

    <form action="" method="post" >
        <label style="display: flex; flex: 1; text-align: left; color: ivory; margin-left: 5vw; margin-top: 2vh;">Create Folder </label>
        <input type="text" name="folder_name" placeholder="Enter folder name here" style="margin-top: 0vh; margin-left: 3vw">
        <input type="submit" value="Create Folder" name="create_folder" style="margin-left: 5vw;><br>
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
                if(isset($_GET['msg']) AND $_GET['msg']=='ins'){
                    echo "<p style='text-align: center; font-type:bold; color: ivory; background-color:#07a407; border-radius: 12px; padding: 4px; box-shadow: 2px 4px 6px #6ad56a;'>File Uploaded Successfully..!!</p>";
                }
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

            <label for="subfolder" style="color: ivory;">Select Subfolder:</label><br>
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

        <!-- Saving the files under the subfolder present in the folder structure start's from here -->
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
                         <img src='./upload//folder-icon.jpeg' style='width: 20px; height: 20px; margin-left: 1vw;'>
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
        var fileInput = document.getElementById("fileInput");
        fileInput.files = event.dataTransfer.files;
        document.getElementById("dropArea").classList.add("dragover");
        if(fileInput.files.length > 0) {
            window.alert(fileInput.files.length + " file's selected!");
        }
    }
// // for drag and drop test
//     function handleDragOver(event) {
//     event.preventDefault();
//     var fileInput = document.getElementById("fileInput");
//     var files = event.dataTransfer.files;

//     for (var i = 0; i < files.length; i++) {
//         fileInput.files.push(files[i]);
//     }

//     document.getElementById("dropArea").classList.add("dragover");

//     if (fileInput.files.length > 0) {
//         window.alert(fileInput.files.length + " file(s) selected!");
//     }
// }

// function handleDrop(event) {
//     event.preventDefault();  // Prevent default behavior of opening files

//     var fileInput = document.getElementById("fileInput");
//     var files = event.dataTransfer.files;

//     for (var i = 0; i < files.length; i++) {
//         fileInput.files.push(files[i]);
//     }

//     document.getElementById("dropArea").classList.remove("dragover");

//     if (fileInput.files.length > 0) {
//         window.alert(fileInput.files.length + " file(s) selected!");
//     }
// }

// // Attach the event listeners
// var dropArea = document.getElementById("dropArea");
// dropArea.addEventListener("dragover", handleDragOver);
// dropArea.addEventListener("drop", handleDrop);
// dropArea.addEventListener("dragleave", function () {
//     document.getElementById("dropArea").classList.remove("dragover");
// });


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
        var selectedFiles = fileInput.files;
        
        if (selectedFiles.length > 0) {
            var popupContent = document.createElement("div");

            for (var i = 0; i < selectedFiles.length; i++) {
                var fileReader = new FileReader();
                fileReader.onload = function (event) {
                    var imageElement = document.createElement("img");
                    imageElement.src = event.target.result;
                    imageElement.style.maxWidth = "20vw";  // Set maximum width
                    imageElement.style.maxHeight = "20vh"; // Set maximum height
                    imageElement.style.marginLeft = "1vw"; // Set maximum height
                    imageElement.style.marginBottom = "1vh"; // Set maximum height

                    popupContent.appendChild(imageElement);

                    if (popupContent.childElementCount === selectedFiles.length) {
                        showImagePopup(popupContent);
                    }
                };
                fileReader.readAsDataURL(selectedFiles[i]);
            }
        }
    });
    }
    
    function showImagePopup(content) {
        var popup = window.open("", "Image Popup", "width=800,height=600");
        popup.document.body.appendChild(content);
    }
    
    function showImagePopup(content) {
        var popup = window.open("", "Image Popup", "width=800,height=600");
        popup.document.body.appendChild(content);
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
function changeTag(id) {
    var newTag = prompt("Enter new tag name:");
    if (newTag) {
        // use AJAX to send the new tag to a PHP script
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("tag_" + id).textContent = newTag;
            }
        };
        xhttp.open("POST", "change_tag.php", true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhttp.send("id=" + id + "&tag=" + newTag);
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