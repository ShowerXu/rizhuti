<?php 
global $current_user;
?>
<div class="info-wrap">
	<form action="" method="post" class="user-usermeta-form" style="">
		<div class="inner">
			<div class="form-group">
				<label class="control-label">输入新密码</label>
				<input type="password" class="form-control" placeholder="请输入6位以上密码" required="" minlength="6" name="password">
			</div>
			<div class="form-group">
                <label class="control-label">重复新密码</label>
				<input type="password" class="form-control" required="" minlength="6" name="password2">
            </div>

            <div class="form-group">
				<div class="col-sm-10 col-sm-offset-2">
					<input type="hidden" name="action2" value="3">
					<button type="submit" class="btn btn-primary btn-lg ladda-button" id="user-action-paw"><span class="ladda-label">修改</span></button>
                </div>
            </div>
		</div>
	</form>
</div>