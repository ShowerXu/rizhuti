<?php if (!$paged || $paged===1) { 
$module_home_service = _hui( 'mo_home_service' );
?>

<?php if (!$module_home_service) { ?>
    <h2 style=" text-align: center; margin: 0 auto; padding: 60px; ">请前往后台设置服务模块！</h2>
<?php }else{ ?>
    <section class="cs-lightbg">
        <div class="container">
            <div class="section scrollspy" id="cs-statistics">
                <!--   Icon Section   -->
                <div class="row cs-row">
                    <div class="section-info"> 
                        <h2 class="postmodettitle"><?php echo _hui( 'mo_home_service_title' ) ?></h2> 
                        <div class="postmode-description"><?php echo _hui( 'mo_home_service_desc' ) ?></div> 
                    </div>
                    <div class="row cs-row">
                        <?php foreach ($module_home_service as $key => $value) { ?>
                            <?php if ($value['_title']) { 
                                echo'<div class="col s12 m4"><div class="card-panel cs-statics"><div class="media-left"><img src="' .$value['_img']['url'].'"></div><div class="media-left">'.$value['_title'].'<br>'.$value['_desc'].'</div></div></div>';
                            } ?>
                        <?php } ?>
                        
                    </div>

                </div>
            </div>
        </div>
    </section>
<?php } ?>

<?php } ?>
