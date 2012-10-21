<?php global $SPT; ?>
<footer id="footer" class="container">
	<?php $footer_settings = get_option(SPT_OPTION_FOOTER); ?>
	<p><?=$footer_settings['copyright'];?></p>
</footer>
<?php wp_footer(); ?>
</div>
</body>
</html>