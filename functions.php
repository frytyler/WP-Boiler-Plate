<?php 
define('SPT_HOME', get_option('home'));
define('SPT_TEMPLATE_DIR', get_bloginfo('template_directory'));
define('SPT_TEMPLATE_DIR_SCRIPTS', SPT_TEMPLATE_DIR.'/scripts');
define('SPT_TEMPLATE_DIR_IMAGES', SPT_TEMPLATE_DIR.'/images');
define('SPT_OPTION_CONTACTFORM', 'spt_contact_form');
define('SPT_OPTION_FEATURE', 'spt_feature');
define('SPT_OPTION_FOOTER', 'spt_footer');
if(!class_exists('SPT')):
class SPT {

	function sidebar() {
		if (function_exists('register_sidebar')) {
			register_sidebar(array(
				'name'=>'General Sidebar',
				'id'=>'sidebar-1',
				'description' => __('This is the general sidebar', 'spt'),
				'before_widget' => '<section class="widget">',
				'after_widget' => '</section>',
				'before_title' => '<h2>',
				'after_title' => '</h2>'
			));
		}
	}

	function mail_headers($send_from_name, $send_from_email) {
		$headers = "From: \"$send_from_name\" <$send_from_email>". "\r\n";
		$headers .= "Reply-To: \"$send_from_name\" <$send_from_email>". "\r\n";
		$headers .= "Return-path: $send_from_email\n";
		$headers .= "X-Mailer:PHP" . phpversion() . "\r\n";
		$headers .= "Precedence: list\nList-Id: " . @get_option('blogname') . "\r\n";
		$headers .= "MIME-Version: 1.0\n";
		$headers .= "Content-Type: text/html; charset=\"". @get_bloginfo('charset') . "\"". "\r\n";
		return $headers;
	}
	
	function get_email_addresses($names,$emails) {
		$email = explode(",",$emails);
		$name = explode(",",$names);
		if (count($email) > 1) {
			$temp["to"] = trim(addslashes($name[0]))."<".trim($email[0]).">";
			for ($x = 1; $x < count($email); $x++) {
				$temp["cc"] .= trim(addslashes($name[$x]))."<".trim($email[$x]).">,";
			}
			$temp["cc"] = substr($temp["cc"],0,-1);
			$temp["cc"] = "Cc: " . $temp["cc"] . "\n";
		}
		else {
			$temp["to"] = trim($email[0]);
			$temp["cc"] = NULL;
		}
		return $temp;
	}
	
	function verify_email_addresses($emails) {
		$temp = true;
		$email = explode(",",$emails);
		if (count($email) > 1) {
			for ($x = 0; $x < count($email); $x++) {
				if (!@is_email($email[$x])) $temp = false;
			}
		}
		else {
			if (!@is_email($email[0])) $temp = false;
		}
		return $temp;
	}


	function remove_thumbnail_dimensions( $html, $post_id, $post_image_id ) {
	    $html = preg_replace( '/(width|height)=\"\d*\"\s/', "", $html );
	    return $html;
	}

	function clean_up_post($post) { $post = strip_tags($post); $post = stripslashes($post); $post = trim($post); return $post; }

	function disableAutoSave(){ wp_deregister_script('autosave'); }
	
	function remove_some_wp_widgets(){
		unregister_widget('WP_Widget_Calendar');
		unregister_widget('WP_Widget_Search');
		unregister_widget('WP_Widget_Recent_Comments');
		unregister_widget('WP_Widget_Categories');
		unregister_widget('WP_Widget_Links');
		unregister_widget('WP_Widget_Meta');
		unregister_widget('WP_Widget_Pages');
		unregister_widget('WP_Widget_Recent_Posts');
		unregister_widget('WP_Widget_RSS');
		unregister_widget('WP_Widget_Tag_Cloud');
		unregister_widget('WP_Widget_Archives');
	}
	
	function modify_footer_admin () { echo 'Created by <a href="http://www.simplypx.com" target="_blank">Simply px</a>.'; 
	}
	
	function remove_menu_items() {
		global $menu,$current_user;
		get_currentuserinfo();
		$restricted = array(__('Links'), __('Comments'), __('Tools'));//, __('Settings'));,  __('Users')
		if (1 != $current_user->ID) {
			$restricted = array(__('Links'), __('Comments'), __('Tools'), __('Settings'), __('Plugins')); // __('Media')
		}
		end ($menu);
		while (prev($menu)){
			$value = explode(' ',$menu[key($menu)][0]);
			if(in_array($value[0] != NULL?$value[0]:"" , $restricted)){
				unset($menu[key($menu)]);
			}
		}
	}
	
	function remove_submenus() {
		global $submenu;
		$current_user = wp_get_current_user();
		unset($submenu['index.php'][10]); // Removes 'Updates'
		unset($submenu['themes.php'][5]);
	}
	
	function delete_submenu_items() {
		global $current_user;
		get_currentuserinfo();
		remove_submenu_page('themes.php', 'theme-editor.php');
		if (1 != $current_user->ID) {
		remove_submenu_page('themes.php', 'nav-menus.php');
		}
		remove_submenu_page('plugins.php', 'plugin-editor.php');
	}
	
