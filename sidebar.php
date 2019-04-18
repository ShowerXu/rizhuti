<div class="sidebar">
<?php 
	if (function_exists('dynamic_sidebar')){

		if (is_single()){
			dynamic_sidebar('single'); 
		}
		else if (is_page()){
			dynamic_sidebar('page'); 
		}

	} 
?>
</div>