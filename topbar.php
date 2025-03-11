<?php
include 'sidebar.php';
?>



<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- <title>image & pfd's upload</title> -->
    <!-- <link rel="stylesheet" type="text/css" href="css/left_view.css"> -->
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

   <!-- Navbar -->
<style>
.search-print-container {
  display: inline-block;
  margin-left: 10vw;
}

#searchForm {
  display: flex;
  align-items: center;
  margin-top: -13.5vh;
  /* margin-left: 28.5vw; */
  position: relative;
}
      
.search-input-container {
  position: relative;
}

.search-input {
  width: 230px;
  height: 46px;
  padding: 5px 25px 5px 5px; /* Adjust padding for the icon */
  background-color: #2C2C2C;
  color: white;
  border: 3px solid #2c2c2c;
  border-radius: 0.5vh;
  transition: background-color 0.3s, filter 0.3s;
  background-position: right top;
  /* margin-right: 0.5vw; */
  margin-top: 15vh;
  margin-bottom: 1vh;
  display: inline-block;
  margin-left: 4vw;
}
      
.search-input:hover {
  background-color: #2C2C2C;
  filter: blur(0.5px); /* Apply blur effect on hover */
}
      
.search-icon {
  position: absolute;
  top: 50%;
  right: 5px;
  transform: translateY(-50%);
  border-radius: 10px;
  color: white;
  transition: background-color 0.3s, filter 0.3s;
}

.search::before {
  content: "";
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(255, 255, 255, 0.2); /* White color overlay with 20% opacity */
  z-index: -1;
  opacity: 0; /* Initially hidden */
  transition: opacity 0.3s;
  transition: background-color 0.3s, filter 0.3s;
}
      
.search:hover::before {
  opacity: 1; /* Show the overlay on hover */
  transition: background-color 0.3s, filter 0.3s;
}
      
.search {
  background-color: #2C2C2C;
  color: #a4a4ae;
  border: none;
  border-radius: 8px;
  cursor: pointer;
  position: relative;
  height: 46px;
  margin-top: 14vh;
  margin-left: 1vw;
}

.suggestion-list {
  max-height: 80vh; /* Maximum height of the suggestion list */
  overflow-y: auto; /* Enable vertical scrolling if the content exceeds the maximum height */
}
      
/* The Modal (background) */
.tag-modal {
  display: none; /* Hidden by default */
  position: fixed; /* Stay in place */
  z-index: 1; /* Sit on top */
  left: 0;
  top: 0;
  width: 100%; /* Full width */
  height: 100%; /* Full height */
  overflow: auto; /* Enable scroll if needed */
  background-color: transparent; /* Black w/ opacity */
}
      
/* Modal Content/Box */
.tag-modal-content {
  background-color: #007cb0;
  margin: 15% auto; /* 15% from the top and centered */
  padding: 20px;
  border: 1px solid #888;
  /* Could be more or less, depending on screen size */
  width: 25vw; /* Set your desired width */
  height: 25vh; /* Set your desired height */
  box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2);
}
      
      
/* The Close Button */
.tag-close {
  color: #aaa;
  float: right;
  font-size: 28px;
  font-weight: bold;
  margin: -3.3vh -1vw;
}
      
      
.tag-close:hover,
.tag-close:focus {
  color: black;
  text-decoration: none;
  cursor: pointer;
}

.custom-dropdown .select-box {
  padding: 10px;
  background-color: #f0f0f0;
  cursor: pointer;
  border: 1px solid #ccc;
  user-select: none;
  padding: 3px;
  margin-left: 1vw;
  margin-top: -1vh;
  border-radius: 5px;
}

.custom-dropdown .caret {
    float: right;
    margin-left: 5px;
}

.checkboxes-containers {
    display: none;
    position: absolute;
    background-color: #f9f9f9;
    border: 1px solid #ddd;
    width: 30%;
    z-index: 1000;
    margin-left: 50px;
}

.checkboxes-containers label {
    display: block;
    margin: 10px;
    cursor: pointer;
}

