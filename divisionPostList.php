<?php
include("dataconnection.php");
$division_id = $_SESSION['division_id'];
$topic_itemprice = "";

if (isset($_GET['divpost'])) 
{
	$choice = $_GET['divpost'];			    
}

//get topic base on user choice
if($choice == 'allpost')
{
	$sql_topic = "select topic.topic_id, topic.topic_title, topic.topic_timestamp, topic.topic_itemprice, topic.topic_status, user.user_name from topic inner join user on topic.user_id=user.user_id where topic.division_id='$division_id' order by topic.topic_timestamp desc";
}
else if($choice == 'mostcomment')
{
	$sql_topic = "select topic.topic_id, topic.topic_title, topic.topic_timestamp, topic.topic_itemprice, topic.topic_status, user.user_name from topic inner join user on topic.user_id=user.user_id where topic.division_id='$division_id' order by (select ifnull(count(comment.topic_id),0) from comment where comment.topic_id=topic.topic_id) desc";
}
else if(is_numeric($choice))
{
	$sql_topic = "select topic.topic_id, topic.topic_title, topic.topic_timestamp, topic.topic_itemprice, topic.topic_status, user.user_name from topic inner join user on topic.user_id=user.user_id where topic.division_id='$division_id' and topic.category_id='$choice' order by topic.topic_timestamp desc";
}


$topic = mysqli_query($conn,$sql_topic);

if(mysqli_num_rows($topic)<1)
{
	echo 'No Topic created yet.';
}
else
{
	while($row_topic=mysqli_fetch_assoc($topic)) 
	{
		if($division_id=='SHOP' && $row_topic['topic_status']=='') 
		{
		} 
		else
		{
			//get item price
			if($division_id=='SHOP')
			{
				$topic_itemprice = 'RM '.$row_topic['topic_itemprice'];
			}
			//get comment 
			$topic_id = $row_topic['topic_id'];
			$sql_comment = "select * from comment where topic_id='$topic_id'";
			$comment = mysqli_query($conn,$sql_comment);
			$comment_num = mysqli_num_rows($comment);
			if($comment_num>1)
			{
				$comment_num = $comment_num.' Comments';
			}else
			{
				$comment_num = $comment_num.' Comment';
			}
			//get topic category
			$sql_topiccategory = "select category.category_id, category.category from topic inner join category on topic.category_id=category.category_id where topic_id='$topic_id'";
			$topiccategory = mysqli_query($conn,$sql_topiccategory);
			$topic_category = mysqli_fetch_assoc($topiccategory);
			if($topic_category['category_id']<1)
			{
				$topic_category = 'NONE';
			}
			else
			{
				$topic_category = $topic_category['category'];
			}

			echo '
			<a href="topic.php?topic_id='.$row_topic['topic_id'].'" class="list-group-item">
			<p class="lead text-info text">'.$row_topic['topic_title'].'</p>
			<p><span>'.$topic_itemprice.'</span>
			<p><b>'.$row_topic['user_name'].'</b> | '.$row_topic['topic_timestamp'].' | <span class="label label-info">'.$topic_category.'</span><span class="badge pull-right">'.$comment_num.'</span></p>
			</a>		
			';
		}
	}
}


	
?>