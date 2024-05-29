<?php

include 'connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
   header('location:login.php');
};

if(isset($_POST['submit'])){

    $name = $_POST['name'];
    $email = $_POST['email'];
    $pronouns = $_POST['pronouns'];
    $about = $_POST['about'];
    $date_of_birth = date('Y-m-d', strtotime($_POST['date_of_birth']));
    $username = $_POST['username'];
 
 
    if(isset($_FILES['test']) && $_FILES['test']['error'] != 4){
       $fileName = $_FILES['test']['name'];
       $fileSize = $_FILES['test']['size'];
       $tmpName = $_FILES['test']['tmp_name'];
 
       $validImageExtension = ['jpg', 'jpeg', 'png'];
       $imageExtension = explode('.', $fileName);
       $imageExtension = strtolower(end($imageExtension));
       if (!in_array($imageExtension, $validImageExtension)) {
          echo "<script>alert('Invalid Image Extension $imageExtension');</script>";
       } else if ($fileSize > 1000000) {
          echo "<script>alert('Image Size Is Too Large');</script>";
       } else {
          $newImageName = uniqid() . '.' . $imageExtension;
          $upload = $conn->prepare("UPDATE users SET image = ? WHERE id = ?");
          $upload->execute([$newImageName, $user_id]);
          move_uploaded_file($tmpName, 'img/' . $newImageName);
          echo "<script>alert('Successfully Added'); document.location.href = 'home.php';</script>";
       }
    } else {
       echo "<script>alert('Image Does Not Exist');</script>";
    }
 
    if (!empty($name)) {
       $update_name = $conn->prepare("UPDATE `users` SET name = ? WHERE id = ?");
       $update_name->execute([$name, $user_id]);
    }
 
    if (!empty($email)) {
       $select_email = $conn->prepare("SELECT * FROM `users` WHERE email = ?");
       $select_email->execute([$email]);
       if ($select_email->rowCount() > 0) {
          echo "<script>alert('Email already taken!');</script>";
       } else {
          $update_email = $conn->prepare("UPDATE `users` SET email = ? WHERE id = ?");
          $update_email->execute([$email, $user_id]);
       }
    }
 
    if (!empty($username)) {
       $update_username = $conn->prepare("UPDATE `users` SET username = ? WHERE id = ?");
       $update_username->execute([$username, $user_id]);
    }
 
    if (!empty($pronouns)) {
       $update_pronouns = $conn->prepare("UPDATE `users` SET pronouns = ? WHERE id = ?");
       $update_pronouns->execute([$pronouns, $user_id]);
    }
 
    if (!empty($about)) {
       $update_about = $conn->prepare("UPDATE `users` SET about = ? WHERE id = ?");
       $update_about->execute([$about, $user_id]);
    }
 
    if (!empty($date_of_birth)) {
       $update_dob = $conn->prepare("UPDATE `users` SET date_of_birth = ? WHERE id = ?");
       $update_dob->execute([$date_of_birth, $user_id]);
    }
 }
 ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link rel="stylesheet" href="styles.css">
    <script src="script.js" defer></script>
</head>
<body>

    <div class="create-post-header-container">
    <?php
         $select_profile = $conn->prepare("SELECT * FROM `users` WHERE id = ?");
         $select_profile->execute([$user_id]);
         if($select_profile->rowCount() > 0){
            $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
         }
      ?>
        <div class="create-post-header">
            <a href="home.php"><h1 class="create-post-title">
                <span class="create-post-isko">ISKO</span><span class="create-post-log">LOG</span>
            </h1></a>
        </div>
    </div>

    <div class="back-container">
        <img onclick="window.history.back()" src="images/Vectorback.png" alt="back-icon">
    </div>

    <br><br>

    <div class="profile-content">
    <form action="" method="post" enctype="multipart/form-data"> 
        <div class="display-container">
            <div class="profile-container">
                <img src="img/<?php if(isset($fetch_profile)) {echo $fetch_profile['image'];} else { echo "default.png"; } ?>" alt="Profile picture">
                <input type="file" name="test" id="image" accept=".jpg, .jpeg, .png" value="">
            </div>
            <h1 class="username"><?= $fetch_profile['username']; ?></h1>
            <h2 class="name"><?=$fetch_profile['name']; ?></h2>
            <h2 class="pronoun"><input type="text" name="pronouns" placeholder="<?php if(isset($pronouns)) {echo $fetch_profile['pronouns'];} else { echo "pronouns"; } ?>"  ></h2>
            <p class="bio"><input type="text" name="about" placeholder="<?php if(isset($about)) {echo $fetch_profile['about'];} else { echo "say something about yourself"; } ?>" ></p>
        </div>
        
        <div class="information-container">
            <div class="information">
                <div class="info-row">
                    <h1>Full Name</h1>
                    <input type="text" name="name" placeholder="<?= $fetch_profile['name']; ?>" >
                </div>
                <hr>
                <div class="info-row">
                    <h1>Username</h1>
                    <input type="text" name="username" placeholder="<?= $fetch_profile['username']; ?>" >
                </div>
                <hr>
                <div class="info-row">
                    <h1>Email</h1>
                    <input type="text" name="email" placeholder="<?= $fetch_profile['email']; ?>" >
                </div>
                <hr>
                <div class="info-row">
                    <h1>Date Of Birth</h1>
                    <input type="date" name="date_of_birth" value="<?= $fetch_profile['date_of_birth']; ?>">
                </div>
            </div>
        </div>
    </div>
    </form>

    <div class="editInformation-container">
    <form action="profile.php" method="post">
    <div class="edit-btn">
            <button type = 'submit' name = 'submit' class ='btn'>SAVE</button>
    </div>
    </form>
    </div>


</body>
</html>
