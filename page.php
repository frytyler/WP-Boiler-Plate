<?php get_header(); ?>
<section class="subpage grid_9">
    <?php if (have_posts()) : the_post(); global $post; ?> 
        <div class="content">
            <h1><?php the_title(); ?></h1>
        	<?php the_content(); ?>
        </div>
	<?php endif; ?>
</section>
<?php get_footer(); ?>