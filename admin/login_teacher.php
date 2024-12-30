<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/qc/admin/inc/dbcon.php');
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Title -->
    <title>강사 로그인 - quantumcode</title>

    <!-- Favicon -->

    <!-- Core Style CSS -->
    <link rel="stylesheet" href="http://<?=$_SERVER['HTTP_HOST'];?>/qc/admin/css/core-style.css">
    <link rel="stylesheet" href="http://<?=$_SERVER['HTTP_HOST'];?>/qc/admin/css/login.css">

    <!-- Bootstrap, jQuery -->
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.14.0/themes/base/jquery-ui.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
  </head>
<body class="teacher_login_php">

  <div class="d-flex">
    <aside>
      <div class="copy">
        <h2>Connect your Dream with Our passion</h2>
        <h3>everything you can imagine is can be possible with us</h3>
      </div>
    </aside>

    <div class="login_container d-flex flex-column justify-content-center align-items-center">
      <h1 class="main_tt text-center mb-4">Log in to your<br>Account</h1>
      <div class="d-flex justify-content-between login_sns_teacher">
        <button class="login_google">Google</button>
        <button class="login_kakaotalk">KaKaoTalk</button>
      </div>
      <div class="divider">
        <span>Or Continue With Email</span>
      </div>
      <div class="row login_box">
        <form action="login_ok_teacher.php" method="POST">
          <div class="form-floating">
            <input type="text" class="form-control" id="id" name="id" placeholder="teacher ID">
            <label for="id">teacher ID</label>
          </div>
          <div class="form-floating">
          <input type="password" class="form-control" id="password" name="password" placeholder="password">
            <label for="password">Password</label>
          </div>
          <div class="d-flex justify-content-between">
            <div>
              <p class="mb-0"><a href="#" class="forgotpw">Join Us</a></p>
              <p><a href="#" class="forgotpw">Forgot Password?</a></p>
            </div>
            <p class="mt-4 mb-4"><a href="login.php" class="login_change">Log in to Admin</a></p>
          </div>
          <button class="btn btn-primary">Log in</button>
        </form>
      </div>
  </div>
    
  </div>

  
<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/qc/admin/inc/footer.php');
?>
