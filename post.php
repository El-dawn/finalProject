
<?php

include 'connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
};

include 'like_posts.php';

$select_profile = $conn->prepare("SELECT * FROM `users` WHERE id = ?");
$select_profile->execute([$user_id]);
if($select_profile->rowCount() > 0){
    $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
}


$get_id = $_GET['post_id'];

if(isset($_POST['add_comment'])){

   $user_id = $_POST['user_id'];
   $user_name = $_POST['user_name'];
   $comment = $_POST['comment'];

   $verify_comment = $conn->prepare("SELECT * FROM `comments` WHERE post_id = ? AND user_id = ? AND user_name = ? AND comment = ?");
   $verify_comment->execute([$get_id, $user_id, $user_name, $comment]);

   if($verify_comment->rowCount() > 0){
      $message[] = 'comment already added!';
   }else{
      $insert_comment = $conn->prepare("INSERT INTO `comments`(post_id,  user_id, user_name, comment) VALUES(?,?,?,?)");
      $insert_comment->execute([$get_id, $user_id, $user_name, $comment]);
      $message[] = 'new comment added!';
   }

}

if(isset($_POST['edit_comment'])){
   $edit_comment_id = $_POST['edit_comment_id'];
   $comment_edit_box = $_POST['comment_edit_box'];

   $verify_comment = $conn->prepare("SELECT * FROM `comments` WHERE comment = ? AND id = ?");
   $verify_comment->execute([$comment_edit_box, $edit_comment_id]);

   if($verify_comment->rowCount() > 0){
      $message[] = 'comment already added!';
   }else{
      $update_comment = $conn->prepare("UPDATE `comments` SET comment = ? WHERE id = ?");
      $update_comment->execute([$comment_edit_box, $edit_comment_id]);
      $message[] = 'your comment edited successfully!';
   }
}

if(isset($_POST['delete_comment'])){
   $delete_comment_id = $_POST['comment_id'];
   $delete_comment = $conn->prepare("DELETE FROM `comments` WHERE id = ?");
   $delete_comment->execute([$delete_comment_id]);
   $message[] = 'comment deleted successfully!';
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Post</title>
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
                <img onclick="viewProfile()" src="img/<?php if(isset($fetch_profile)) {echo $fetch_profile['image'];} else { echo "default.png"; } ?>" alt="profile-icon">
            </div>
        </div>
    </div>

    <div class="back-container">
        <img onclick="window.history.back()" src="images/Vectorback.png" alt="back-icon">
    </div>

    <div class="main-post-container">
    <?php
        function convertdate($dateStr) {
            $date = DateTime::createFromFormat('Y-m-d', $dateStr);
    
            $formattedDate = $date->format('F d, Y');
        
            return $formattedDate;
        }

        $select_profile = $conn->prepare("SELECT * FROM `users` WHERE id = ?");
        $select_profile->execute([$user_id]);
        if($select_profile->rowCount() > 0){
            $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);}
        
         $select_posts = $conn->prepare("SELECT * FROM `posts` WHERE id = ?");
         $select_posts->execute([$get_id]);
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
        <div class="main-post-picture">
        <?php
                        if($fetch_posts['image'] != ''){  
                    ?>
                        <img src="imgpost/<?= $fetch_posts['image']; ?>" alt="Post Image">
                    <?php
                    }
                ?>
        </div>
        <div class="post-header">
            <h3 class="post-title"><?= $fetch_posts['title']; ?></h3>
            <div class="post-details">
                <h4 class="post-user"><?= $fetch_posts['name']; ?></h4>
                <h4 class="post-date"><?php echo convertdate($fetch_posts['date']); ?></h4>
            </div>
            <hr class="post-hr">
            <br>
        </div>
        <div class="content-post">
            <p><?= $fetch_posts['content']; ?></p>
        </div>
    </div>
    <?php
            $select_comments = $conn->prepare("SELECT * FROM `comments` WHERE post_id = ?");
            $select_comments->execute([$get_id]);

            if($select_comments->rowCount() > 0){
                while($fetch_comments = $select_comments->fetch(PDO::FETCH_ASSOC)){
                    $get_pfp = $conn->prepare("SELECT image FROM `users` WHERE id = ?");
                    $get_pfp->execute([$fetch_comments['user_id']]);
                    $user_pfp = $get_pfp->fetch(PDO::FETCH_ASSOC)['image'];
        ?>
    <div class="comment-container">
        <div class="comment">
            <div class="profile-picture">
                <img src="img/<?php echo $user_pfp; ?>" class="pfp" alt="<?php echo $fetch_comments['user_name'];?>">
            </div>
            <div class="comment-content">
                <h2><?= $fetch_comments['user_name']; ?></h2>
                <p><?= $fetch_comments['comment']; ?></p>
            </div>
    </div>
    </div>
    <?php
         }
        }
         ?>

    <div class="comment-container">
        <div class="like-comment-info">
            <form method = "post">
            <input type="hidden" name="post_id" value="<?= $post_id; ?>">
            <input type="hidden" name="user-id" value="<?= $fetch_posts['user_id']; ?>">
            <button class="like-button" type = 'submit' name="like_post">
                <img src="images/Vectorlike-icon.png" class="like" alt="like" style="<?php if($confirm_likes->rowCount() > 0){ echo 'color:blue opacity: 1'; }else{echo 'color:blue opacity: 0.4';} ?>">
            </button>
            </form>
            <p><?php echo $total_post_likes?></p> 
            
            <img src="images/Vectorcomment-icon.png" class="comment-icon" alt="comment-icon">
            <p><?php echo $total_post_comments?></p>
        </div>
        <div class="comment-btn">
            <div class="profile-picture">
                <img src="img/<?php if(isset($fetch_profile)) {echo $fetch_profile['image'];} else { echo "default.png"; } ?>" class="pfp" alt="pfp">
            </div>
            <form action="" method="post">
                <input type="hidden" name="user_id" value="<?= $fetch_profile['id']; ?>">
                <input type="hidden" name="user_name" value="<?= $fetch_profile['name']; ?>">
                <input class="cmt-textbox" type="text" name="comment" placeholder="Add comment">
                <button type="submit" value="add comment" name="add_comment">Post</button>
            </form>
        </div>
    </div>
    <?php
         }
      }else{
         echo '<p class="empty">no posts found!</p>';
      }
      ?>
</body>
</html>