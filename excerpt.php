<?php 

_the_ads('ad_list_header', 'list-header');

_the_leadpager(); 

?>


<?php
if ( have_posts() ):

    echo '<div class="excerpts-wrapper">';
	    echo '<div class="excerpts">';

	        while ( have_posts() ) : the_post();
	            get_template_part( 'excerpt', 'item' );
	        endwhile; 

	        

	    echo '</div>';
    echo '</div>';
    _paging();
    wp_reset_query();
else:

     get_template_part( 'excerpt', 'none' );

endif; 

_the_ads('ad_list_footer', 'list-footer');
