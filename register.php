<?php

include 'connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
};

if(isset($_POST['submit'])){

   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_STRING);
   $pass = sha1($_POST['pass']);
   $pass = filter_var($pass, FILTER_SANITIZE_STRING);
   $cpass = sha1($_POST['cpass']);
   $cpass = filter_var($cpass, FILTER_SANITIZE_STRING);

   $select_user = $conn->prepare("SELECT * FROM `users` WHERE email = ?");
   $select_user->execute([$email]);
   $row = $select_user->fetch(PDO::FETCH_ASSOC);

   if($select_user->rowCount() > 0){
      $message[] = 'email already exists!';
   }else{
      if($pass != $cpass){
         $message[] = 'confirm password not matched!';
      }else{
         $insert_user = $conn->prepare("INSERT INTO `users`(name, email, password) VALUES(?,?,?)");
         $insert_user->execute([$name, $email, $cpass]);
         $select_user = $conn->prepare("SELECT * FROM `users` WHERE email = ? AND password = ?");
         $select_user->execute([$email, $pass]);
         $row = $select_user->fetch(PDO::FETCH_ASSOC);
         if($select_user->rowCount() > 0){
            $_SESSION['user_id'] = $row['id'];
            header('location: edit_profile.php');
         }
      }
   }

}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Form</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body class="Login_Field">
<div class="container" style="margin-top: 5%;">
<div class="title-container">
            <h1 class="title">
                <span class="isko">ISKO</span><span class="log">LOG</span>
            </h1>
        </div>
        <div class="desc-container">
            <p class="desc">UPV's Blogging Platform</p>
        </div>
        <div class="login-container">
        <form action="" method="post">
            <h1>Register</h1>
            <div class="input-box">                
                <input type="text" name= "name" placeholder="Enter name" required>
            </div>
            <div class="input-box">                
                <input type="text" name= "email" placeholder="Enter email" required>
            </div>
            <div class="input-box">
                <input type="password" name= "pass" placeholder="Enter password" required>
            </div>
            <div class="input-box">
                <input type="password" name= "cpass" placeholder="Confirm Password" required>
            </div>
            <button type="submit" name= "submit" class="btn">Register Now</button>
        </form>
</div>
    </div>
</body>
</html> 