<?php /* TEMPLATE NAME: CUSTOM CATEGORIES  */ ?>

<?php get_header(); ?>   
<section class="subpage">
    <?php if (have_posts()) : the_post(); ?>
        <div class="grid_12 content">
            <h1><?php the_title(); ?></h1>
            <?php the_content(); ?>
        </div>
        <?php endif; ?>
        <?php global $post; $cat = get_post_meta($post->ID, 'custom_categories', true); if ($cat) { query_posts("cat=".$cat."&paged=$paged&showposts=10&orderby=date&order=DESC"); if (have_posts()) :
        $paged = (get_query_var('paged')) ? get_query_var('paged') : 1; 
        while (have_posts()) : the_post(); ?>

            <?php 
            $title = get_the_title();
            $content = get_the_content(); 
            $content_class = ($content)? 'has-content' : NULL;
            $details = ($content)? '<div class="details">'.$content.'</div>' : NULL;
            $portfolio_increment = get_post_meta($post->ID, 'portfolio_increment',true);
            $portfolio_list = NULL;
            for($x=1; $x <= $portfolio_increment; $x++) {
                $stop = false;
                $portfolio_id = get_post_meta($post->ID, 'portfolio_'.$x.'_id',true);
                $portfolio_info = ($portfolio_id) ? get_post($portfolio_id) : NULL;
                $portfolio_link = $portfolio_info->guid;
                if ((1 == $x) && (!$stop)){
                    $single_portfolio_image = '
                        <a href="'.$portfolio_link.'" class="'.$content_class.' product_img" rel="'.$post->ID.'">
                            <img src="'.$portfolio_link.'" />
                            <h2>'.$title.'</h2>
                            '.$details.'
                        </a>';
                    $stop = true;
                }
                if($x >= 2) {
                    $multi_portfolio_img = true;
                    $portfolio_list .= '<a href="'.$portfolio_link.'" rel="'.$post->ID.'"><img src="'.$portfolio_link.'" /></a>'.chr(13);
                }
            }

            ?>
            
        <article class="grid_4 product">
            <?=$single_portfolio_image;?>
            <?php if($multi_portfolio_img){ ?>
            <div style="display:none;"><?=$portfolio_list;?></div>
            <?php } ?>
        </article>
        
    <?php endwhile; endif; wp_reset_query(); } ?>
</section>
<?php get_footer(); ?>