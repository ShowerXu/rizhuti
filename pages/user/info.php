<?php 
global $current_user;
?>
<div class="info-wrap">
	<form action="" method="post" class="user-usermeta-form" style="">

		<div class="inner">
			
			<div class="form-group">
				<label class="control-label">用户ID</label>
				<input style="cursor: no-drop;" type="text" class="form-control" name="username" required="" value="<?php echo $current_user->user_login;?>" disabled="disabled">
			</div>
			<div class="form-group">
                <label class="control-label">用户昵称</label>
				<input type="text" class="form-control" name="nickname" required="" placeholder="请输入用户昵称" value="<?php echo $current_user->nickname;?>">
            </div>

            <div class="form-group">
            	<label class="control-label">邮箱</label>
        		<div class="col-sm-4">
        			<input type="email" class="form-control" name="user_email" required="" placeholder="" value="<?php echo $current_user->user_email;?>" >
                </div>
            </div>
            <!-- 绑定QQ -->
			<?php if( _hui('is_oauth_qq',false)) { ?>
				<div class="form-group">
	                <div class="col-sm-4">
						<div class="qq-card">
							<i class="iconfont">&#xe81f;</i>
							<?php 
								$open_bind =get_user_meta($current_user->ID, 'open_bind',true );
								$qq_name = get_user_meta($current_user->ID, 'qq_name',true );
								if ($open_bind == '1') {
				                	echo '<span>已绑定（'.$qq_name.'）</span><a href="javascript: void(0);" class="bind-qq" id="unset-bind-qq">解除绑定</a>';
				                }else{
				                	$bind_url = get_stylesheet_directory_uri() . '/oauth/qq?rurl='.home_url().'/user?action=info';
									echo '<a href="'.$bind_url.'" class="bind-qq">绑定</a>';
				                }
			                ?>
							
						</div>
	                </div>
	            </div>
			<?php } ?>
            
            <div class="form-group">
				<div class="col-sm-10 col-sm-offset-2">
					<input type="hidden" name="action2" value="1">
					<button type="submit" class="btn btn-primary btn-lg ladda-button" id="user-action-info"><span class="ladda-label">提交</span></button>
                </div>
            </div>
		</div>

	</form>
</div>