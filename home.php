<?php

include 'connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
};

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link rel="stylesheet" href="styles.css">
    <script src="script.js" defer></script>
</head>
<body>
    <div class="header-container">
        <div class="header">
        <?php
            $select_profile = $conn->prepare("SELECT * FROM `users` WHERE id = ?");
            $select_profile->execute([$user_id]);
            if($select_profile->rowCount() > 0){
                $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
            }
            $count_user_comments = $conn->prepare("SELECT * FROM `comments` WHERE user_id = ?");
            $count_user_comments->execute([$user_id]);
            $total_user_comments = $count_user_comments->rowCount();
            $count_user_likes = $conn->prepare("SELECT * FROM `likes` WHERE user_id = ?");
            $count_user_likes->execute([$user_id]);
            $total_user_likes = $count_user_likes->rowCount();
         ?>
            <div class="spacer"></div>
            <table class="nav-icons">
                <tr>
                    <td><img onclick="viewProfile()" src="img/<?php if($fetch_profile['image'] != '') { $fetch_profile['image'];} else{ echo 'default.png';}  ?><?php if(isset($fetch_profile)) {echo $fetch_profile['image'];} else { echo "default.png"; } ?>" alt=""></td>
                </tr>
            </table>
        </div>
    </div>


    <div class="title-container">
        <h1 class="title">
            <span class="isko">ISKO</span><span class="log">LOG</span>
        </h1>
    </div>


    <div class="search-container">
        <form action = 'results.php' method = "get" enctype="multipart/form-data">
        <div class="search">
            <input type="text" class="search-input" name="search-input" placeholder="Search">
            <input type="image" name="submit" src="images/Vectorsearch-icon.png" alt="Submit" class="search-icon">
        </div>
        </form>
    </div>    
  
    <div class="trending-header">
        <h2>Trending</h2>
    </div>

    <div class="trendingposts-container">
        <?php
        function convertdate($dateStr) {
            $date = DateTime::createFromFormat('Y-m-d', $dateStr);
    
            $formattedDate = $date->format('F d, Y');
        
            return $formattedDate;
        }
            $select_posts = $conn->prepare("SELECT * FROM `posts` ORDER BY likes DESC");
            $select_posts->execute();
        ?>
        <?php
            if($select_posts->rowCount() > 0){
                while($fetch_posts = $select_posts->fetch(PDO::FETCH_ASSOC)){

                    $post_id = $fetch_posts['id'];

                    $count_post_comments = $conn->prepare("SELECT * FROM `comments` WHERE post_id = ?");
                    $count_post_comments->execute([$post_id]);
                    $total_post_comments = $count_post_comments->rowCount(); 
     
                    $count_post_likes = $conn->prepare("SELECT * FROM `likes` WHERE post_id = ?");                            
                    $count_post_likes->execute([$post_id]);
                    $total_post_likes = $count_post_likes->rowCount();

                    $update_like = $conn->prepare("UPDATE `posts` SET likes = ? WHERE id = ?");
                    $update_like->execute([$total_post_likes, $post_id]);
         
                    $confirm_likes = $conn->prepare("SELECT * FROM `likes` WHERE user_id = ? AND post_id = ?");
                    $confirm_likes->execute([$user_id, $post_id]);
                    ?>

                <div class="trendingposts">
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
                <form class="box" method="get">

                    <h2 class="title"><?= $fetch_posts['title']; ?></h2>
                    <span class="user"><?= $fetch_posts['name']; ?></span>
                    <p class="description"><?= $fetch_posts['content']; ?></p>
                    <a href="post.php?post_id=<?= $post_id; ?>" class="inline-btn">read more</a>
                    <div class="metadata">
                        <input type="hidden" name="post_id" value= <?php echo $post_id;?>>
                        <span class="trend-comments"><?php echo $total_post_comments?> comments</span>
                        <span class="trend-likes"><?php echo $total_post_likes?> likes</span>
                        <span class="trend-date"><?php echo convertdate($fetch_posts['date']); ?></span>
                    </div>
                    </form>
                </div>
            </div>                            
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
        <div class="createpost">
            <button onclick="createPost()">+ CREATE POST</button>
        </div>
    </div>
    
</body>
</html>