<?php 
/**
 * template name: 30天热门
 */

get_header();
?>

<?php _the_focusbox( '', get_the_title() ); ?>

<section class="container">

    <?php 

        $limit_date = 30;
        $limit = _hui('page_month_count', 50);
        $min_views = 0;

        $limit_date = time() - $limit_date*86400;
        $limit_date = date("Y-m-d H:i:s", $limit_date);

        $hotposts = $wpdb->get_results("SELECT DISTINCT $wpdb->posts.*, (meta_value+0) AS views FROM $wpdb->posts LEFT JOIN $wpdb->postmeta ON $wpdb->postmeta.post_id = $wpdb->posts.ID WHERE post_date > '".$limit_date."' AND post_type = 'post' AND post_status = 'publish' AND meta_key = 'views' AND meta_value > {$min_views} ORDER BY views DESC LIMIT $limit");

        if( !empty($hotposts) ){

            echo '<div class="excerpts-wrapper">';
            echo '<div class="excerpts">';

            foreach ($hotposts as $post) {
                get_template_part( 'excerpt', 'item' );
            }

            echo '</div>';
            echo '</div>';

        }else{

            get_template_part( 'excerpt', 'none' );
            
        }

    ?>

</section>

<?php get_footer(); ?>