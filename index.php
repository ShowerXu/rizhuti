<?php get_header(); $paged = get_query_var('paged'); ?>

<?php 
$module_home = _hui( 'home_module' );
if (!$module_home) {
    echo '<h2 style=" text-align: center; margin: 0 auto; padding: 60px; ">请前往后台-主题设置-设置首页模块！</h2>';
}
// var_dump($module_home['enabled']);
if($module_home){
  foreach ($module_home['enabled'] as $key => $value) {
    get_template_part( 'home-module/module', $key );
  }
}
?>

<?php get_footer(); ?>