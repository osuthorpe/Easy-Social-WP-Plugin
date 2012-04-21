<?php 
	/*
	Plugin Name: Easy Social
	Plugin URI: http://www.alexthorpe.com
	Description: This plug in gives you all the social media you need with none of the bloat. Easily add facebbok, google+ and twitter to each post and a widget with custom icons.
	Version: 1.0
	Author: Alex Thorpe
	Author URI: http://www.alexthorpe.com/
	License: GPL2
	*/
	
	/*  
		Copyright 2011  Alex Thorpe  (email : osuthorpe@gmail.com)
	
	    This program is free software; you can redistribute it and/or modify
	    it under the terms of the GNU General Public License, version 2, as 
	    published by the Free Software Foundation.
	
	    This program is distributed in the hope that it will be useful,
	    but WITHOUT ANY WARRANTY; without even the implied warranty of
	    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	    GNU General Public License for more details.
	
	    You should have received a copy of the GNU General Public License
	    along with this program; if not, write to the Free Software
	    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
	*/
	
	//Add plugin options page to settings tab
	
	
	function add_css() {
		echo '<link type="text/css" rel="stylesheet" href="' . get_bloginfo('wpurl') . '/wp-content/plugins/easy-social/style.css" />' . "\n";
	}
	
	
	//Adds Social Javascript
	function add_social_js() { ?>
		
		<div id="fb-root"></div>
		<script>
			//facebook JS SDK
			(function(d, s, id) {
			  var js, fjs = d.getElementsByTagName(s)[0];
			  if (d.getElementById(id)) return;
			  js = d.createElement(s); js.id = id;
			  js.src = "//connect.facebook.net/en_US/all.js#xfbml=1";
			  fjs.parentNode.insertBefore(js, fjs);
			}(document, 'script', 'facebook-jssdk'));
 	
		  	//Twitter JS SDK
		  	!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");
		  	
		  	//Stumbleupon JS SDK
		  	(function() { 
				var li = document.createElement('script'); li.type = 'text/javascript'; li.async = true; 
				li.src = 'https://platform.stumbleupon.com/1/widgets.js'; 
				var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(li, s); 
			})(); 
		</script>
		<script type="text/javascript" ;="" src="http://apis.google.com/js/plusone.js"></script> 
		<script src="http://platform.linkedin.com/in.js" type="text/javascript"></script>
	<?php }
	
	
	//Adds Social Media Buttons to posts & pages
	function add_social_buttons($content) {
		$social .= '<div id="easy-social-buttons">';
		
		//Facebook Button
		$social .= '<div class="easy-social-button"><div class="fb-like" data-send="true" data-layout="button_count" data-show-faces="false"></div></div>';
		
		//Twitter Send
		$social .= '<div class="easy-social-button"><a href="https://twitter.com/share" class="twitter-share-button">Tweet</a></div>';
		
		//Google+ Button
		$social .= '<div class="easy-social-button"><g:plusone size="medium"></g:plusone></div>';
		
		//Linkedin Button
		$social .= '<div class="easy-social-button"><script type="IN/Share" data-counter="right"></script></div>';
		
		//Stumbleupon Button
		$social .= '<div class="easy-social-button"><su:badge layout="1"></su:badge></div>';
		
		$social .= '</div>';
		
		return $content . $social;
		
	}
	
	//Include Widget
	include('widgets/easy-social-widget.php');
	include('widgets/easy-tabs-widget.php');
	
	//Add buttons to single pages
	add_action("wp", "load_social");
	
	function load_social() {
		if(is_single() && !is_admin()) {
			add_action('wp_footer','add_social_js');
	        add_filter('the_content','add_social_buttons');
		}
		if(!is_admin()) {
			add_action('wp_head','add_css');
		}
	}
	
	function easy_social_admin() {
		include('easy-social-admin.php');
	}
	
	function easy_admin_actions() {
	    add_options_page('Easy Social Settings', 'Easy Social Settings', 'manage_options', 'bk-easy-social', 'easy_social_admin');
	}
	
	add_action('admin_menu', 'easy_admin_actions');

?>