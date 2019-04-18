<?php get_header(); ?>

<?php 
$date_title = '';
if(is_day()) {
	$date_title = get_the_time('Y年m月j日');
}
elseif(is_month()) {
	$date_title = get_the_time('Y年m月');
}
elseif(is_year()) {
	$date_title = get_the_time('Y年'); 
}
?>

<?php _the_focusbox( '', $date_title.' 的文章', '' ); ?>

<section class="container">
	<?php get_template_part( 'excerpt' ); ?>
</section>

<?php get_footer(); ?>