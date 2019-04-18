<?php 
/**
 * template name: 用户中心(已购买记录)
 */
date_default_timezone_set('Asia/Shanghai');
if(!is_user_logged_in()){
	header("Location:".home_url('login'));
	exit();
}
get_header();
global $current_user;

$user_downData = this_vip_downum();


?>

<section class="container">
<!-- Page Layout here -->
<div class="row">

  <div class="col s12 m2 12">
	<div class="user-nav">

		<ul>
			<li>
				<div class="user-nav-avatar">
					<?php echo _get_user_avatar( $user_email, true, 100); ?>
						<form action="<?php echo get_bloginfo('template_url');?>/action/avatar.php" method="post" class="" role="form" name="AvatarForm" id="AvatarForm"  enctype="multipart/form-data">
						<a class="btn btn-default btn-sm upload" href="javascript:void(0)"><span id="udptips">修改头像</span>
						<input type="file" name="addPic" id="addPic" accept=".jpg, .gif, .png" resetonclick="true">
						</a>
						</form>
					<script src="<?php echo get_bloginfo('template_url');?>/js/jquery.form.js"></script>
				</div>
			</li>
			<li>
				<p><?php echo $current_user->user_login;?></p><span><?php echo vip_type_name();?></span>
				<div class="row downinfo">
					<span>已下<?php echo $user_downData['today_down_num']; ?></span>
					<span>剩余<?php echo $user_downData['over_down_num']; ?></span>
				</div>
			</li>
			<li><a href="?action=order" class="order <?php if($_GET['action'] == 'order') echo 'active';?>" etap="order">我的订单</a></li>
			<li><a href="?action=vip" class="vip <?php if($_GET['action'] == 'vip') echo 'active';?>" etap="vip">会员特权</a></li>
			<li><a href="?action=info" class="info <?php if($_GET['action'] == 'info') echo 'active';?>" etap="info">我的信息</a></li>
			<li><a href="?action=comment" class="comments <?php if($_GET['action'] == 'comment') echo 'active';?>" etap="comment">我的评论</a></li>
			<?php if (_hui('is_write')) { ?>
			<li><a href="?action=mywrite" class="mywrite <?php if($_GET['action'] == 'mywrite') echo 'active';?>" etap="mywrite">我的文章</a></li>
			<li><a href="?action=write" class="write <?php if($_GET['action'] == 'write') echo 'active';?>" etap="write">投稿</a></li>
			<?php } ?>
			<li><a href="?action=password" class="password <?php if($_GET['action'] == 'password') echo 'active';?>" etap="password">修改密码</a></li>
		</ul>
	</div>

  </div>

  <div class="col s12 m10 18">

		<!-- 账户信息 -->
		<?php if (isset($_GET['action'])) {
			$part_action = $_GET['action'];
			get_template_part( 'pages/user/'.$part_action);
		}else{ 
			get_template_part( 'pages/user/index');
		} ?>


  </div>

</div>

</section>



<?php get_footer(); ?>