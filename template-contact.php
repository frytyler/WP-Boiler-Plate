<?php 
/* Template Name: CONTACT */
global $SPT; 
?>
<?php get_header('sub'); ?>
<section class="subpage">
    <?php if (have_posts()) : the_post(); global $post; ?> 
    <section class="contactus">
    	<article class="grid_12 content">
    		<h2><?php the_title(); ?></h2>
    		<?php the_content(); ?>
    	</article>
    	<div class="clr"></div>
    	<?php $SPT->get_contact_form(); ?>
    </section>
	<?php endif; ?>
</section>
<?php get_footer(); ?>