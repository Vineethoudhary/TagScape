<?php
include 'db-connect-reports.php';
include 'upload_handler.php';
?>
<?php // At the very start of the script (before any HTML), start the session session_start();

// Then check if 'login_type' is set in the session and assign it to a variable for later use 
$login_type = isset($_SESSION['login_type']) ? $_SESSION['login_type'] : null;

// Now, instead of directly using $_SESSION['login_type'], use the $login_type variable 
?>

<aside class="main-sidebar sidebar-dark-primary elevation-4" style="background-color: #d5d3d3">
    <div class="dropdown">
      <?php if($login_type == 1): ?>
        <a href="./index.php?page=left_view" class="brand-link" style="background-color: white">
          <img src='./upload/uploads/logo.png' style="width: 9.5vw; height: 14vh; margin-left: 0.5vw; margin-top: -2vh; margin-bottom: -2vh; background: white;">
          <!-- <h3 class="text-center p-0 m-0"><b>BES Consultants</b></h3> -->
        </a>
        <?php else: ?>
          <a href="./index.php?page=user_left_view" class="brand-link" style="background-color: white">
          <img src='./upload/uploads/logo.png' style="width: 9.5vw; height: 14vh; margin-left: 0.5vw; margin-top: -2vh; margin-bottom: -2vh; background: white;">
            <!-- <h3 class="text-center p-0 m-0"><b>USER</b></h3> -->
          </a>
          <?php endif; ?>
      </div>
    
    <div class="sidebar pb-4 mb-4" style="margin-top: 12vh;">
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column nav-flat" data-widget="treeview" role="menu" data-accordion="false">
        <li class="nav-item dropdown">
          <?php if($login_type == 1): ?>
            <a href="./index.php?page=left_view" class="nav-link nav-home" style="color: #333;">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>
                Library
              </p>
            </a>
          <?php else: ?>
            <a href="./index.php?page=user_left_view" class="nav-link nav-home" style="color: #333;">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>
                Library
              </p>
            </a>
          <?php endif; ?>
        </li>
        <li class="nav-item dropdown">
          <?php if($login_type == 1): ?>
            <a href="http://localhost:5173/" class="nav-link nav-home" style="color: #333;">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>
                Transfer Data
              </p>
            </a>
          <?php else: ?>
            <a href="http://localhost:5173/" class="nav-link nav-home" style="color: #333;">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>
                Transfer Data
              </p>
            </a>
          <?php endif; ?>
        </li>
          <li class="nav-item">
            <ul class="nav nav-treeview">
            <?php if($login_type == 1): ?>
              <li class="nav-item">
                <a href="./index.php?page=new_project" class="nav-link nav-new_project tree-item">
                  <i class="fas fa-angle-right nav-icon"></i>
                  <p>Add New</p>
                </a>
              </li>
            <?php endif; ?>
            </ul>
          </li> 
          <!-- <?php if($login_type == 1): ?>
           <li class="nav-item">
                <a href="./index.php?page=calculation_check" class="nav-link nav-reports">
                  <i class="fas fa-th-list nav-icon"></i>
                  <p>Report</p>
                </a>
          </li>
          <?php endif; ?> -->
          <?php if($login_type == 1): ?>
          <li class="nav-item">
            <a href="#" class="nav-link nav-edit_user" style="color: #333;">
              <i class="nav-icon fas fa-users"></i>
              <p>
                Users
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="./index.php?page=new_user" class="nav-link nav-new_user tree-item">
                  <i class="fas fa-angle-right nav-icon"></i>
                  <p>Add New</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="./index.php?page=user_list" class="nav-link nav-user_list tree-item">
                  <i class="fas fa-angle-right nav-icon"></i>
                  <p>List</p>
                </a>
              </li>
            </ul>
          </li>
        <?php endif; ?>

        <!-- Display folders for USER start's from here-->
        <?php if ($login_type != 1): ?>
          <div style="color: #333; margin-top: -1vh;">
          <hr style="color: #333;">
          <p style="display: flex; text-align: left; color: #333; margin-left: 2vw">Folders:</p>
          <?php
    $folderPath = './upload/';
    $folders = array_filter(glob($folderPath . '*'), 'is_dir');
    $selectedFolder = isset($_GET['folder']) ? $_GET['folder'] : '';

    foreach ($folders as $folder) {
        $folderName = basename($folder);
        $highlightStyle = ($folderName === $selectedFolder) ? 'background-color: darkcyan; color: black;' : ''; // Add your highlight style here

        echo "<div style='display: flex; align-items: center; margin-bottom: 10px; {$highlightStyle}'>
                 <a href='?folder=$folderName' style='text-decoration: none; color: black;'>
                     <img src='./upload/uploads/folder-icon.JPEG' style='width: 20px; height: 20px; margin-left: 2vw;'>
                 </a>
                 <a href='?folder=$folderName' style='text-decoration: none; color: #333; margin-left: 0.3vw;'>$folderName</a>
            </div>";
    }
    ?>
          </div>
          <?php endif; ?>
          <!-- Display folders for USER end's here-->

          <!-- Displaying the folder stucture for ADMIN starts's from here -->
          <?php if ($login_type == 1): ?>
            <form action="" method="post" onsubmit="return validateFolderName();" style="margin-left: -1vw;">
            <hr style="color: #333;">
            <label for="folder_name" style="display: flex; flex: 1; text-align: left; color: #333; margin-left: 4vw; margin-top: 2vh;">Create Folder </label>
            <input type="text" id="folder_name" name="folder_name" placeholder="Enter folder name here" style="margin-top: 0vh; margin-left: 2vw"><br>
        <input type="submit" value="Create Folder" name="create_folder" style="margin-left: 4vw;><br>
        </form>
    <input type="hidden" name="id" value="<?php echo $id; ?>">
    <input type="hidden" name="tag" value="<?php echo $tags; ?>">

    <hr style="color: #333;">
    <h2 style="color: #333; font-size: 20px; margin-top: 3vh;" align="center">Please Select Image(s) <br> or PDF(s)</h2>
        <div class="center">
            <input type="file" id="fileInput" name="files[]" accept="image/*,video/*,.pdf" multiple style="display: none;">
            <div id="dropArea" ondrop="handleDrop(event)" ondragover="handleDragOver(event)" ondragenter="handleDragEnter(event)" ondragleave="handleDragLeave(event)">
                <p style="color:#333; margin-left: 0vw;">Drag and drop files here,<br> or <a href="#" onclick="browseFiles()" style="color: orange;">browse</a> to select files.</p>

            
                <?php
                $folderPath = './upload/';
                $subfolders = array_filter(glob($folderPath . '*'), 'is_dir');
                // echo "<$subfolder>";
                foreach ($subfolders as $subfolder) {
                    $subfolderName = basename($subfolder);
                    // echo "<option value=\"$subfolderName\">$subfolderName</option>";
                }
                ?>
          

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
              <div style="color: #333; margin-top: -4vh; margin-bottom: 130px;">
    <hr style="color: #333;">
    <p style="display: flex; flex: 1; text-align: left; color: #333; margin-left: 2vw;">Folders:</p>
    <?php
    $folderPath = './upload/';
    $folders = array_filter(glob($folderPath . '*'), 'is_dir');
    $selectedFolder = isset($_GET['folder']) ? $_GET['folder'] : '';

    foreach ($folders as $folder) {
        $folderName = basename($folder);
        $highlightStyle = ($folderName === $selectedFolder) ? 'background-color: darkcyan; color: black;' : ''; // Add your highlight style here

        echo "<div style='display: flex; align-items: center; margin-bottom: 10px; {$highlightStyle}'>
                 <a href='?folder=$folderName' style='text-decoration: none; color: black;'>
                     <img src='./upload/uploads/folder-icon.JPEG' style='width: 20px; height: 20px; margin-left: 2vw;'>
                 </a>
                 <a href='?folder=$folderName' style='text-decoration: none; color: #333; margin-left: 0.3vw;'>$folderName</a>
            </div>";
    }
    ?>
