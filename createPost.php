<?php

include 'connect.php';

session_start();

$user_id = $_SESSION['user_id'];

if(!isset($user_id)){
   header('location:login.php');
   exit;
}

if(isset($_POST['publish'])){

   $name = $_POST['name'];
   $title = $_POST['title'];
   $content = $_POST['content'];

   if(isset($_FILES['image']) && $_FILES['image']['error'] == 0){
      $fileName = $_FILES['image']['name'];
      $fileSize = $_FILES['image']['size'];
      $tmpName = $_FILES['image']['tmp_name'];

      $validImageExtension = ['jpg', 'jpeg', 'png'];
      $imageExtension = explode('.', $fileName);
      $imageExtension = strtolower(end($imageExtension));
      if (!in_array($imageExtension, $validImageExtension)) {
         echo "<script>alert('Invalid Image Extension $imageExtension');</script>";
         $image = '';
      } else if ($fileSize > 1000000) {
         echo "<script>alert('Image Size Is Too Large');</script>";
         $image = '';
      } else {
         $newImageName = uniqid() . '.' . $imageExtension;
         move_uploaded_file($tmpName, 'imgpost/' . $newImageName);
         $image = $newImageName;
      }
   } else {
      $image = '';
   }

   $insert_post = $conn->prepare("INSERT INTO `posts` (user_id, name, title, content, image) VALUES (?, ?, ?, ?, ?)");
   $insert_post->execute([$user_id, $name, $title, $content, $image]);
   echo "<script>alert('Post Published');</script>";
   header('location:home.php');
}
$select_profile = $conn->prepare("SELECT * FROM `users` WHERE id = ?");
$select_profile->execute([$user_id]);
if($select_profile->rowCount() > 0){
    $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Post</title>
    <link rel="stylesheet" href="styles.css">
    <script src="script.js" defer></script>
</head>
<body>
   <div class="create-post-header-container">
        <div class="create-post-header">
            <a href="home.php"><h1 class="create-post-title">
                <span class="create-post-isko">ISKO</span><span class="create-post-log">LOG</span>
            </h1></a>
            <div class="create-post-nav-icons">
                <img onclick="viewProfile()" src="img/<?php if($fetch_profile['image'] != '') { $fetch_profile['image'];} else{ echo 'default.png';}  ?>" alt="profile-icon">
            </div>
        </div>
    </div>

    <div class="back-container">
        <img onclick="window.history.back()" src="images/Vectorback.png" alt="back-icon">
    </div>

    <br><br>

    <div class="create-text">
        <h3>Create A Post</h3>
    </div>
    <form action="" method="post" enctype="multipart/form-data">
        <?php
        $select_profile = $conn->prepare("SELECT * FROM `users` WHERE id = ?");
        $select_profile->execute([$user_id]);
        if($select_profile->rowCount() > 0){
            $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
        }
        ?>
        <input type="hidden" name="name" value="<?= $fetch_profile['name']; ?>">
        <div class="titletext-container">
            <input type="text" placeholder="Enter title here" name="title" required>
        </div>
        <br>

        <div class="contenttext-container">
            <div class="upload-image">
                <input type="file" name="image" id="image" accept=".jpg, .jpeg, .png">
            </div>
            <textarea name="content" id="content" cols="30" rows="10" placeholder="Enter content here" required></textarea>
        </div>

        <div class="publish-container">
            <button type="submit" name="publish">PUBLISH</button>
        </div>
    </form>

    <script src="script.js"></script>
</body>
</html>
