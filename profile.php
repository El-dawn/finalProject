<?php

include 'connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
   header('location:login.php');
};

function convertdate($dateStr) {
    $date = DateTime::createFromFormat('Y-m-d', $dateStr);

    $formattedDate = $date->format('F d, Y');

    return $formattedDate;
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
        <img onclick="window.location.href='home.php'" src="images/Vectorback.png" alt="back-icon">
    </div>

    <br><br>

    <div class="profile-content">
        <div class="display-container">
            <div class="profile-container">
                <img src="img/<?php if(isset($fetch_profile)) {echo $fetch_profile['image'];} else { echo "default.png"; } ?>" alt="Profile picture">
            </div>
            <h1 class="username"><?= $fetch_profile['username']; ?></h1>
            <h2 class="name"><?=$fetch_profile['name']; ?></h2>
            <h2 class="pronoun"><?=$fetch_profile['pronouns']?></h2>
            <p class="bio"><?=$fetch_profile['about']?></p>
        </div>
        
        <div class="information-container">
            <div class="information">
                <div class="info-row">
                    <h1>Full Name</h1>
                    <h1><?= $fetch_profile['name']; ?></h1>
                </div>
                <hr>
                <div class="info-row">
                    <h1>Username</h1>
                    <h1><?= $fetch_profile['username']; ?></h1>
                </div>
                <hr>
                <div class="info-row">
                    <h1>Email</h1>
                    <h1><?= $fetch_profile['email']; ?></h1>
                </div>
                <hr>
                <div class="info-row">
                    <h1>Date Of Birth</h1>
                    <h1><?php echo convertdate($fetch_profile['date_of_birth']); ?></h1>
                </div>
            </div>
        </div>
    </div>

    <div class="editInformation-container">
        <div class="logout">
            <a href="logout.php">Logout</a>
        </div>
        <form action="edit_profile.php">
        <div class="edit-btn">
            <button type='submit' name='submit' class='btn'>EDIT</button>
        </div>
        </form>
    </div>

</body>
</html>
