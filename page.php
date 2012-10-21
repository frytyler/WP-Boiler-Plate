<?php get_header(); ?>   
<section class="subpage">
    <?php if (have_posts()) : the_post(); global $post; ?> 
        <div class="grid_12 content">
            <h1><?php the_title(); ?></h1>
        	<?php the_content(); ?>
        </div>
	<?php endif; ?>
</section>
<?php get_footer(); ?>