	function reconfigure_dashboard() {
		global $wp_meta_boxes;
		unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_recent_comments']);
		unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_plugins']);
		unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_primary']);
		unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_secondary']);
		unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_incoming_links']);
		unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_right_now']);
		unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_quick_press']);
		unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_recent_drafts']);
		//wp_add_dashboard_widget('csource_dashboard_1', __("C-Source"), array(&$this, 'csource_dashboard_1'));
	}

	function jquery() { if (!is_admin()) { wp_enqueue_script('jquery', 'ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js'); } }
	function modernizr() { if (!is_admin()) { wp_enqueue_script('modernizr', SPT_TEMPLATE_DIR_SCRIPTS.'/modernizr/modernizr-2.6.1.js', array('jquery')); } }
	function fancybox() { if (!is_admin()) { wp_enqueue_script('fancybox', SPT_TEMPLATE_DIR_SCRIPTS.'/fancybox/jquery.fancybox.pack.js', array('jquery')); } }
	function fancybox_css() { if (!is_admin()) { wp_enqueue_style('fancybox-style', SPT_TEMPLATE_DIR_SCRIPTS.'/fancybox/jquery.fancybox.css'); } }
	function flexslider() { if (!is_admin()) { wp_enqueue_script('flexslider', SPT_TEMPLATE_DIR_SCRIPTS.'/flexslider/jquery.flexslider.js'); } }
	function flexslider_css() { if (!is_admin()) { wp_enqueue_style('flexslider-style', SPT_TEMPLATE_DIR_SCRIPTS.'/flexslider/flexslider.css'); } }
	function thickbox() { global $current_screen; if (is_admin()) {  wp_enqueue_script('media-upload'); wp_enqueue_script('thickbox'); } }
	function mainjs(){ if (!is_admin()) { wp_enqueue_script('main-js', SPT_TEMPLATE_DIR_SCRIPTS.'/main.js', array('jquery')); } }
	function wp_admin_styles() { if (is_admin()) {  wp_enqueue_style('thickbox'); } }		
	
	function register_my_menus() { 
		if(function_exists('register_nav_menu')){
			register_nav_menu('menu', 'Menu');	
		} 
	}
	
	function verify_null($post_id, $name, $value) {
		if('' == trim($value) || '0' == trim($value)) {
			delete_post_meta($post_id, $name);
		} else {
			update_post_meta($post_id, $name, $value);
		}
	}
	
	function verify_null_portfolio_details($post_id, $name, $value) {
		if('' == trim($value) || '0' == trim($value)) {
			delete_post_meta($post_id, $name);
		} else {
			$value = strip_tags($value, '<a>');
			update_post_meta($post_id, $name, $value);
		}
	}

	function theme_settings(){
		wp_enqueue_script('common'); wp_enqueue_script('wp-lists'); wp_enqueue_script('postbox');
		add_theme_page(__('Theme Options', 'spt'), __('Theme Options', 'spt'), 'edit_pages', 'spt-settings', array(&$this, 'theme_settings_panel_dashboard'));

	}

