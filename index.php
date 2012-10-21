<?php get_header(); global $SPT; ?>
    <?php 
    $feature_setting = get_option(SPT_OPTION_FEATURE);
    if(1 == $feature_setting['featurebanner_enabled']) :
    ?>
    <div class="grid_12">
        <?php $SPT->get_feature(); ?>
    </div>
    <?php endif; ?>
    <div class="clr"></div>
    <section class="homepage clearfix">
		<?php $args = array('category_name' => 'homepage-left','showposts' => 1); query_posts($args); if (have_posts()) : while (have_posts()): the_post(); global $post; ?>
        <article class="grid_4 bucket">
            <?php 
            $readmore_link = get_post_meta($post->ID,'spt_readmore_link',true); 
            $readmore_link = ($readmore_link)? $readmore_link : get_permalink();
            ?>
            <a href="<?=$readmore_link;?>">
                <?php if ( has_post_thumbnail() ) { echo get_the_post_thumbnail($post->ID,'full',array('title'=>'')); } ?>
                <h2><?php the_title();?></h2> 
                <div class="excerpt">
                    <?php the_excerpt(); ?>
                </div>
            </a>
        </article>
        <?php endwhile; endif; wp_reset_query(); ?>
        <?php $args = array('category_name' => 'homepage-center','showposts' => 1); query_posts($args); if (have_posts()) : while (have_posts()): the_post(); global $post; ?>
        <article class="grid_4 bucket">
            <?php 
            $readmore_link = get_post_meta($post->ID,'spt_readmore_link',true); 
            $readmore_link = ($readmore_link)? $readmore_link : get_permalink();
            ?>
            <a href="<?=$readmore_link;?>">
                <?php if ( has_post_thumbnail() ) { echo get_the_post_thumbnail($post->ID,'full',array('title'=>'')); } ?>
                <h2><?php the_title();?></h2> 
                <div class="excerpt">
                    <?php the_excerpt(); ?>
                </div>
            </a>
        </article>
        <?php endwhile; endif; wp_reset_query(); ?>
        <?php $args = array('category_name' => 'homepage-right','showposts'=>1); query_posts($args);  query_posts($args); if (have_posts()) : while (have_posts()): the_post(); global $post; ?>
        <article class="grid_4 bucket">
            <?php 
            $readmore_link = get_post_meta($post->ID,'spt_readmore_link',true); 
            $readmore_link = ($readmore_link)? $readmore_link : get_permalink();
            ?>
            <a href="<?=$readmore_link;?>">
                <?php if ( has_post_thumbnail() ) { echo get_the_post_thumbnail($post->ID,'full',array('title'=>'')); } ?>
                <h2><?php the_title();?></h2> 
                <div class="excerpt">
                    <?php the_excerpt(); ?>
                </div>
            </a>
        </article>
	   <?php endwhile; endif; wp_reset_query(); ?> 
    </section>
<?php get_footer(); ?>