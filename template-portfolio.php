<?php global $SPT; ?>
<?php get_header('sub'); ?>
<section class="container subpage">
    <?php if (have_posts()) : the_post(); global $post; ?> 
    <section class="portfolio">
        <header class="grid_12">
            <h2><?php the_title(); ?></h2>
        </header>
        <?php $t=0; $args = array('category_name' => 'portfolio','orderby' => 'date', 'order' => 'DESC'); query_posts($args); if (have_posts()) : while(have_posts()):the_post(); global $post; 
                $t++;
                $clear = NULL;
                $count_array = array('3','6','9','12','15','18','21','24','27','30');
                if (in_array($t,$count_array)) { 
                    $clear = '<div class="clr"></div>';
                } else { 
                    $clear = NULL;
                }
                $portfolio_title = get_post_meta($post->ID, 'simsco_portfolio_title',true);
                $portfolio_details = get_post_meta($post->ID, 'simsco_portfolio_details',true);
                $portfolio_increment = get_post_meta($post->ID, 'simsco_portfolio_increment',true);
                $portfolio_list = NULL;
                for($x=1; $x <= $portfolio_increment; $x++) {
                    $stop = false;
                    $portfolio_id = get_post_meta($post->ID, 'simsco_portfolio_'.$x.'_id',true);
                    $portfolio_info = ($portfolio_id) ? get_post($portfolio_id) : NULL;
                    $portfolio_link = $portfolio_info->guid;
                    if ((1 == $x) && (!$stop)){
                        $single_portfolio_image = '<a href="'.$portfolio_link.'" class="portfolio_img" rel="'.$post->ID.'"><figure class="portfolio_image"><img src="'.$portfolio_link.'" /></figure></a>';
                        $stop = true;
                    }
                    if($x >= 2) {
                        $multi_portfolio_img = true;
                        $portfolio_list .= '<a href="'.$portfolio_link.'" rel="'.$post->ID.'"><img src="'.$portfolio_link.'" /></a>'.chr(13);
                    }
                }
                ?>
                <article class="grid_4 portfolio_item">
                    <?=$single_portfolio_image;?>
                    <div style="display:none;">
                    <?=($multi_portfolio_img)? $portfolio_list : NULL; ?>
                    </div>
                    <h3><?=$portfolio_title;?></h3>
                    <div class="details"><?=$portfolio_details;?></div>
                </article>
                <?=$clear;?>
                <?php endwhile; endif; wp_reset_query(); ?>
    </section>
    <?php endif; ?>
</section>
<?php get_footer(); ?>