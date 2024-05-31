<?php
include 'connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
}

if(isset($_POST['submit'])){
   $user = $_POST['username'];
   $pass = sha1($_POST['password']);

   if (filter_var($user, FILTER_VALIDATE_EMAIL)) {
      $email = $user;
      $select_user = $conn->prepare("SELECT * FROM `users` WHERE email = ? AND password = ?");
      $select_user->execute([$email, $pass]);
   } else {
      $name = $user;
      $select_user = $conn->prepare("SELECT * FROM `users` WHERE username = ? AND password = ?");
      $select_user->execute([$name, $pass]);
   }

   $row = $select_user->fetch(PDO::FETCH_ASSOC);

   if($select_user->rowCount() > 0){
      $_SESSION['user_id'] = $row['id'];
      $message[] = 'Login successful';
      header('location:home.php');
      exit; 
   } else {
      $message[] = 'Incorrect username or password!';
   }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container" style="margin-top: 10%;">
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
                <input type="text" name="username" placeholder="Username/Email" required><br>
                <input type="password" name="password" placeholder="Password" required><br>
                <button class="submit" type="submit" name="submit">Sign in</button>
                <p>Don't have an account? <a href="register.php">Register here</a></p>
            </form>
            <?php
            if(isset($message)){
                foreach($message as $msg){
                    echo '<p class="message">'.$msg.'</p>';
                }
            }
            ?>
        </div>
    </div>
</body>
</html>