	function theme_settings_panel_dashboard() {
		$hidden_field_name = 'contact_form_settings';
		$hidden_field_name_2 = 'feature_banner_settings';
		$hidden_field_name_3 = 'footer_settings';
		if(isset($_POST[$hidden_field_name])){
			$emailtoname = @$this->clean_up_post($_POST['emailtoname']);
			$emailto = @$this->clean_up_post($_POST['emailto']);
			$emailfromname = @$this->clean_up_post($_POST['emailfromname']);
			$emailfrom = @$this->clean_up_post($_POST['emailfrom']);
			$successmsg = @$this->clean_up_post($_POST['successmsg']);
			$errormsg = @$this->clean_up_post($_POST['errormsg']);
			if ($emailtoname && $emailto && $emailfromname && $emailfrom && $successmsg && $errormsg){
				$fields = array('emailtoname'=>"$emailtoname",'emailto'=>"$emailto",'emailfromname'=>"$emailfromname",'emailfrom'=>"$emailfrom",'postalcode_text_en'=>"$postalcode_text_en",'successmsg'=>"$successmsg",'errormsg'=>"$errormsg");
				update_option(SPT_OPTION_CONTACTFORM, $fields);
				$msg = '<div id="message" class="updated success_message fade"><p>Your Contact Form Settings have been updated!</p></div>';
			}
			else {
				$msg = '<div id="message" class="updated error_message fade"><p>Please ensure all the fields are entered.</p></div>';
			}
		}
		if(isset($_POST[$hidden_field_name_2])){
			$featurebanner_enabled = ($_POST['featurebanner_enabled'])? 1 : 0;
			$fields = array('featurebanner_enabled'=>"$featurebanner_enabled");
				update_option(SPT_OPTION_FEATURE, $fields);
				$msg = '<div id="message" class="updated success_message fade"><p>Your Feature Banner Settings have been updated!</p></div>';

		}
		if(isset($_POST[$hidden_field_name_3])){
			$copyright = $_POST['copyright'];
			$fields = array('copyright'=>"$copyright");
				update_option(SPT_OPTION_FOOTER, $fields);
				$msg = '<div id="message" class="updated success_message fade"><p>Your Feature Banner Settings have been updated!</p></div>';

		}
		$spt_cf_setting = get_option(SPT_OPTION_CONTACTFORM);
		$spt_feature_setting = get_option(SPT_OPTION_FEATURE);
		$spt_footer_settings = get_option(SPT_OPTION_FOOTER);
		?>
		<div id="theme_settings">
			<?=$msg;?>
			<h1>E-mail Form Settings</h1>
			<p>Set the contact page settings here.</p>
			<div class="spt_wrap">
				<form action="" method="post">
					<input type="hidden" name="<?=$hidden_field_name;?>" value="1" />
					<div class="grid_6">
						<label for="emailtoname">To Name:</label>
						<input type="text" name="emailtoname" id="emailtoname" class="widefat" value="<?=$spt_cf_setting['emailtoname'];?>" />
					</div>
					<div class="grid_6">
						<label for="emailto">To E-mail:</label>
						<input type="text" name="emailto" id="emailto" class="widefat" value="<?=$spt_cf_setting['emailto'];?>" />
					</div>
					<div class="grid_6">
						<label for="emailfromname">From Name:</label>
						<input type="text" name="emailfromname" id="emailfromname" class="widefat" value="<?=$spt_cf_setting['emailfromname'];?>" />
					</div>
					<div class="grid_6">
						<label for="emailfrom">From E-mail:</label>
						<input type="text" name="emailfrom" id="emailfrom" class="widefat" value="<?=$spt_cf_setting['emailfrom'];?>" />
					</div>
					<div class="grid_12">
						<label for="successmsg">Success Message:</label>
						<input type="text" name="successmsg" id="successmsg" class="widefat" value="<?=$spt_cf_setting['successmsg'];?>" />
					</div>
					<div class="grid_12">
						<label for="errormsg">Error Message:</label>
						<input type="text" name="errormsg" id="errormsg" class="widefat" value="<?=$spt_cf_setting['errormsg'];?>" />
					</div>
					<div class="grid_4" style="margin-top:10px;">
						<button type="submit" class="button-primary">Update</button>
					</div>
					<div class="clr"></div>
				</form>
			</div>
			<h1>Feature Banner Settings</h1>
			<p>Set up the feature banner basic Settings.</p>
			<div class="spt_wrap">
				<form action="" method="post">
				<input type="hidden" name="<?=$hidden_field_name_2;?>" value="1" />
				<div class="grid_12">
					<label for="featurebanner_enabled">
						<?php $checked = (1 == $spt_feature_setting['featurebanner_enabled'])? 'checked="checked"' : NULL; ?>
						<input type="checkbox" name="featurebanner_enabled" id="featurebanner_enabled" <?=$checked;?> value="1" />
						Enable the Feature Banner ?
					</label>
				</div>
				<div class="grid_4" style="margin-top:10px;">
					<button type="submit" class="button-primary">Update</button>
				</div>
				<div class="clr"></div>
				</form>
			</div>
			<h1>Footer Copyright Info:</h1>
			<div class="spt_wrap">
				<form action="" method="post">
					<input type="hidden" name="<?=$hidden_field_name_3;?>" value="1" />
					<div class="grid_12">
						<label for="copyright">Copyright Text</label>
						<input type="text" name="copyright" id="copyright" class="widefat" value="<?=$spt_footer_settings['copyright'];?>" />
					</div>
					<div class="grid_4" style="margin-top:10px;">
						<button type="submit" class="button-primary">Update</button>
					</div>
					<div class="clr"></div>
				</form>
			</div>
		</div>
		<?php
	}

	/* FRONT END FUNCTIONS */

	function get_menu($nav_id) { 
		global $wpdb;
		$main_menu = $wpdb->get_row("SELECT * FROM $wpdb->terms WHERE name ='MainMenu';");
		if ($main_menu) {
			$menu_items = wp_get_nav_menu_items($main_menu->term_id);
			$class = ('mainnav' == $nav_id)? 'class="grid_12"' : NULL;
			$menu_list = '<nav id="'.$nav_id.'" '.$class.'>
							<a href="#" class="btn btn-navbar left">
						        <span class="icon-bar"></span>
						        <span class="icon-bar"></span>
						        <span class="icon-bar"></span>
						    </a>
						    <div class="clr"></div>
						    <ul>
						    	<li><a href="'.SPT_HOME.'">Shop</a></li>';
			foreach ( (array) $menu_items as $key => $menu_item ) {
				$menu_id = $menu_item->object_id;
				$title = $menu_item->title;
				$url = $menu_item->url;
				$menu_list .= '<li><a href="'.$url.'">'.$title.'</a></li>';
			}	
			$menu_list .= '</ul></nav>';
		}
		return $menu_list;
	}

	function get_feature(){
	    ?>
		<section id="hero" class="flexslider">
			<ul class="slides">
	    <?php
       	$args = array('category_name' => 'feature-banner','orderby' => 'date', 'order' => 'DESC'); query_posts($args); 
	    if (have_posts()) : while(have_posts()):the_post();
	    ?>
				<li>
				  <figure>
				    <?php 
				    if ( has_post_thumbnail() ) {
				      the_post_thumbnail();
				    }
				    ?>
				  </figure>
				  <h2><?php the_title();?></h2>
				</li>
        <?php endwhile; endif; wp_reset_query(); ?>
			</ul>
		</section>
		<?php
	}

