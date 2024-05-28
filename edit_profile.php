<?php

include 'connect.php';

session_start();

   $user_id = $_SESSION['user_id'];

if(isset($_POST['submit'])){

   $name = $_POST['name'];

   $email = $_POST['email'];

   $pronouns = $_POST['pronouns'];

   $about = $_POST['about'];
   
   $date_of_birth = date('Y-m-d', strtotime($_POST['date_of_birth']));

   $username = $_POST['username'];

      $imageName = $_FILES["image"]["name"];
      $imageSize = $_FILES["image"]["size"];
      $tmpName = $_FILES["image"]["tmp_name"];

      if ($imageSize > 1200000){
        echo
        "
        <script>
          alert('Image Size Is Too Large');
          document.location.href = '../updateimageprofile';
        </script>
        ";
      }
      else{
        $newImageName = $name . " - " . date("Y.m.d") . " - " . date("h.i.sa"); // Generate new image name
        $newImageName .= '.' . $imageExtension;
        $upload = $conn->prepare("UPDATE users SET image = ? WHERE id = ?");
        $upload->execute([$newImageName,$user_id]);
        move_uploaded_file($tmpName, 'img/' . $newImageName);
        echo
        "
        <script>
        document.location.href = '../updateimageprofile';
        </script>
        ";
      }

   if(!empty($name)){
      $update_name = $conn->prepare("UPDATE `users` SET name = ? WHERE id = ?");
      $update_name->execute([$name, $user_id]);
   }

   if(!empty($email)){
      $select_email = $conn->prepare("SELECT * FROM `users` WHERE email = ?");
      $select_email->execute([$email]);
      if($select_email->rowCount() > 0){
         $message[] = 'email already taken!';
      }else{
         $update_email = $conn->prepare("UPDATE `users` SET email = ? WHERE id = ?");
         $update_email->execute([$email, $user_id]);
      }
   }
   if(!empty($username)){
    $update_username = $conn->prepare("UPDATE `users` SET username = ? WHERE id = ?");
    $update_username->execute([$username, $user_id]);
   }

   if(!empty($pronouns)){
    $update_pronouns = $conn->prepare("UPDATE `users` SET pronouns = ? WHERE id = ?");
    $update_pronouns->execute([$pronouns, $user_id]);
   }

   if(!empty($about)){
    $update_about = $conn->prepare("UPDATE `users` SET about = ? WHERE id = ?");
    $update_about->execute([$about, $user_id]);
   }
 }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
</head>
<body>
    <div class="header-container">
    <?php
            $select_profile = $conn->prepare("SELECT * FROM `users` WHERE id = ?");
            $select_profile->execute([$user_id]);
            if($select_profile->rowCount() > 0){
               $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
            }
         ?>
        <div class="header">
            <ul>
                <li><img src="" alt="pin-icon"></li>
                <li><img src="" alt="profile-icon"></li>
            </ul>
        </div>
    </div>

    <div class="title-container">
        <div class="title">
            <h1>ISKOLOG</h1>
        </div>
    </div>
    <form action="" method="post">
    <div class="display-container">
    <div class="profile">
    <input type="file" name="image" class="box" accept="image/jpg, image/jpeg, image/png, image/webp">
    <h2><?=$fetch_profile['name']; ?></h2>
    <input type="text" name="pronouns" placeholder="<?php if(isset($pronouns)) {echo $fetch_profile['pronouns'];} else { echo "pronouns"; } ?>"  >
    <br>
    <input type="text" name="about" placeholder="<?php if(isset($pronouns)) {echo $fetch_profile['about'];} else { echo "say something about yourself"; } ?>" >
    </div>
    </div>
    <div class="information-container">
        <div id="information">
        <h2>Full name</h2>
        <input type="text" name="name" placeholder="<?= $fetch_profile['name']; ?>" >
        <h2>Username</h2>
        <input type="text" name="username" placeholder="<?= $fetch_profile['username']; ?>" >
        <h2>Email</h2>
        <input type="text" name="email" placeholder="<?= $fetch_profile['email']; ?>" >
        <h2>Date of birth</h2>
        <input type="date" name="date_of_birth" value = ''>
        </div>
    </div>
    <div class="information-container">
        <div class="edit-btn">
            <button type = 'submit' name = 'submit' class ='btn'>SAVE</button>
        </div>
    </div>
</form>
    
</body>
</html>