.checkboxes-containers label:hover {
    background-color: #f1f1f1;
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


    <nav class="main-header navbar navbar-expand navbar-primary navbar-dark" style="background: linear-gradient(to right, #66cc33, #2c5fff);">
    <!-- Left navbar links -->

      <div class="buttons-container" style="display: flex; align-items: center;">
      
      <button id="download-button"
          style="background-color: #404040; color: #a4a4ae; font-weight: 550; border: none; padding: 2px 25px; cursor: pointer; transition: background-color 0.15s, box-shadow 0.15s, color 0.15s, transform 0.15s, font-weight 0.15s; margin-top: 1vh; margin-left: 0vw; border-radius: 8px; height: 45px;"
          onmouseover="this.style.backgroundColor='#2db875'; this.style.color='#FFFFFF'; this.style.boxShadow='0 0 10px rgba(255, 255, 255, 0.5) inset'; this.style.transform='scale(1.05)'; this.style.fontWeight='650';"
          onmouseout="this.style.backgroundColor='#404040'; this.style.color='#a4a4ae'; this.style.boxShadow='none'; this.style.transform='scale(1)'; this.style.fontWeight='550';">
          Download
      </button>

      <?php if ($login_type == 1): ?>
      <button onclick="changeTags()"
        style="background-color: #404040; color: #a4a4ae; font-weight: 550; border: none; padding: 2px 25px; cursor: pointer; transition: background-color 0.15s, box-shadow 0.15s, color 0.15s, transform 0.15s, font-weight 0.15s; margin-top: 1vh; margin-left: 1vw; border-radius: 8px; height: 46px;"
        onmouseover="this.style.backgroundColor='#2db875'; this.style.color='#FFFFFF'; this.style.boxShadow='0 0 10px rgba(255, 255, 255, 0.5) inset'; this.style.transform='scale(1.05)'; this.style.fontWeight='650';"
        onmouseout="this.style.backgroundColor='#404040'; this.style.color='#a4a4ae'; this.style.boxShadow='none'; this.style.transform='scale(1)'; this.style.fontWeight='550';">
        Change Tags
     </button>
      <?php endif; ?>
      <!-- <button id="delete-button"
        style="background-color: #404040; color: #a4a4ae; font-weight: 550; border: none; padding: 2px 25px; cursor: pointer; transition: background-color 0.15s, box-shadow 0.15s, color 0.15s, transform 0.15s, font-weight 0.15s; margin-top: 1vh; margin-left: 1vw; border-radius: 8px; height: 45px;"
        onmouseover="this.style.backgroundColor='#2db875'; this.style.color='#FFFFFF'; this.style.boxShadow='0 0 10px rgba(255, 255, 255, 0.5) inset'; this.style.transform='scale(1.05)'; this.style.fontWeight='650';"
        onmouseout="this.style.backgroundColor='#404040'; this.style.color='#a4a4ae'; this.style.boxShadow='none'; this.style.transform='scale(1)'; this.style.fontWeight='550';"
        onclick="deleteFile()">
        Delete
      </button> -->

      <?php if($login_type == 1): ?>
      <button id="delete-selected-button"
        style="background-color: #404040; color: #a4a4ae; font-weight: 550; border: none; padding: 2px 25px; cursor: pointer; transition: background-color 0.15s, box-shadow 0.15s, color 0.15s, transform 0.15s, font-weight 0.15s; margin-top: 1vh; margin-left: 1vw; border-radius: 8px; height: 45px;"
        onmouseover="this.style.backgroundColor='#2db875'; this.style.color='#FFFFFF'; this.style.boxShadow='0 0 10px rgba(255, 255, 255, 0.5) inset'; this.style.transform='scale(1.05)'; this.style.fontWeight='650';"
        onmouseout="this.style.backgroundColor='#404040'; this.style.color='#a4a4ae'; this.style.boxShadow='none'; this.style.transform='scale(1)'; this.style.fontWeight='550';"
        onclick="deleteSelectedFiles()">
        Delete Selected
      </button>
      <?php endif; ?>

      <!-- The Modal -->
      <div id="customPrompt" class="tag-modal">
        <!-- Modal content -->
        <div class="tag-modal-content">
          <span class="tag-close" onclick="closeCustomPrompt()">&times;</span>
          <input type="text" id="newTagName" list="tagSuggestions" style="margin-top: 0vh; margin-left: 0vw; width: 22vw;" placeholder="Enter or search new tag"><br>
          <datalist id="tagSuggestions"></datalist>
          <button action="" onclick="submitTagChange()" style="margin-left: 17vw; margin-top: 15vh; margin-right: 10px; border-radius: 5px;">OK</button>
          <button onclick="closeCustomPrompt()" style="border-radius: 5px;">Cancel</button>
        </div>
      </div>
          
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" integrity="sha512-6pS7e3Z1Zzj+VgqCBunauB5ySNmS66FJ8XipBTJiR1XeHDI2VHyCzLp3/fYPV33vUNjGWJp6K/XzsZsFSNLjKA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <?php if ($login_type == 1): ?>
      <div class="search-print-container">
        <form id="searchForm" method="get" action="./index.php">
          <input type="hidden" name="page" value="user_right_view">
          <input type="text" id="search" name="search" class="search-input" autocomplete="off" placeholder="Search by tag" value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>">

        <!-- Search Button -->
        <button type="submit" class="search">
            <i class="fas fa-search"></i>
            Search
        </button>

        <!-- Static Dropdown for File Types -->
        
        <div class="custom-dropdown" style="margin-top: 15vh;">
          <div class="select-box" onclick="toggleDropdown()">Select to Exclude <span class="caret">&#9660;</span></div>
          <div id="checkboxs" class="checkboxes-containers">
            <form id="filterForm" method="get" action="index.php">
              <input type="hidden" name="page" value="user_right_view">
              <label for="all">
                <input type="checkbox" id="all" name="filetype[]" value="all" onclick="toggleAll(this)" /> All Files
              </label>
              <label for="images">
                <input type="checkbox" name="filetype[]" id="imagesCheckbox" value="jpg,jpeg,png,gif,JPG,JPEG,PNG,GIF"> Images
              </label>
              <label for="pdf">
                <input type="checkbox" name="filetype[]" id="pdfCheckbox" value="pdf"> PDFs
              </label>
              <label for="video">
                <input type="checkbox" name="filetype[]" id="videosCheckbox" value="mp4,mov,avi,flv,wmv,MP4,MOV,AVI,FLV,WMV"> Videos
              </label>
              
              <?php 
              $allowedFolders = ['Black Library', 'Testing']; 
              $directory = "./upload";
              $subfolders = array_filter(glob($directory . '/*'), 'is_dir');
              
              foreach ($subfolders as $subfolder):
                $folderName = basename($subfolder);
                if (in_array($folderName, $allowedFolders)):
              ?>
              
              <label for="<?php echo htmlspecialchars($folderName); ?>">
                <input type="checkbox" id="<?php echo htmlspecialchars($folderName); ?>" name="folder[]" value="<?php echo htmlspecialchars($folderName); ?>" />
                <?php echo htmlspecialchars($folderName); ?>
              </label>
              
              <?php 
                endif;
                endforeach;
              ?>
              <!-- <button type="submit"></button> -->
            </form>
          </div>
        </div>


        </form>
      </div>
      <?php endif; ?>
      
      
      <?php if($login_type != 1): ?>
        <div class="search-print-container" style="display: inline-block; margin-left: 30vw;">
          <form id="searchForm" method="get" action="./index.php">
            <input type="hidden" name="page" value="user_right_view">
            <input type="text" id="search" name="search" class="search-input" autocomplete="off" placeholder="Search by tag" value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>">
            <button type="submit" class="search">
              <i class="fas fa-search"></i>
              Search
      </button>
      
      <div class="custom-dropdown" style="margin-top: 15vh;">
        <div class="select-box" onclick="toggleDropdown()">Select to Exclude <span class="caret">&#9660;</span></div>
        <div id="checkboxs" class="checkboxes-containers">
          <form id="filterForm" method="get" action="index.php">
            <input type="hidden" name="page" value="user_right_view">
            <label for="all">
              <input type="checkbox" id="all" name="filetype[]" value="all" onclick="toggleAll(this)" /> All Files
            </label>
            <label for="images">
                <input type="checkbox" name="filetype[]" id="imagesCheckbox" value="jpg,jpeg,png,gif,JPG,JPEG,PNG,GIF"> Images
            </label>
            <label for="pdf">
                <input type="checkbox" name="filetype[]" id="pdfCheckbox" value="pdf"> PDFs
            </label>
            <label for="video">
                <input type="checkbox" name="filetype[]" id="videosCheckbox" value="mp4,mov,avi,flv,wmv,MP4,MOV,AVI,FLV,WMV"> Videos
            </label>
            <?php 
            $allowedFolders = ['Black Library', 'Testing']; 
            $directory = "./upload"; 
            $subfolders = array_filter(glob($directory . '/*'), 'is_dir');
            
            foreach ($subfolders as $subfolder):
              $folderName = basename($subfolder);
              if (in_array($folderName, $allowedFolders)):
            ?>
            <label for="<?php echo htmlspecialchars($folderName); ?>">
                <input type="checkbox" id="<?php echo htmlspecialchars($folderName); ?>" name="folder[]" value="<?php echo htmlspecialchars($folderName); ?>" />
                <?php echo htmlspecialchars($folderName); ?>
            </label>
            <?php 
              endif;
            endforeach;
            ?>
            <!-- <button type="submit"></button> -->
        </form>
    </div>
</div>
      </form> 
     </div>
     <?php endif; ?>



    
    </div><!-- class="botton-container" -->
    </ul>

    <ul class="navbar-nav ml-auto">
     
      <li class="nav-item">
        <a class="nav-link" data-widget="fullscreen" href="#" role="button">
          <i class="fas fa-expand-arrows-alt"></i>
        </a>
      </li>
     <li class="nav-item dropdown">
            <a class="nav-link"  data-toggle="dropdown" aria-expanded="true" href="javascript:void(0)">
              <span>
                <div class="d-felx badge-pill">
                  <span class="fa fa-user mr-2"></span>
                  
                  <span class="fa fa-angle-down ml-2"></span>
                </div>
              </span>
            </a>
            <div class="dropdown-menu" aria-labelledby="account_settings" style="left: -2.5em;">
              <!-- class="dropdown-item" href="javascript:void(0)" id="manage_account"><i class="fa fa-cog"></i> Manage Account</a> -->
              <a class="dropdown-item" href="javascript:void(0)" onclick="signIn()"><i class="fa fa-power-on"></i> SignIn</a>
              <a class="dropdown-item" href="ajax.php?action=logout"><i class="fa fa-power-off"></i> Logout</a>
            </div>
      </li>
    </ul>
  </nav>
  <!-- /.navbar -->
  <script>
      // Code for Signin start's from here
     $('#manage_account').click(function(){
        uni_modal('Manage Account','manage_user.php?id=<?php echo $_SESSION['login_id'] ?>')
      })

      function signIn() {
        window.location.href = 'login.php';
      }
      function signIn() {
        $.ajax({
          url: 'ajax.php?action=logout',
          type: 'POST',
          success: function(response) {
            // Check if the logout was successful and redirect to the login page
            window.location.href = 'login.php?admin=1';
          },
          error: function(error) {
            console.error("Logout failed", error);
            // Handle any errors that occur during logout
          }
        });
      }
      // Code for Signin end's here
         
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



    //For applying the filter on the search button start's from here 
    function toggleDropdown() {
        const checkboxesContainer = document.getElementById('checkboxs');
        checkboxesContainer.style.display = checkboxesContainer.style.display === 'block' ? 'none' : 'block';
    }

    function toggleAll(source) {
        const checkboxes = document.querySelectorAll('.checkboxes-containers input[type="checkbox"]');
        for (let i = 0; i < checkboxes.length; i++) {
            checkboxes[i].checked = source.checked;
        }
        updateResults();
    }

    document.addEventListener('DOMContentLoaded', function() {
        const checkboxes = document.querySelectorAll('.checkboxes-containers input[type="checkbox"]');
        checkboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                if (this.id === 'all' && !this.checked) {
                    document.getElementById('all').checked = false;
                }
                updateResults();
            });
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            const dropdown = document.querySelector('.custom-dropdown');
            if (!dropdown.contains(event.target)) {
                document.getElementById('checkboxs').style.display = 'none';
            }
        });
    });
   //For applying the filter on the search button end's here 

  </script>

</body>
</html>