	function get_contact_form() { 
		$hidden_name_field = 'contact_form';
		$setting = get_option(SPT_OPTION_CONTACTFORM);
		if(isset($_POST[$hidden_name_field])){
			$from_name = $this->clean_up_post($_POST['from_name']);
			$email = ($this->verify_email_addresses($_POST['email']))? $_POST['email'] : false ;
			$message = $_POST['message'];
			if($email) { 
				$send_email = $this->get_email_addresses($setting['emailtoname'],$setting['emailto']);
				$headers = $this->mail_headers($setting['emailfromname'],$setting['emailfrom']);
				$headers = $send_email["cc"] . $headers;
				$subject = $from_name.' ('.$email.') has contacted you from '.SPT_HOME;
				$message = str_replace(chr(13),"<br />",$message);
				$message = str_replace(chr(10),"<br />",$message);
				$message = stripslashes($message);
				$body = '<p>'. $message .'</p>';
				$mail = wp_mail($send_email["to"], $subject, $body, $headers,'-f info@totalrenocanada.com'); 
				if ($mail){
					$from_name = NULL; $email = NULL; $message = NULL;
					$msg = '<div class="grid_12 alert alert-success">'.$setting['successmsg'].'</div>';
				}
			} else { $msg = '<div class="grid_12 alert alert-error">'.$setting['errormsg'].'</div>'; }
		}
		?>
        <form method="post" action="">
        <?=$msg;?>
            <div class="grid_12">
	            <input type="hidden" name="<?=$hidden_name_field;?>" value="1" />
	            <input type="text" class="grid_6 name" name="from_name" placeholder="Name" value="<?=$from_name;?>" />
	            <input type="email" class="grid_6 email" name="email" placeholder="E-mail" value="<?=$email;?>" />
	            <textarea name="message" class="grid_12 message" placeholder="Message"><?=$message;?></textarea>
	            <button type="submit" class="btn btn-primary">Send</button>
        	</div>
        </form>
        <?php
	}

	function footer() {
		?>
<script type="text/javascript">
$js_=jQuery.noConflict();
$js_(document).ready(function(){ 
	if(!Modernizr.input.placeholder){ $js_('[placeholder]').focus(function() { var input = $js_(this); if (input.val() == input.attr('placeholder')) { input.val(''); input.removeClass('placeholder'); } }).blur(function() { var input = $js_(this); if (input.val() == '' || input.val() == input.attr('placeholder')) { input.addClass('placeholder'); input.val(input.attr('placeholder')); } }).blur(); $js_('[placeholder]').parents('form').submit(function() { $js_(this).find('[placeholder]').each(function() { var input = $js_(this); if (input.val() == input.attr('placeholder')) { input.val(''); }})})}
});

</script>
	<?php 
	}

	function admin_scripts() {
		?>
        <style type="text/css">
			.clr { clear:both; }
			#portfolio_options .add_image { margin:10px 0 5px 0; float:right; }
			#portfolio_options .item_box { margin:10px 0; }
			#portfolio_options label { margin:5px 0; display:inline-block; }
			#portfolio_options .remove_attachment { margin-bottom:10px; text-shadow:0 1px 0 rgba(0,0,0,0.5); border-color:#991a1a; background:#991a1a; background-image:-webkit-gradient(linear, left top, left bottom, from(#c51e1e),to(#991a1a)); background-image: -webkit-linear-gradient(top,#c51e1e,#991a1a); background-image: -o-linear-gradient(top,#c51e1e,#991a1a); background-image:-moz-linear-gradient(top,#c51e1e,#991a1a); -ms-filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#c51e1e', endColorstr='#991a1a', GradientType=0); filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#c51e1e', endColorstr='#991a1a', GradientType=0); color:#fff; }
			#portfolio_options .remove_attachment:hover { background-image:-webkit-gradient(linear, left top, left bottom, from(#c51e1e),to(#8b1616)); background-image:-webkit-linear-gradient(top,#c51e1e,#8b1616); background-image: -o-linear-gradient(top,#c51e1e,#8b1616); background-image:-moz-linear-gradient(top,#c51e1e,#8b1616); -ms-filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#c51e1e', endColorstr='#8b1616', GradientType=0); filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#c51e1e', endColorstr='#8b1616', GradientType=0); border-color:#8b1616; }
			.portfolio_form img { max-width:100%; }

			.spt_wrap { width:100%; }
			.spt_wrap [class*="grid"] { display: inline; float:left; margin-left: 1%; margin-right: 1%; }
			.spt_wrap input[class*="grid"], .spt_wrap select[class*="grid"], .spt_wrap textarea[class*="grid"], .spt_wrap button[class*="grid"] { float: none; margin-left: 0; margin-right:0; }

			.spt_wrap .grid_1 { width:6.333%;   }
			.spt_wrap .grid_2 { width:14.667%;  }
			.spt_wrap .grid_3 { width:23.0%;    }
			.spt_wrap .grid_4 { width:31.333%;  }
			.spt_wrap .grid_5 { width:39.667%;  }
			.spt_wrap .grid_6 { width:48.0%;    }
			.spt_wrap .grid_7 { width:56.333%;  }
			.spt_wrap .grid_8 { width:64.667%;  }
			.spt_wrap .grid_9 { width:73.0%;    }
			.spt_wrap .grid_10 { width:81.333%; }
			.spt_wrap .grid_11 { width:89.667%; }
			.spt_wrap .grid_12 { width:98.0%;   }


        </style>
        <?php	
	}

