<?php
_the_ads('ad_list_header', 'list-header');

_the_leadpager(); 

echo '<div class="excerpts-wrapper">';
    echo '<div class="excerpts">';

        if( is_home() && empty($paged) && _hui('excerpt_hot_s') ){

            $is_hotposts = true;

            $limit_date = time() - _hui('excerpt_hot_date', 2)*86400;
            $limit_date = date("Y-m-d H:i:s",$limit_date);

            $limit = _hui('excerpt_hot_items', 2);
            $min_views = _hui('excerpt_hot_minviews', 200);


            $hotposts_number = 0;
            $hotposts_ids = array();

            $hotposts = $wpdb->get_results("SELECT DISTINCT $wpdb->posts.*, (meta_value+0) AS views FROM $wpdb->posts LEFT JOIN $wpdb->postmeta ON $wpdb->postmeta.post_id = $wpdb->posts.ID WHERE post_date > '".$limit_date."' AND post_type = 'post' AND post_status = 'publish' AND meta_key = 'views' AND meta_value > {$min_views} ORDER BY views DESC LIMIT $limit");
            
            if( !empty($hotposts) ){

                foreach ($hotposts as $post) {

                    $hotposts_ids[] = get_the_ID();
                    $hotposts_number += 1;
                    
                    include 'excerpt-item.php';
                    
                }

                $args = array(
                    'ignore_sticky_posts' => 1,
                    'post__not_in'        => $hotposts_ids,
                    'showposts'           => get_option('posts_per_page', 20) - $hotposts_number
                );

                query_posts($args);
                
            }

        }

        while ( have_posts() ) : the_post();

            get_template_part( 'excerpt', 'item' );

        endwhile; 

        wp_reset_query();


    echo '</div>';
echo '</div>';

_paging();

_the_ads('ad_list_footer', 'list-footer');
