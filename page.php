<?php get_header(); ?>
<section class="subpage grid_8">
    <?php if (have_posts()) : the_post(); global $post; ?> 
        <div class="content">
            <h1><?php the_title(); ?></h1>
        	<?php the_content(); ?>
        </div>
	<?php endif; ?>
</section>
<aside id="sidebar" class="grid_4">
	<?php get_sidebar(); ?>
</aside>
<?php get_footer(); ?>