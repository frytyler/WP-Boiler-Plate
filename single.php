<?php get_header(); ?>
    <section class="subpage grid_8">
    <?php if (have_posts()) : the_post(); global $post; ?>
        <h1><?php the_title(); ?></h1>
    	<?php the_content(); ?>
	<?php endif; ?>
    </section>
    <aside id="sidebar" class="grid_4">
    	<?php get_sidebar(); ?>
    </aside>
<?php get_footer(); ?>