	function admin_footer() { 
	?>
		<script type="text/javascript">
			$js_=jQuery.noConflict();
			var portfolio_image_clicked = false;
			var inc = null;
			var totalcount = $js_('#divTxt').attr('data-totalcount');
			$js_(document).ready(function() {
				window.original_send_to_editor = window.send_to_editor;
				window.send_to_editor = function(html) {
					if (portfolio_image_clicked) {
						var fileurl = $js_(html).attr('href');
						//alert(fileurl);
						$js_('#portfolio_'+inc+'_id').val(fileurl);
						$js_('#portfolio_'+inc+'_id_preview_en').val(fileurl);
						portfolio_image_clicked = false;
						$js_(".portfolio_clear_button").show();
						tb_remove();
					}
					else {
						window.original_send_to_editor(html);
						
					}
				}
			});
			function add_portfolio_image(a) {
				tb_show('', 'media-upload.php?type=image&TB_iframe=true');
				portfolio_image_clicked = true;
				inc = a.title;
				return false;
			}
			function clear_button(a) {
				t = a.title
				$js_('#portfolio_'+t+'_id').val("");
				$js_('#portfolio_'+t+'_id_preview_en').val("").hide();
				$js_('.portfolio_'+t+'_preview_en').html('<input type="text" class="widefat" disabled="disabled" id="portfolio_'+t+'_id_preview_en" value="" maxlength="2000" />');
				$js_(this).hide();
				return false;
			}
			function removeFormField(id) {
				$js_('#item_'+id).remove();
			}
			function addFormField() {				
				var increment = document.getElementById("increment").value;
				$js_("#divTxt").append('<div id="item_'+increment+'" class="item_box"><input type="hidden" class="widefat" name="portfolio_'+increment+'_id" id="portfolio_'+increment+'_id" value="" maxlength="2020" /><label>Add a portfolio image:</label><br /><a href="#" class="portfolio_button" onclick="return add_portfolio_image(this);" title="'+increment+'">Click Here</a><div class="portfolio_'+increment+'_preview_en"><input type="text" class="widefat" disabled="disabled" id="portfolio_'+increment+'_id_preview_en" value="" maxlength="2020" /></div><div><a href="#" class="portfolio_clear_button" title="'+increment+'" style="display:none" onclick="return clear_button(this);">Remove attachment</a></div><br /><a href="#" class="button remove_attachment left" onClick="removeFormField('+increment+'); return false;">Remove</a></div>');
				increment = (increment - 1) + 2;
				document.getElementById("increment").value = increment;
			}
        </script>
	<?php
	}
	
	function add_posts_page_panels() {
		if(function_exists('add_meta_box')) {
			add_meta_box('spt_custom_meta_data',__('Additional Options', 'myplugin_textdomain'), array(&$this,'meta_data_inner_custom_box'),'post', 'normal');
			add_meta_box('portfolio_options',__('Portfolio Item', 'myplugin_textdomain'), array(&$this,'portfolio_inner_custom_box'),'post', 'side');
			add_meta_box('custom_categories',__('Custom Categories', 'myplugin_textdomain'), array(&$this,'custom_categories_inner_custom_box'),'page','normal','low');
		}
	}

