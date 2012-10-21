$js_=jQuery.noConflict();
var a = {
	init: function(){
		a.plugins.flexslider();
		a.plugins.fancybox();
		a.menu();
		//a.plugins.masonry();
	},
	plugins : {
		flexslider : function() {
			$js_('.flexslider').flexslider();
		},
		masonry : function() {
			var $container = $js_('.main');

			$container.imagesLoaded( function(){
			  $container.masonry({
			    	itemSelector : '.product',
					columnWidth: function( containerWidth ) {
				    	return containerWidth / 3;
					}
				});
			});	
		},
		fancybox : function() {
			$js_('.product a[rel]').fancybox({padding: 0});
		}
	},
	menu: function() {
		$js_('.btn-navbar').on('click',function(e){
			e.preventDefault();
			$js_('#mainnav').toggleClass('open');
		});
	}
};
$js_(document).ready(function(){
	a.init();
});