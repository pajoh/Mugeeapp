<?php
	et_get_mobile_header();


?>
<section class="section section-single-project single">
<?php
    if(have_posts()) { the_post();
        global $wp_query, $ae_post_factory, $post, $project, $user_ID;
        $post_object    = $ae_post_factory->get(PROJECT);

        $convert            = $post_object->convert($post);
        $et_expired_date    = $convert->et_expired_date;
        $bid_accepted       = $convert->accepted;
        $project_status     = $convert->post_status;
        $profile_id         = get_user_meta($post->post_author,'user_profile_id', true);
        $currency           = ae_get_option('content_currency',array('align' => 'left', 'code' => 'IDR', 'icon' => 'Rp. '));
        $project            = $convert;
        $exp                = $convert->et_expired_date;
?>

	<div class="info-single-project-wrapper">
    	<div class="container">
            <div class="info-project-top">
                <div class="avatar-author-project">
                    <a href="<?php echo get_author_posts_url( $post->post_author ); ?> ">
                        <?php echo get_avatar( $post->post_author, 35,true, get_the_title($profile_id) ); ?>
                    </a>
                </div>
                <h1 class="title-project"><?php the_title();?></h1>
                <div class="clearfix"></div>
            </div>
            <div class="info-bottom">
                <span class="name-author">
                    <?php printf(__('Dilelang Oleh %s',ET_DOMAIN), get_the_author_meta( 'display_name', $convert->post_author ));?>
                </span>
                <span class="price-project">
                    <?php echo $convert->budget; ?>
                </span>
            </div>
        </div>
    </div>

    <div class="info-bid-wrapper">
       
           
            
            
    
            	
                <p class="btn-warpper-bid">
                <?php if( !$user_ID && $project_status == 'publish'){ ?>
                        <a href="#"  class="btn-apply-project-item btn-login-trigger btn-bid btn-bid-mobile" ><?php  _e('Bid',ET_DOMAIN);?></a>
                        <?php
                    } else {
                        $role = ae_user_role();
                        if($project_status == 'publish'){
                            if(( fre_share_role() || $role == FREELANCER ) && $user_ID != $project->post_author ){
                                $has_bid = fre_has_bid( get_the_ID() );
                                if( $has_bid ) {
                                    ?>
                                    <a rel="<?php echo $project->ID;?>" href="#" id="<?php echo $has_bid;?>" title= "<?php _e('Delete this bidding',ET_DOMAIN); ?>"  class="btn-bid btn-del-project" ><?php  _e('Cancel',ET_DOMAIN);?></a>

                                <?php
                                } else {
                                $class = 'btn-bid-mobile';
                                $href = '#';
                                if( !can_user_bid( $user_ID ) ){
                                    $class = '';
                                    $href = et_get_page_link('upgrade-account');
                                }
                                ?>
                                <a href="<?php echo $href; ?>"  class="btn-apply-project-item  btn-bid <?php echo $class ?>" >
                                    <?php  _e('Bid ',ET_DOMAIN);?>
                                </a>
                                <?php }
                            }
                            else { ?>
                                <a href="#" id="<?php the_ID();?>"  class="btn-apply-project-item btn-bid" ><?php  _e('Sedang dilelang',ET_DOMAIN);?></a>
                                <?php
                            }

                        }
                        if($project_status == 'close'){
                            if( (int)$project->post_author == $user_ID){ ?>
                                <a href="#" class="btn btn-primary btn-close-project"><?php _e("Tutup", ET_DOMAIN); ?></a>
                                <a href="#" id="<?php the_ID();?>"   class="btn btn-primary btn-project-status btn-complete-project btn-complete-mobile" >
                                    <?php  _e('Selesai',ET_DOMAIN);?>
                                </a>
                                <?php
                            } else {
                                $freelan_id  = (int)get_post_field('post_author', $bid_accepted);
                                if($freelan_id == $user_ID ) { ?>
                                    <a href="#"  class="btn btn-primary btn-quit-project" title="<?php  _e('Selesai',ET_DOMAIN);?>" ><?php  _e('Selesai',ET_DOMAIN);?></a>
                                <?php }else{ ?>
                                    <a href="#"  class="btn btn-primary" title="<?php  _e('Working',ET_DOMAIN);?>" ><?php  _e('Working',ET_DOMAIN);?></a>
                                <?php }
                            }
                        } else if($project_status == 'complete'){

                            $freelan_id  = (int)get_post_field('post_author',$convert->accepted);

                            $comment        = get_comments( array('status'=> 'approve', 'type' => 'fre_review', 'post_id'=> get_the_ID() ) );

                            if( $user_ID == $freelan_id && empty( $comment ) ){ ?>
                                <a href="#" id="<?php the_ID();?>" title="<?php  _e('Ulasan',ET_DOMAIN);?>" class="btn-bid btn-project-status btn-complete-project btn-complete-mobile" ><?php  _e('Review job',ET_DOMAIN);?></a>
                                <?php
                            } else { ?>
                                <a href="#"  class="btn-bid" title="<?php  _e('Selesai',ET_DOMAIN);?>" ><?php  _e('Selesai',ET_DOMAIN);?></a>
                                <?php
                            }
                        } else{
                            $text_status =   array( 'pending'   => __('Pending',ET_DOMAIN),
                                                        'draft'     => __('Draft',ET_DOMAIN),
                                                        'archive'   => __('Draft',ET_DOMAIN),
                                                        'reject'    => __('Reject', ET_DOMAIN),
                                                        'trash'     => __('Trash', ET_DOMAIN),
                                                        'close'     => __('Working', ET_DOMAIN),
                                                        'complete'  => __('Completed', ET_DOMAIN),
                                                    );
                            if(isset($text_status[$project_status])){ ?>
                                <a href="#"  class="btn-apply-project-item" ><?php  echo isset($text_status[$convert->post_status]) ? $text_status[$convert->post_status] : ''; ;?></a>
                                <?php
                            }
                        }
                    }
                    ?>
   
    
        <div class="clearfix"></div>
    </div>

    <?php

    ?>

    <?php
    // dispute form
   {
            get_template_part('') ?>
    <?php } ?>

    <!-- user message -->
    <?php if(isset($_REQUEST['workspace']) && $_REQUEST['workspace'] && fre_access_workspace($post)) { ?>
    <div class="workplace-container">
        <div class="info-single-project-wrapper">
            <h1 class="title-workspace"><?php _e("Workspace", ET_DOMAIN); ?></h1>
        </div>
        <?php get_template_part('template/project', 'workspaces') ?>
    </div>
    <?php }else{ ?>
    <!--// user message -->
    <div class="content-project-wrapper">
        <!-- form bid !-->
        <div class="form-bid">
            <?php get_template_part('mobile/template-js/form','bid-project'); ?>
            <?php get_template_part('mobile/template-js/form','review-project'); ?>
        </div>
        <!-- end form bid !-->
    	<h2 class="title-content"><?php _e('Deskripsi Ikan:',ET_DOMAIN);?></h2>
        <?php
            the_content();
            if(function_exists('et_render_custom_field')) {
                et_render_custom_field($post);
            }
        ?>
        <?php list_tax_of_project( get_the_ID(), __('Skills required:',ET_DOMAIN), $tax_name = 'skill' ); ?>
        

        <?php

            // list project attachment
            $attachment = get_children( array(
                    'numberposts' => -1,
                    'order' => 'ASC',
                    'post_parent' => $post->ID,
                    'post_type' => 'attachment'
                  ), OBJECT );
            if(!empty($attachment)) {
                echo '<div class="project-attachment">';
                echo '<h3 class="title-content">'. __("Gambar Ikan:", ET_DOMAIN) .'</h3>';
                echo '<ul class="list-file-attack-report">';
                foreach ($attachment as $key => $att) {
                    $file_type = wp_check_filetype($att->post_title, array('jpg' => 'image/jpeg',
                                                                            'jpeg' => 'image/jpeg',
                                                                            'gif' => 'image/gif',
                                                                            'png' => 'image/png',
                                                                            'bmp' => 'image/bmp'
                                                                        )
                                                );
                    $class="";

                    if(isset($file_type['ext']) && $file_type['ext']) $class="image-gallery";
                    echo '<li>
                            <a class="'.$class.'" target="_blank" href="'.$att->guid.'"><i class="fa fa-paperclip"></i>'.$att->post_title.'</a>
                        </li>';
                }
                echo '</ul>';
                echo '</div>';
            }

        ?>

        <?php
            // Action to add more in mobile single project template
            do_action( 'after_mobile_single_project', $project )
        ?>
    </div>
    <?php } ?>
    <div class="history-cmt-wrapper">
    	<div class="btn-tabs-wrapper">
        	<ul class="" role="tablist">
            	<li class="active" <?php if(ae_get_option('disable_project_comment')) { echo 'style="width:100%"';}?> >
                    <a href="#history-tabs" role="tab" data-toggle="tab">
                        <?php
                            if($convert->total_bids>1)
                                printf(__("%d Riwayat Bid", ET_DOMAIN), (int)$convert->total_bids);
                            else
                                printf(__("%d Riwayat Bid", ET_DOMAIN), (int)$convert->total_bids);
                        ?>
                    </a>
                </li>
                <?php if(!ae_get_option('disable_project_comment')) { ?>
                <li>
                    <a href="#comment-tabs" role="tab" data-toggle="tab">
                    <?php
                        if( !$user_ID ){
                            $total_comment = get_comments(array( 'post_id' => $post->ID, 'type' => 'comment', 'count' => true, 'status' => 'approve' ));
                        }
                        else{
                            $total_comment = get_comments(array( 'post_id' => $post->ID, 'type' => 'comment', 'count' => true, 'status' => 'all' ));
                        }
                        // comments_number (__('0 Comment', ET_DOMAIN), __('1 Comment', ET_DOMAIN), __('% Comments', ET_DOMAIN));
                        if($total_comment > 1) {
                            printf(__("%d Komentar", ET_DOMAIN), $total_comment);
                        }else{
                            printf(__("%d Komentar", ET_DOMAIN), $total_comment);
                        }
                    ?>
                    </a>
                </li>
                <?php } ?>
            </ul>
        </div>
        <div class="tab-content">
        	<div class="tab-pane fade in active" id="history-tabs">
            	<!-- List bid of this project !-->
                <?php

                global $project, $post;
                add_filter('posts_orderby', 'fre_order_by_bid_status');
                $q_bid      = new WP_Query( array(  'post_type' => BID,
                                                    'post_parent' => get_the_ID(),
                                                    'post_status' => array('publish','complete', 'accept')
                                                )
                                            );
                $bid_data = array();
                remove_filter('posts_orderby', 'fre_order_by_bid_status');
                if($q_bid->have_posts()):
                    echo '<div class="info-bidding-wrapper project-'.$project->post_status.'">';
                        echo '<ul class="list-history-bidders list-bidding">';
                        while($q_bid->have_posts()):  $q_bid->the_post();
                            get_template_part( 'mobile/template/bid', 'item' );
                            $bid_data[] = $post;
                        endwhile;
                        echo '</ul>';

                        // paging list bid on this project
                        if($q_bid->max_num_pages > 1){
                            echo '<div class="paginations-wrapper">';
                                $q_bid->query = array_merge(  $q_bid->query ,array('is_single' => 1 ) ) ;
                                ae_pagination($q_bid, get_query_var('paged'), $type = 'load_more');
                            echo '</div>';
                        }
                    echo '</div>';


                    // end paging
                else :
                    get_template_part( 'mobile/template/bid', 'not-item' );
                endif;
                wp_reset_query();
                ?>
                <?php
                if(!empty($bid_data)) {
                    echo '<script type="data/json" class="biddata" >'.json_encode($bid_data).'</script>';
                } ?>
                <!-- End list bid !-->
                <div class="clearfix"></div>
            </div>

            <div class="tab-pane fade" id="comment-tabs">
                <?php if(!ae_get_option('disable_project_comment')) { ?>
            	<div class="comment-list-wrapper">
                   <div class="comments" id="project_comment">
                        <?php comments_template('/comments.php', true)?>
                    </div>
                </div>
                <?php } ?>
            </div>
        </div>
    </div>

    <?php get_template_part( 'mobile/form-bid', 'project' ); ?>
    <?php
    }
?>
<input type="hidden" id="project_id" name="<?php echo $project->ID;?>" value="<?php echo $project->ID;?>" />
</section>
<?php
    echo '<script type="data/json" id="project_data">'.json_encode($project).'</script>';
	et_get_mobile_footer();
?>