</div>

          <?php endif; ?>
          <!-- Displaying the folder stucture for ADMIN end's here -->
        </ul>
      </nav>
    </div>
  </aside>
  <script>
  	$(document).ready(function(){
      var page = '<?php echo isset($_GET['page']) ? $_GET['page'] : '' ?>';
  		var s = '<?php echo isset($_GET['s']) ? $_GET['s'] : '' ?>';
      if(s!='')
        page = page+'_'+s;
  		if($('.nav-link.nav-'+page).length > 0){
             $('.nav-link.nav-'+page).addClass('active')
  			if($('.nav-link.nav-'+page).hasClass('tree-item') == true){
            $('.nav-link.nav-'+page).closest('.nav-treeview').siblings('a').addClass('active')
  				$('.nav-link.nav-'+page).closest('.nav-treeview').parent().addClass('menu-open')
  			}
        if($('.nav-link.nav-'+page).hasClass('nav-is-tree') == true){
          $('.nav-link.nav-'+page).parent().addClass('menu-open')
        }

  		}
     
  	})

   //Code for not using special character in name using creating the folder start's from here
    function validateFolderName() {
    var folderNameInput = document.getElementById('folder_name');
    var folderName = folderNameInput.value;
    // Regular expression to match any special character
    var specialCharsRegex = /[`!@#$%^&*()_+\-=[\]{};':"\\|,.<>/?~]/;

    if (specialCharsRegex.test(folderName)) {
        alert('Please do not use special characters in the name while creating the folder.');
        return false; // Prevent form submission
    }
    
    return true; // Allow form submission
  }
  //Code for not using special character in name using creating the folder end's here


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
    document.addEventListener("DOMContentLoaded", function() {
    const dropArea = document.getElementById('drop-area');
    if (dropArea && !dropArea.hasAttribute('data-listener-attached')) {
        dropArea.addEventListener("dragenter", handleDragEnter, false);
        dropArea.addEventListener("dragover", handleDragOver, false);
        dropArea.addEventListener("dragleave", handleDragLeave, false);
        dropArea.addEventListener("drop", handleDrop, false);
        dropArea.setAttribute('data-listener-attached', 'true');
    }
});

    // for drag and drop the images end's here



    

    //Browsing the files from the local desktop start's from here
    function browseFiles() {
      var fileInput = document.getElementById("fileInput");
      // fileInput.style.background = "#007cb0";
      fileInput.click();
      
      fileInput.addEventListener("change", function() {
        var selectedFiles = fileInput.files;
        console.log(selectedFiles);
        
        // Delay opening the popup until after the file input dialog has closed
        if (selectedFiles.length > 0) {
          setTimeout(function() {
            showUploadForm(selectedFiles);
          }, 0);
        }
      });
    }//function browseFiles end's here
    
    function showUploadForm(selectedFiles) {
      var popupContent = document.createElement("div");
      popupContent.style.background = "#007cb0";

      
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
   
      
    
      // Function to add the form elements to the popup content
    function addFormElements() {
      // Create form for folder, label, tags, and subfolder
      var uploadForm = document.createElement("form");
      uploadForm.action = "upload_handler.php"; // Set your form action
      uploadForm.method = "post"; // Set your form method
      uploadForm.style.display = "flex"; // Use flexbox to make the form elements align horizontally
      // uploadForm.style.background = "#007cb0";
      
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
      
      var subfolderLabel = document.createElement("label");
      subfolderLabel.textContent = "Select Folder:";
      subfolderLabel.style.margin = "5px 5px";
      subfolderLabel.style.fontWeight = "bold";
      subfolderLabel.style.color = "white";
      uploadForm.appendChild(subfolderLabel);

      const subfolderDropdown = document.createElement("select");
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
      tagsLabel.style.margin = "5px 5px";
      tagsLabel.style.fontWeight = "bold";
      tagsLabel.style.color = "white";
      uploadForm.appendChild(tagsLabel);
      
      
      const tagsInput = document.createElement("input");
      tagsInput.required = true;
      tagsInput.type = "text";
      tagsInput.name = "tags";
      tagsInput.id = "tags";
      tagsInput.setAttribute("list", "tagSuggestions"); // Link to the datalist
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

      // Add this line to create a datalist for tag suggestions
      var dataList = document.createElement("datalist");
      dataList.id = "tagSuggestions"; // Set the ID for the datalist
      uploadForm.appendChild(dataList); // Append it to the upload form


      // Function to fetch tags based on user input
      tagsInput.addEventListener("input", function() {
        var searchTerm = this.value.trim();
        if (searchTerm.length < 2) {
            return; // Only fetch suggestions if the input is at least 2 characters
        }

        var xhttpFetchTags = new XMLHttpRequest();
        xhttpFetchTags.onreadystatechange = function() {
            if (this.readyState === 4 && this.status === 200) {
                var uniqueTags = JSON.parse(this.responseText);
                dataList.innerHTML = ""; // Clear existing options
                uniqueTags.forEach(function(tag) {
                    var option = document.createElement('option');
                    option.value = tag;
                    dataList.appendChild(option);
                });
            }
        };
        xhttpFetchTags.open("GET", "fetch_tags.php?search=" + encodeURIComponent(searchTerm), true);
        xhttpFetchTags.send();
      });
      uploadForm.appendChild(tagsInput);

      // New Free Text Input for User's Description
    var descriptionLabel = document.createElement("label");
    descriptionLabel.textContent = "Enter Description:";
    descriptionLabel.style.margin = "5px 5px";
    descriptionLabel.style.fontWeight = "bold";
    descriptionLabel.style.color = "white";
    uploadForm.appendChild(descriptionLabel);

    var descriptionInput = document.createElement("textarea");
    descriptionInput.name = "description";
    descriptionInput.id = "description";
    descriptionInput.style.marginLeft = "1vw";
    descriptionInput.style.marginTop = "0px";
    descriptionInput.style.borderRadius = "10px";
    descriptionInput.style.width = "25%";
    descriptionInput.style.height = "25px";
    descriptionInput.placeholder = "You can add the description here...";

    uploadForm.appendChild(descriptionInput);


      //Error Handling for Empty Tags 
      const errorDiv = document.createElement("div");
      errorDiv.id = "tagsError";
      errorDiv.style.color = "cyan";
      errorDiv.style.marginTop = "5px";
      uploadForm.appendChild(errorDiv);

   //Error Handling for folder Selection
      const subfolderErrorDiv = document.createElement("div");
        subfolderErrorDiv.id = "subfolderError";
        subfolderErrorDiv.style.color = "red";
        subfolderErrorDiv.style.marginTop = "5px";
        uploadForm.appendChild(subfolderErrorDiv);

            


      const submitButton = document.createElement("button");
      submitButton.type = "submit1"; //Change the type to button to prevent form submission
      submitButton.name = "submit1";
      submitButton.textContent = "Upload files";
      uploadForm.enctype = "multipart/form-data";
      submitButton.style.marginLeft = "1vw"; // Add margin for spacing between the last input and the button
      // submitButton.style.background = "darkcyan";

      
      submitButton.addEventListener("click", function(event) {
        event.preventDefault();
        // Handle form submission logic here
        var labelValue = labelDropdown.value;
        var subfolderValue = subfolderDropdown.value;
        var tagsValue = tagsInput.value;
        var descriptionValue = descriptionInput.value; // Get the description value

        // Validate form fields
        if (tagsValue === '') {
          errorDiv.textContent = "Please enter a tag name";
          return; // Prevent further execution
        } 
        else{
          errorDiv.textContent=''
        }

        if (subfolderValue === '') {
          subfolderErrorDiv.textContent = "Please select a folder";
          return; // Prevent further execution
        } else {
          subfolderErrorDiv.textContent = ""; // Clear the error message
        }
        
        // Perform AJAX request to handle file upload
        var formData = new FormData();
        for (var i = 0; i < selectedFiles.length; i++) {
          formData.append("files[]", selectedFiles[i]);
        }
        formData.append("label", labelValue);
        formData.append("subfolder", subfolderValue);
        formData.append("tags", tagsValue);
        formData.append("description", descriptionValue); // Add description to form data
        
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "upload_handler.php", true);
        
        xhr.onreadystatechange = function () {
          if (xhr.readyState === 4) {
            // Check if the request was successful
            if (xhr.status === 200) {
              // Handle the response from the server
              alert(xhr.responseText);
              
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
                    popup.close();
                  } else {
                    console.error("Database connection failed.");
                  }
                }
              };
              xhr2.send(); // Send the AJAX request to db-connect-report.php
            } else {
              alert('An error occurred: ' + xhr.responseText);            }
          }
        };
        
        xhr.send(formData); // Send the AJAX request to upload_handler.php

        xhr.onerror = function () {
    alert('An error occurred while uploading the file.');
};

// Inside the AJAX error function
xhr.onerror = function (xhr, status, error) {
    // Check if the status code is 400 (indicating client error)
    if (xhr.status === 400) {
        // Parse the JSON response to get the error message
        var response = JSON.parse(xhr.responseText);
        alert(response.error); // Display the duplicate file warning
    } else {
        // Display generic error message for other errors
        alert('An error occurred: ' + xhr.responseText);
    }
};
        
        // Close the popup after submission
        popup.close();
      });
      
      uploadForm.appendChild(submitButton);
      
      // Append form and submit button to popup content
      popupContent.appendChild(uploadForm);
    
    } //Function AddFormElement end's here
    
    // Add the selected files to the popup content
    for (var i = 0; i < selectedFiles.length; i++) {
      addImageToPopup(selectedFiles[i]);
    }// Open the popup window
    var popup = window.open("", "_blank", "width=1150,height=600");
    if (!popup) {
      console.error('Popup blocked. Please allow popups for this website and try again.');
      return;
    }
    // Your existing code to add content to popup...
    
    // Append the popupContent to the opened popup window
    popup.document.body.appendChild(popupContent);

  
  } //Function showUploadForm end's here

    //Browsing the files from the local desktop end's here
  </script>