<?php

include 'connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
};

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
    <title>Result</title>
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
                <img onclick="viewProfile()" src="img/<?php if($fetch_profile['image'] != '') { echo $fetch_profile['image'];} else{ echo 'default.png';}  ?>" alt="profile-icon">
            </div>
        </div>
    </div>

    <div class="back-container">
        <img onclick="window.history.back()" src="images/Vectorback.png" alt="back-icon">
    </div>


    <div class="search-container">
    <form action = 'results.php' method = "get" enctype="multipart/form-data">
        <div class="search">
        <input type="text" class="search-input" name="search-input" placeholder="Search">
            <input type="image" name="submit" src="images/Vectorsearch-icon.png" alt="Submit" class="search-icon">
        </div>
    </form>
    </div>    

    <div class="trendingposts-container">
        
        <?php
        function convertdate($dateStr) {
            $date = DateTime::createFromFormat('Y-m-d', $dateStr);
    
            $formattedDate = $date->format('F d, Y');
        
            return $formattedDate;
        }

         $search_box = $_GET['search-input'];
         $select_posts = $conn->prepare("SELECT * FROM `posts` WHERE title LIKE '%{$search_box}%' OR name LIKE '%{$search_box}%' ");
         $select_posts->execute();
         if($select_posts->rowCount() > 0){
            while($fetch_posts = $select_posts->fetch(PDO::FETCH_ASSOC)){
               
               $post_id = $fetch_posts['id'];

               $count_post_comments = $conn->prepare("SELECT * FROM `comments` WHERE post_id = ?");
               $count_post_comments->execute([$post_id]);
               $total_post_comments = $count_post_comments->rowCount(); 

               $count_post_likes = $conn->prepare("SELECT * FROM `likes` WHERE post_id = ?");
               $count_post_likes->execute([$post_id]);
               $total_post_likes = $count_post_likes->rowCount();

               $confirm_likes = $conn->prepare("SELECT * FROM `likes` WHERE user_id = ? AND post_id = ?");
               $confirm_likes->execute([$user_id, $post_id]);
        
      ?>
      <div class="trendingposts">
      <form class= "" method = "post">
            <div class="post">
                <?php
                if($fetch_posts['image'] != ''){  
                ?>   
                    <img src="imgpost/<?= $fetch_posts['image']?>"; alt="Post Image">
                    <?php
                    }
                    else{
                        echo '<img src="imgpost/default.jpeg">';
                    }
                ?>
                        <div class="post-info">
                        <h2 class="title"><?= $fetch_posts['title']; ?></h2>
                        <span class="user"><?= $fetch_posts['name']; ?></span>
                        <p class="description"><?= $fetch_posts['content']; ?></p>
                                            <a href="post.php?post_id=<?= $post_id; ?>" class="inline-btn">read more</a>
                        <div class="metadata">
                            <span class="trend-comments"><?php echo $total_post_comments ?> comments</span>
                            <span class="trend-likes"><?php echo $total_post_likes?> likes</span>
                            <span class="trend-date"><?php echo convertdate($fetch_posts['date']); ?></span>
                        </div>
                    </div>
                </div>
                </form>       
             </div>
             <hr>
             <?php
            }
         }else{
            echo '<p class="empty">no posts aded yet!</p>';
        }
        ?>
        </div>

    <div class="createpost-container">
        <div class="createpost-btn">
            <button onclick="createPost()">+ CREATE POST</button>
        </div>
    </div>    
     
</body>
</html>