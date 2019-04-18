<?php 
global $current_user;
?>
<div class="write-wrap">
<?php 
	$user_id            = $current_user->ID;
	$post_id            = _get('post_id');
	$post_title         = '';
	$post_content       = '';
	$cat_id             = [];
	$post_thumbnail_src = '';
	$thumbnail_id       = '';

	if ($post_id) {
	    $get_post = get_post($post_id);

	    $post_title           = $get_post->post_title ? $get_post->post_title : '';
	    $post_content         = $get_post->post_content;
	    $thumbnail_id         = get_post_thumbnail_id($post_id);
	    $attachment_image_src = wp_get_attachment_image_src($thumbnail_id, 'full');
	    $post_thumbnail_src   = $attachment_image_src[0];

	    $category = get_the_category($post_id);
	    foreach ($category as $key => $value) {
	        $cat_id[] = $value->cat_ID;
	    }
	    if ($get_post->post_author != $user_id) {
	        wp_die('系统异常');
	    }
	}
	?>

	<div class="info-wrap">
	
		<div class="tougao" style=" background-color: #fff;padding: 20px; ">
			<form action="" method="POST" id="post_form">
			<div class="tougao-editor" style=" overflow: hidden; ">
				<p><input name="post_title" id="post_title" class="form-control" type="text" placeholder="请输入文章标题..." value="<?php echo $post_title;?>" style="width: 100%; padding: 6px 12px; border: 1px solid #cccccc; border-radius: 4px;"></p>
				<div class="editor" id="editor" name="editor">
					<?php
	                    $content = ($post_content) ? $post_content : '' ;
	                    echo $content;
	                    // $content   = '';
	                    // wp_editor( $content, $editor_id, $settings );
	                ?>
				</div>
			</div>

			<div class="content-editor">
				<div class="category">
					<div class="package" style=" padding: 10px 0; ">

						选择文章分类 <select name="cats[]" title="请选择所属分类">
						<?php 
							$cats = get_categories( array( 'hide_empty' => false ) );
							foreach ($cats as $key => $value) {
								if( $cat_id ){
									if( in_array($value->term_id, $cat_id) ){
										echo '<option selected value="'.$value->term_id.'">'.$value->name.'</option>';
									}else{
										echo '<option value="'.$value->term_id.'">'.$value->name.'</option>';
									}
								}else{
									echo '<option value="'.$value->term_id.'">'.$value->name.'</option>';
								}
							}
						?>
						</select>
						
					</div>
				</div>
				<div class="package" style=" padding: 10px 0; ">
					<div class="thumbnail">
						文章缩略图默认调用文章第一张
						<input type="hidden" name="thumbnail" class="thumbnail" value="<?php echo $thumbnail_id; ?>">
					</div>
				</div>

			</div>
			<input type="hidden" name="action" id="publish_post" value="publish_post">
			<input type="hidden" name="post_status" id="post_status" value="">
			<input type="hidden" name="post_id" value="<?php echo $post_id; ?>">
			<input type="hidden" name="editor" value="">
			</form>

			<div class="tijiao">
				<a href="javascript:;" data-status="draft" class="btn btn-primary ladda-button publish_post">保存草稿</a>
				<a href="javascript:;" data-status="pending" class="btn btn-primary ladda-button publish_post">提交审核</a>
			</div>
		</div>
	</div>
	<script src="//cdn.bootcss.com/js-xss/0.3.3/xss.min.js"></script>
	<script type="text/javascript" src="//unpkg.com/wangeditor/release/wangEditor.min.js"></script>
	<script type="text/javascript">
        var E = window.wangEditor
        var editor = new E('#editor')
        // 或者 var editor = new E( document.getElementById('editor') )
        editor.create()
    </script>

</div>