	function custom_categories_inner_custom_box() {
		$custom_categories = get_post_meta($_REQUEST["post"], 'custom_categories',true);
		global $wpdb;
		$custom_categories = split(",",$custom_categories);
		
		$categories = $wpdb->get_results("SELECT wterms.* FROM $wpdb->terms wterms, $wpdb->term_taxonomy wterm_taxonomy
			WHERE (wterms.term_id = wterm_taxonomy.term_id AND wterm_taxonomy.taxonomy = 'category')
			ORDER BY wterms.term_id  ASC");
		$x=0;
		$td=0;
		foreach ($categories as $cat) {
			$x++;
			$checked = (in_array((int)$cat->term_id,$custom_categories)) ? 'checked="checked"' : NULL ;
			$options .= '<label style="margin-right:10px;" for="custom_categories_'.$x.'"><input type="checkbox" name="custom_categories[]" id="custom_categories_'.$x.'" value="'.$cat->term_id.'" '.$checked.' />&nbsp;' . $cat->name . '</label>';
		}
		wp_nonce_field('custom_categories_action', 'custom_categories_noncename'); // Use nonce for verification
		?>
		<p>Check the relative categories you want associated with this PAGE:</p>
		<div>
            <?php echo $options;?>
        </div>
            <div style="margin-top:10px;">
                <b>NOTE:</b> <small>This feature is only to be used in conjuction with the "<b>CUSTOM CATEGORIES</b>" Template.</small>            
            </div>
		<?php
	}
	
	function custom_categories_save_postdata($post_id) {
		if (defined('DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
		if (!wp_verify_nonce($_POST['custom_categories_noncename'], 'custom_categories_action')) return;
		if ('page' == $_POST['post_type']) {
			if ( !current_user_can('edit_page', $post_id )) return;
		}
		else {
			if ( !current_user_can('edit_post', $post_id )) return;
		}
		$custom_categories = NULL;
		$mydata = $_POST['custom_categories'];
		
		if (is_array($mydata)) {
			foreach ($_POST["custom_categories"] as $cats):
				$custom_categories .= $cats . ",";
			endforeach;
			
			$custom_categories = substr($custom_categories,0,-1);
		}
				@$this->verify_null($post_id, 'custom_categories', $custom_categories);
		
		
		return $mydata;
	}

	function meta_data_inner_custom_box() {
		global $post, $wpdb;
		$spt_readmore_link = get_post_meta($_REQUEST["post"], 'spt_readmore_link',true);
		?>
		<h2>Add a custom Link:</h2>
		<p>Use this when you want to have a Homepage Bucket go to a Page instead of the Post.</p>
		<label for="spt_readmore_link">Custom Link: <small>( http://mysite.com/?p=3 )</small></label>
		<input type="text" name="spt_readmore_link" value="<?=$spt_readmore_link;?>" id="spt_readmore_link" class="widefat" />
		<?php
		wp_nonce_field('meta_data_action', 'meta_data_noncename');
	}

	function meta_data_save_postdata($post_id) {
		global $wpdb;
		if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
		if (!wp_verify_nonce( $_POST['meta_data_noncename'], 'meta_data_action')) return;
		if ('page' == $_POST['post_type']) {
			if (!current_user_can( 'edit_page', $post_id)) return;
		}
		else {
			if (!current_user_can( 'edit_post', $post_id)) return;
		}
		if($_POST["spt_readmore_link"]){
			$spt_readmore_link = $_POST["spt_readmore_link"];
			update_post_meta($post_id, 'spt_readmore_link', $spt_readmore_link);
		}
	}

	function portfolio_inner_custom_box(){
		global $post, $wpdb;
		$portfolio_title = get_post_meta($_REQUEST["post"], 'portfolio_title',true);
		$portfolio_details = get_post_meta($_REQUEST["post"], 'portfolio_details',true);
		?>
        <h2>Add a new Portfolio Item</h2>
        <div class="portfolio_form">
			<?php /*
			<label for="portfolio_title">Portfolio Title:</label>
            <input class="widefat" type="text" name="portfolio_title" value="<?=$portfolio_title;?>" id="portfolio_title" />
            <label for="portfolio_details">Portfolio Details:</label>
            <textarea class="widefat" name="portfolio_details" id="portfolio_details"><?=$portfolio_details;?></textarea>
            */ ?>
            <?php 
			$increment = get_post_meta($_REQUEST["post"], 'portfolio_increment',true); 
			$increment = ($increment) ? $increment : 1;
			?>
            <?php 
			wp_nonce_field('portfolio_action', 'portfolio_noncename');
			for($x=1; $x<=$increment; $x++){ 
            	$portfolio_id = false;
				$portfolio_id = get_post_meta($_REQUEST["post"], 'portfolio_'.$x.'_id',true);
			?>
            <div id="divTxt">
                <div id="item_<?=$x;?>" class="item_box">
                    <input type="hidden" class="widefat" name="portfolio_<?=$x;?>_id" id="portfolio_<?=$x;?>_id" value="<?=SPT_HOME."/?attachment_id=".$portfolio_id;?>" maxlength="2020" />
                    <label>Add a portfolio image:</label><br />
                    <a href="#" class="portfolio_button" onclick="return add_portfolio_image(this);" title="<?=$x;?>">Click Here</a>
                    <div class="portfolio_<?=$x;?>_preview_en">
                    <?php $attachment_info = get_post($portfolio_id); ?>
                    <?=($portfolio_id) ? '<div style="padding:5px; margin:5px 0; background:#F9F9F9;" class="full border_wrap file_name"><img src="'.$attachment_info->guid.'" target="_blank" /></div>' : '<input type="text" class="widefat" disabled="disabled" id="portfolio_'.$x.'_id_preview_en" value="" maxlength="2020" />' ?>
                    </div>
                    <div><a href="#" class="portfolio_clear_button" title="<?=$x;?>" <?=(!$portfolio_id) ? 'style="display:none"': NULL; ?> onclick="return clear_button(this);">Remove attachment</a></div><br />
                   <a href="#" class="button remove_attachment left" onClick="removeFormField(<?=$x;?>); return false;">Remove</a>
                    </div>
                </div>
            	<div class="clr"></div>
            
            <?php } ?>
            <input type="hidden" id="increment" name="port_increment" value="<?=($increment+1);?>">
            <input type="hidden" id="old_increment" name="old_increment" value="<?=($increment+1);?>">
            <a href="#" class="button add_image right" onClick="addFormField(); return false;">+1 Photo</a>
            <div class="clr"></div>
        </div> 
		<?php
	}
	
	function portfolio_save_postdata($post_id) {
		global $wpdb;
		if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
		if (!wp_verify_nonce( $_POST['portfolio_noncename'], 'portfolio_action')) return;
		if ('page' == $_POST['post_type']) {
			if (!current_user_can( 'edit_page', $post_id)) return;
		}
		else {
			if (!current_user_can( 'edit_post', $post_id)) return;
		}
		$increment = $_POST['port_increment'];
		$old_increment = $_POST['old_increment'];
		
		for($x=1; $x <= $old_increment; $x++) {
			delete_post_meta($post_id, 'portfolio_'.$x.'_id');
		}
		
		$y=0;
	
		for($x=1; $x <= $increment; $x++) {			
				$portfolio_file_url = $_POST['portfolio_'.$x.'_id'];
					$myQryStr = str_replace(SPT_HOME."/?","",$portfolio_file_url);
					parse_str($myQryStr);
					$portfolio_id = ($attachment_id) ? $attachment_id : false;
						$attachment_id = false;
			if (is_numeric($portfolio_id)) {
				$y++;
				$this->verify_null($post_id, 'portfolio_'.$y.'_id', $portfolio_id);
				/* return $mydata; */
			}
		}
		$portfolio_title = $_POST['portfolio_title'];
			$this->verify_null($post_id, 'portfolio_title', $portfolio_title);
		$portfolio_details = format_to_edit($_POST['portfolio_details'],true);
			$this->verify_null_portfolio_details($post_id, 'portfolio_details', $portfolio_details);
		$this->verify_null($post_id, 'portfolio_increment',$y);
	}
 
}
$SPT = new SPT();
else :
	exit("Class 'SPT' already exists");
endif;
if (isset($SPT)) {
	add_action('init',array(&$SPT,'register_my_menus'));
	add_action("admin_head", array($SPT, "admin_scripts"),7);
	add_action('admin_footer', array(&$SPT, 'admin_footer'));	
	add_action('admin_menu',  array(&$SPT, 'add_posts_page_panels'));
	add_action('widgets_init', array(&$SPT, 'sidebar'));
	add_action('save_post', array(&$SPT,'portfolio_save_postdata'));
	add_action('save_post', array(&$SPT,'meta_data_save_postdata'));	
	add_action('save_post', array(&$SPT,'custom_categories_save_postdata'));
	add_action('wp_print_scripts', array(&$SPT, 'jquery'));
	add_action('wp_print_scripts', array(&$SPT, 'modernizr'));
	add_action('wp_print_scripts', array(&$SPT, 'fancybox'));
	add_action('wp_print_styles', array(&$SPT, 'fancybox_css'));
	add_action('wp_print_scripts', array(&$SPT, 'flexslider'));
	add_action('wp_print_styles', array(&$SPT, 'flexslider_css'));
	add_action('admin_print_styles', array(&$SPT,'wp_admin_styles'));
	add_action('admin_print_scripts', array(&$SPT, 'thickbox'));
	add_action('wp_print_scripts', array(&$SPT, 'mainjs'));
	add_action('admin_init', array(&$SPT, 'delete_submenu_items'));
	add_action('admin_menu', array(&$SPT, 'remove_submenus'));
	add_action('admin_menu', array(&$SPT, 'remove_menu_items'));
	add_action('wp_dashboard_setup', array(&$SPT, 'reconfigure_dashboard'));
	add_action('widgets_init', array(&$SPT, 'remove_some_wp_widgets'));
	add_action("wp_footer", array(&$SPT, "footer"));
	add_action('admin_menu', array(&$SPT, 'theme_settings'));
	add_theme_support( 'post-thumbnails', array('post') );
}

class Code_Widget extends WP_Widget {

	function Code_Widget() {
		$widget_ops = array('classname' => 'widget_codewidget', 'description' => __('Embed your code or snippet'));
		$control_ops = array('width' => 400, 'height' => 350);
		$this->WP_Widget('codewidget', __('Insert your text'), $widget_ops, $control_ops);
	}

	function widget($args, $instance) {
		extract($args);
		$title = apply_filters( 'widget_title', empty($instance['title']) ? '' : $instance['title'], $instance );
		$text = apply_filters( 'widget_text', $instance['text'], $instance );
		echo $before_widget;
		if (!empty( $title ) ) { echo $before_title . $title . $after_title; } 
			ob_start();
			eval('?>'.$text);
			$text = ob_get_contents();
			ob_end_clean();
			?>
            <div><?php echo $instance['filter'] ? wpautop($text) : $text; ?></div>	
		<?php
		echo $after_widget;
	}

	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		//if ( current_user_can('unfiltered_html') )
			$instance['text'] =  $new_instance['text'];
		//else
			//$instance['text'] = stripslashes( wp_filter_post_kses( $new_instance['text'] ) );
		return $instance;
	}

	function form($instance) {
		$instance = wp_parse_args( (array) $instance, array( 'title' => '', 'text' => ''));
		$title = strip_tags($instance['title']);
		$text = format_to_edit($instance['text']);
		?>
		<p><label for="<?php echo $this->get_field_id('title');?>"><?php _e('Title (optional):'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('title');?>" name="<?php echo $this->get_field_name('title');?>" type="text" value="<?php echo esc_attr($title);?>" maxlength="100" /></p>
        <p><label for="<?php echo $this->get_field_id('text');?>"><?php _e('Text (required):'); ?></label>
		<textarea class="widefat" rows="16" cols="20" id="<?php echo $this->get_field_id('text');?>" name="<?php echo $this->get_field_name('text');?>"><?php echo $text;?></textarea></p>
		<?php
	}
}

class TEMPLATE_SIDEMENU extends WP_Widget {

	function TEMPLATE_SIDEMENU() {
		$widget_ops = array('classname' => 'widget_sidemenu', 'description' => __('Sidebar Navigation'));
		$control_ops = array('width' => 400, 'height' => 350);
		$this->WP_Widget('sidemenu', __('Sidebar Navigation'), $widget_ops, $control_ops);
	}

	function widget($args, $instance) {
		global $SPT;
		extract($args);
		echo $before_widget;
		echo $SPT->get_menu('side_nav');
		echo $after_widget;
	}

	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		return $instance;
	}

	function form($instance) {
		$instance = wp_parse_args((array) $instance, array('title'=>''));
		$title = strip_tags($instance['title']);
		$quantity = format_to_edit($instance['quantity']);
		?>
            <p>This will create a sidebar Navigation</p>
		<?php
	}
	
}

class Recentportfolio extends WP_Widget {

	function Recentportfolio() {
		$widget_ops = array('classname' => 'widget_recentportfolio', 'description' => __('Show recent portfolio items'));
		$control_ops = array('width' => 400, 'height' => 350);
		$this->WP_Widget('recentportfolio', __('Recent Portfolio Items'), $widget_ops, $control_ops);
	}

	function widget($args, $instance) {
		global $wpdb,$SPT,$post;
		extract($args);
		$title = apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title'], $instance);
		$quantity = (!$instance['quantity']) ? 1 : $instance['quantity'];
		echo $before_widget;
		if (!empty($title)) { echo $before_title . $title . $after_title; }
		$args2 = array(
					'showposts' => $quantity,
					'category_name' => 'portfolio',
					'orderby' => 'date',
					'order' => 'DESC'
				);
				query_posts($args2); if (have_posts()) : while (have_posts()) : the_post();
				
				$portfolio_title = get_post_meta($post->ID, 'portfolio_title',true);
				$portfolio_details = get_post_meta($post->ID, 'portfolio_details',true);
				$portfolio_increment = get_post_meta($post->ID, 'portfolio_increment',true);
				$portfolio_list = NULL;
				for($x=1; $x <= $portfolio_increment; $x++) {
					$stop = false;
					$portfolio_id = get_post_meta($post->ID, 'portfolio_'.$x.'_id',true);
					$portfolio_info = ($portfolio_id) ? get_post($portfolio_id) : NULL;
					$portfolio_link = $portfolio_info->guid;
					if ((1 == $x) && (!$stop)){
						$portfolio_image = '<a href="'.$portfolio_link.'" rel="'.$post->ID.'" class="portfolio_img" ><figure><img src="'.$portfolio_link.'" /></figure></a>';
						$stop = true;
					}
					if($x >= 2) {
						$multi_portfolio_img = true;
						$portfolio_list .= '<a href="'.$portfolio_link.'" rel="'.$portfolio_title.'-'.$post->ID.'"><img src="'.$portfolio_link.'" /></a>'.chr(13);
					}
				}
				?>
                <article class="portfolio_item">
					<?=$portfolio_image;?>
                    <div style="display:none;">
                    <?=($multi_portfolio_img)? $portfolio_list : NULL; ?>
                    </div>
					<h3><?=$portfolio_title;?></h3>
					<div class="name"><?=$portfolio_details;?></div>
				</article>
				<?php
				endwhile; endif; 
		wp_reset_query();
		echo $after_widget;
	}

	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['quantity'] = $new_instance['quantity'];
		return $instance;
	}

	function form($instance) {
		$instance = wp_parse_args((array) $instance, array('title'=>''));
		$title = strip_tags($instance['title']);
		$quantity = format_to_edit($instance['quantity']);
		?>
            <p><label for="<?=$this->get_field_id('title');?>"><?php _e('Title:'); ?></label>
            <input class="widefat" id="<?=$this->get_field_id('title');?>" name="<?=$this->get_field_name('title');?>" type="text" value="<?=esc_attr($title);?>" maxlength="64" /></p>
            <p>
            	<label for="<?=$this->get_field_id('quantity');?>">How many items would you like to display?</label>
                <select name="<?=$this->get_field_name('quantity');?>" id="<?=$this->get_field_id('quantity');?>">
            	<?php
				for ($x=1;$x<=10;$x++) {
					$sel = ($quantity == $x) ? 'selected="selected"' : NULL;
					echo '<option value="'.$x.'" '.$sel.'>'.$x.'</option>'.chr(13);	
				}
				?>
                </select>
            </p>
		<?php
	}
	
}

function register_all_widgets() {
	register_widget('Recentportfolio');
	register_widget('TEMPLATE_SIDEMENU');
	register_widget('Code_Widget');
}
add_action('widgets_init', 'register_all_widgets');
