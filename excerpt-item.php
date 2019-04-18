<?php 


$price = get_post_meta($post->ID,'wppay_price',true);
$vip = get_post_meta($post->ID,'wppay_vip_auth',true);
$cols = _hui('list_cols', 5);
if( _hui('list_imagetext') ){
    $cols .= ' excerpt-combine';
}

if( _hui('list_hover_plugin') ){
    $cols .= ' excerpt-hoverplugin';
}

$p_like = _get_post_like_data(get_the_ID());
echo '<article class="excerpt excerpt-c'.$cols.'">';

    echo '<a'. _target_blank() .' class="thumbnail" href="'.get_permalink().'">'._get_post_thumbnail().'</a>';
    
    echo '<h2><a'. _target_blank() .' href="'.get_permalink().'">'.get_the_title().'</a></h2>';

    echo '<footer>';
        if( $vip && $vip != 0 ){
            echo '<span class="post-price"><i class="iconfont">&#xe63f;</i></span>';
        }
        if( $price && $price != 0 ){
            echo '<span class="post-price"><i class="iconfont">&#xe628;</i> '.$price.'</span>';
        }

        if( _hui('list_is_time') ){
            echo '<time>'._get_post_time().'</time>';
        }

        echo '<span class="post-view"><i class="iconfont">&#xe611;</i> '._get_post_views().'</span>';
        echo '<span class="post-comm">'._get_post_comments().'</span>';

    echo '</footer>';
    
echo '</article>';