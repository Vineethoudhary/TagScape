<!DOCTYPE html>
<html lang="en">
<?php 
session_start();
include('./db_connect.php');

// Define a default user ID for automatic login (for demonstration purposes)
$default_user_id = 2; // Assume this is the ID of your predefined "default" user

// Check if the 'admin' parameter is in the URL
if(isset($_GET['admin']) && $_GET['admin'] == 1) {
  // Admin login logic goes here
  ob_start();
  // if(!isset($_SESSION['system'])){

    $system = $conn->query("SELECT * FROM system_settings")->fetch_array();
    foreach($system as $k => $v){
      $_SESSION['system'][$k] = $v;
    }
  // }
  ob_end_flush();
} else {
  // Automatically log in the default user
  $_SESSION['login_id'] = $default_user_id;
  header("location:index.php?page=user_left_view");
  exit;
}

?>
<?php include 'header.php' ?>
<body class="hold-transition login-page" style="background-image: url('./upload/uploads/profile.jpg');">
<div class="login-box">
  <div class="login-logo">
  <a href="#" class="text-white"><b><?php echo isset($_SESSION['system']['name']) ? $_SESSION['system']['name'] : ''; ?> BES Consultants Pvt. Ltd.</b></a>
  </div>
  <!-- /.login-logo -->
  <div class="card">
    <div class="card-body login-card-body">
      <form action="" id="login-form">
        <div class="input-group mb-3">
          <input type="email" class="form-control" name="email" required placeholder="Email">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-envelope"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input type="password" class="form-control" name="password" required placeholder="Password">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-8">
            <div class="icheck-primary">
              <input type="checkbox" id="remember">
              <label for="remember">
                Remember Me
              </label>
            </div>
          </div>
          <!-- /.col -->
          <div class="col-4">
            <button type="submit" class="btn btn-primary btn-block">Sign In</button>
          </div>
          <!-- /.col -->
        </div>
      </form>
    </div>
    <!-- /.login-card-body -->
  </div>
</div>
<!-- /.login-box -->
<script>
  $(document).ready(function(){
    $('#login-form').submit(function(e){
    e.preventDefault()
    start_load()
    if($(this).find('.alert-danger').length > 0 )
      $(this).find('.alert-danger').remove();
    $.ajax({
      url:'ajax.php?action=login',
      method:'POST',
      data:$(this).serialize(),
      error:err=>{
        console.log(err)
        end_load();

      },
      success:function(resp){
        if(resp == 1){
          location.href ='index.php?page=left_view';
        }else if(resp == 3){
          location.href ='index.php?page=left_view';  
        }else{
          $('#login-form').prepend('<div class="alert alert-danger">Username or password is incorrect.</div>')
          end_load();
        }
      }
    })
  })
  })
</script>
<?php include 'footer.php' ?>

</body>
</html>
