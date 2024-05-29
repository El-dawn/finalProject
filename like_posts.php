<?php

if(isset($_POST['like_post'])){

   if($user_id != ''){
      
      $post_id = $_POST['post_id'];
      $user_post_id = $_POST['user-id'];
      
      $select_post_like = $conn->prepare("SELECT * FROM `likes` WHERE post_id = ? AND user_id = ?");
      $select_post_like->execute([$post_id, $user_post_id]);

      if($select_post_like->rowCount() > 0){
         $remove_like = $conn->prepare("DELETE FROM `likes` WHERE post_id = ?");
         $remove_like->execute([$post_id]);
         $message[] = 'removed from likes';
      }else{
         $add_like = $conn->prepare("INSERT INTO `likes`(user_id, post_id) VALUES(?,?)");
         $add_like->execute([$user_post_id, $post_id]);
         $message[] = 'added to likes';
      }
      
   }else{
         $message[] = 'please login first!';
   }

}

?>