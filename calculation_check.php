<?php 
include 'db-connect-reports.php';
?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- <title>image & pfd's upload</title> -->
    <link rel="stylesheet" type="text/css" href="css/calculation_check.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
</head>

<body background="black">

<div class="row"> 
<!-- *****LEFTSIDE MENU STARTS FROM HERE***** -->
<div class="column1">
    <div class="formdesign" style="
        position: absolute;
        Left: 77.5vw; /* Adjust as needed */
        top: 0vh; /* Adjust as needed */
        width: 22vw; /* Adjust as needed */
        height: 85vh; /* Adjust as needed */
        border-radius: 1.5vw;
        box-shadow: 0.5vw 0.5vh 1vh rgba(0, 0, 0, 0.3);
        margin-left: -124vh;">
       <form action="" method="POST" enctype="multipart/form-data">
        <div class="center">
            <input type="file" id="fileInput" name="excelFile" multiple style="display: none;">
            <div id="dropArea" ondrop="handleDrop(event)" ondragover="handleDragOver(event)" ondragenter="handleDragEnter(event)" ondragleave="handleDragLeave(event)">
              <p style="color: #c1bdbd; text-shadow: 0.01vw 0.01vw 0.02vw black;"> Drag and drop files here, or <a href="#" onclick="browseFiles()" style="color: lemonchiffon;">browse</a> to select files.</p>
        </div> <!--class = center -->
        <input type="submit" value="Submit" style="margin-top: 2vh; padding: 0.5rem 1rem; background-color: #5cb85c; color: white; border-radius: 0.5rem; border: none; cursor: pointer;">
    </div>
    </form>
    </div><br> <br> <!-- class = formdesign -->
</div><!-- class = column1 -->
<!-- *****LEFTSIDE MENU END HERE***** -->



<!-- *****RIGHTSIDE MENU STARTS FROM HERE***** -->
<div class="column" style="
    background-color: transparent; 
    margin-left: 5vw;
    margin-right: 50vw;">
    <div class="roll" style="overflow-y: auto;
        height:85vh;
        width:57vw;
        background: linear-gradient(90deg, rgba(39,38,38,1) 1%, rgba(38,39,39,0.8883928571428571) 100%, rgba(60,59,59,0.6811099439775911) 100%, rgba(59,58,58,1) 100%, rgba(193,189,189,0.4766281512605042) 100%, rgba(64,63,63,1) 100%, rgba(72,70,70,1) 100%);
        margin-top: -60vh;
        border-radius: 1vw;
        box-shadow: 2vw 2vw 4vw rgba(0,0,0,0.6);
        margin-left: 16vw;">
        
        <h3 style="background: #2874A6;
        -webkit-text-fill-color:linear-gradient(90deg, rgba(39,38,38,1) 1%, rgba(6,56,82,1) 47%, rgba(0,0,0,0.9612219887955182) 100%, rgba(60,59,59,0.6811099439775911) 100%, rgba(59,58,58,1) 100%, rgba(193,189,189,0.4766281512605042) 100%, rgba(64,63,63,1) 100%, rgba(72,70,70,1) 100%);
        border-radius: 1vw;
        display: inline-block;
        font-weight: bold;
        font-size: 1vw;
        margin-top: 3vh;
        margin-bottom:-3vh;
        width: 14vw;
        margin-left: 1vw;
        box-shadow: 0 0 1vw rgba(255, 255, 255, 0.5);
        padding: 1vw;
        color: floralwhite;
        box-shadow: 0vw 0vw 0.25vw black, 0 0 0.5vw blue, 0 0 1.25vw white;">CALCULATION CHECK</h3>        
    <div class="box" style="
        height: 40vh;
        width: 52vw;
        background-color: #f1f1f1;
        border-radius: 1vw;
        margin-top: 6vh;
        margin-left: 2vw;">
    </div>

    <div id="myModal" class="modal">
        <!-- The Close Button -->
        <span class="close">&times;</span>
        <!-- Modal Content (The Image) -->
        <img class="modal-content" id="img01">
    </div>

    <div class="box" style="
        height: 28vh;
        width: 52vw;
        background-color: #f1f1f1;
        border-radius: 1vw;
        margin-top: 2vh;
        margin-left: 2vw;">
    </div>
</div> 
</div>
<!-- *****RIGHTSIDE MENU END'S HERE***** -->

</div> <!-- div class row is ending here -->


<script>
    
    //*****for drag and drop the images -->starts from here*****
    function handleDrop(event) {
    event.preventDefault();
    var fileInput = document.getElementById("fileInput");
    fileInput.files = event.dataTransfer.files;
    document.getElementById("dropArea").classList.remove("dragover");
    if(fileInput.files.length > 0) {
        window.alert(fileInput.files.length + " file's selected!");
    }
}
    function handleDragOver(event) {
        event.preventDefault();
        document.getElementById("dropArea").classList.add("dragover");
    }

    function handleDragEnter(event) {
        event.preventDefault();
        document.getElementById("dropArea").classList.add("dragover");
    }

    function handleDragLeave(event) {
        event.preventDefault();
        document.getElementById("dropArea").classList.remove("dragover");
    }
    // for drag and drop the images end's here

    function browseFiles() {
        var fileInput = document.getElementById("fileInput");
        fileInput.click();
    }
</script>


</body>
</html>