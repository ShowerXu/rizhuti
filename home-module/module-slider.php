<?php
function mo_slider(){
    $module_slide = _hui( 'focusslide' ); 
    if (!$module_slide) {
        echo '<h2 style=" text-align: center; margin: 0 auto; padding: 60px; ">请前往后台新建幻灯片！</h2>';
    }
    foreach ($module_slide as $key => $value) {
        if ($value['_title'] && $value['_desc'] && $value['_src']) {
          echo '<div class="swiper-slide"><div class="focusbox" style="background-image: url('.$value['_src'].')">
                    <div class="focusbox-image-overlay"></div><div class="container">
                        <h3 class="focusbox-title">'.$value['_title'].'</h3>
                        <div class="focusbox-text">'.$value['_desc'].'</div>
                        <a'.( $value['_blank'] ? ' target="_blank"' : '' ).' href="'.$value['_href'].'" class="btn btn-wiht" style=" margin-bottom: 1rem; "><i class="iconfont">&#xe641;</i> '.$value['_btn'].'</a>
                    </div></div></div>';
        }
    }
}

?>
<?php  if (!$paged || $paged===1) { ?>
<section class="container-white">
    <!-- Swiper -->
    <div class="swiper-container swiper-container-horizontal">
        <div class="swiper-wrapper">
            <?php mo_slider(); ?>
        </div>
        <?php if (_hui( 'focusslide_pagination' )) {
           echo '<div class="swiper-pagination swiper-pagination-bullets swiper-pagination-bullets-dynamic"></div>';
        }?>
        <?php if (_hui( 'focusslide_button' )) {
           echo '<div class="swiper-button-prev"></div><div class="swiper-button-next"></div>';
        }?>
    </div>

    <script src="<?php echo get_stylesheet_directory_uri() ?>/js/swiper.min.js"></script>
    <script type="text/javascript">
        var swiper = new Swiper('.swiper-container', {
          autoplay:true,
          pagination: {
            el: '.swiper-pagination',
            dynamicBullets: true,
          },
          // 如果需要前进后退按钮
            navigation: {
              nextEl: '.swiper-button-next',
              prevEl: '.swiper-button-prev',
            },
        });
    </script>
</section>
<?php } ?>