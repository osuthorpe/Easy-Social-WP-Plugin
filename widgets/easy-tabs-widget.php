<?php
/*
 * Plugin Name: Custom Tabbed Widget
 * Plugin URI: http://www.ormanclark.com
 * Description: A widget that display popular posts, recent posts, recent comments and tags
 * Version: 1.0
 * Author: Orman Clark
 * Author URI: http://www.ormanclark.com
 */

/*
 * tabd function to widgets_init that'll lotab our widget.
 */
add_action( 'widgets_init', 'tz_tab_widgets' );

/*
 * Register widget.
 */
function tz_tab_widgets() {
	register_widget( 'TZ_Tab_Widget' );
}

function add_js(){
if (is_active_widget(false, false, 'tz_tab_widget') && (!is_admin())){ ?>
	<script>
		jQuery(document).ready(function() {	
		  //Get all the LI from the #tabMenu UL
		  $('#tabMenu li').click(function(){
		    
		    //perform the actions when it's not selected
		    if (!$(this).hasClass('selected')) {    
		           
			    //remove the selected class from all LI    
			    $('#tabMenu li').removeClass('selected');
			    
			    //Reassign the LI
			    $(this).addClass('selected');
			    
			    //Hide all the DIV in .boxBody
			    $('.boxBody div.parent').slideUp('1500');
			    
			    //Look for the right DIV in boxBody according to the Navigation UL index, therefore, the arrangement is very important.
			    $('.boxBody div.parent:eq(' + $('#tabMenu > li').index(this) + ')').slideDown('1500');
			    
			 }
		    
		  }).mouseover(function() {
		
		    //Add and remove class, Personally I dont think this is the right way to do it, anyone please suggest    
		    $(this).addClass('mouseover');
		    $(this).removeClass('mouseout');   
		    
		  }).mouseout(function() {
		    
		    //Add and remove class
		    $(this).addClass('mouseout');
		    $(this).removeClass('mouseover');    
		    
		  });
		
			//Mouseover with animate Effect for Category menu list
		  $('.boxBody #category li').click(function(){
		
		    //Get the Anchor tag href under the LI
		    window.location = $(this).children().attr('href');
		  }).mouseover(function() {
		
		    //Change background color and animate the padding
		    $(this).css('backgroundColor','#888');
		    $(this).children().animate({paddingLeft:"20px"}, {queue:false, duration:300});
		  }).mouseout(function() {
		    
		    //Change background color and animate the padding
		    $(this).css('backgroundColor','');
		    $(this).children().animate({paddingLeft:"0"}, {queue:false, duration:300});
		  });  
			
			//Mouseover effect for Posts, Comments, Famous Posts and Random Posts menu list.
		  $('#.boxBody li').click(function(){
		    window.location = $(this).children().attr('href');
		  }).mouseover(function() {
		    $(this).css('backgroundColor','#888');
		  }).mouseout(function() {
		    $(this).css('backgroundColor','');
		  });  	
			
		});
	</script>
	
	<div id="fb-root"></div>
	<script>(function(d, s, id) {
	  var js, fjs = d.getElementsByTagName(s)[0];
	  if (d.getElementById(id)) return;
	  js = d.createElement(s); js.id = id;
	  js.src = "//connect.facebook.net/en_US/all.js#xfbml=1";
	  fjs.parentNode.insertBefore(js, fjs);
	}(document, 'script', 'facebook-jssdk'));</script>
<?php	}
}

add_action('wp_head', 'add_js');

/*
 * Widget class.
 */
class tz_tab_widget extends WP_Widget {

	/* ---------------------------- */
	/* -------- Widget setup -------- */
	/* ---------------------------- */
	
	function TZ_tab_Widget() {
	
		/* Widget settings */
		$widget_ops = array( 'classname' => 'tz_tab_widget', 'description' => __('A tab for recent tweets, facebook fanpage, linkedin posts and flickr photos.', 'framework') );

		/* Create the widget */
		$this->WP_Widget( 'tz_tab_widget', __('Easy Social Tabs', 'framework'), $widget_ops, $control_ops );
	}

	/* ---------------------------- */
	/* ------- Display Widget -------- */
	/* ---------------------------- */
	
	function widget( $args, $instance ) {
		global $wpdb;
		extract( $args );

		/* Our variables from the widget settings. */
		$title = apply_filters('widget_title', $instance['title'] );
	

		/* Before widget (defined by themes). */
		echo $before_widget;

		/* Display the widget title if one was input (before and after defined by themes). */
		if ( $title )
			echo $before_title . $title . $after_title; ?>
			
		<div class="box">
	<ul id="tabMenu">
	  <li class="posts selected"></li>
	  <li class="comments"></li>
	  <li class="category"></li>
	  <li class="famous"></li>
	  <li class="random"></li>
	  <li class="stumbleupon"></li>
	  <li class="rss"></li>
	</ul>
	<div class="boxTop"></div>
	
	<div class="boxBody">
	  
	  <div id="posts" class="show parent">
	    <?php
 
			/**
			 * TWITTER FEED PARSER
			 * 
			 * @version	1.1.1
			 * @author	Jonathan Nicol
			 * @link	http://f6design.com/journal/2010/10/07/display-recent-twitter-tweets-using-php/
			 * 
			 * Notes:
			 * We employ caching because Twitter only allows their RSS feeds to be accesssed 150
			 * times an hour per user client.
			 * --
			 * Dates can be displayed in Twitter style (e.g. "1 hour ago") by setting the 
			 * $twitter_style_dates param to true.
			 * 
			 * Credits:
			 * Hashtag/username parsing based on: http://snipplr.com/view/16221/get-twitter-tweets/
			 * Feed caching: http://www.addedbytes.com/articles/caching-output-in-php/
			 * Feed parsing: http://boagworld.com/forum/comments.php?DiscussionID=4639
			 */
			 
			function display_latest_tweets(
				$twitter_user_id,
				$cache_file = './twitter.txt',
				$tweets_to_display = 100,
				$ignore_replies = false,
				$twitter_wrap_open = '<h4>Latest tweets</h4><ul id="twitter">',
				$twitter_wrap_close = '</ul>',
				$tweet_wrap_open = '<li><span class="status">',
				$meta_wrap_open = '</span><span class="meta"> ',
				$meta_wrap_close = '</span>',
				$tweet_wrap_close = '</li>',
				$date_format = 'g:i A M jS',
				$twitter_style_dates = false){
			 
				// Seconds to cache feed (1 hour).
				$cachetime = 60*60;
				// Time that the cache was last filled.
				$cache_file_created = ((@file_exists($cache_file))) ? @filemtime($cache_file) : 0;
			 
				// A flag so we know if the feed was successfully parsed.
				$tweet_found = false;
			 
				// Show file from cache if still valid.
				if (time() - $cachetime < $cache_file_created) {
			 
					$tweet_found = true;
					// Display tweets from the cache.
					@readfile($cache_file);	
			 
				} else {
			 
					// Cache file not found, or old. Fetch the RSS feed from Twitter.
					$rss = @file_get_contents('http://twitter.com/statuses/user_timeline/'.$twitter_user_id.'.rss');
			 
					if($rss) {
			 
						// Parse the RSS feed to an XML object.
						$xml = @simplexml_load_string($rss);
			 
						if($xml !== false) {
			 
							// Error check: Make sure there is at least one item.
							if (count($xml->channel->item)) {
			 
								$tweet_count = 0;
			 
								// Start output buffering.
								ob_start();
			 
								// Open the twitter wrapping element.
								$twitter_html = $twitter_wrap_open;
			 
								// Iterate over tweets.
								foreach($xml->channel->item as $tweet) {
			 
									// Twitter feeds begin with the username, "e.g. User name: Blah"
									// so we need to strip that from the front of our tweet.
									$tweet_desc = substr($tweet->description,strpos($tweet->description,":")+2);
									$tweet_desc = htmlspecialchars($tweet_desc);
									$tweet_first_char = substr($tweet_desc,0,1);
			 
									// If we are not gnoring replies, or tweet is not a reply, process it.
									if ($tweet_first_char!='@' || $ignore_replies==false){
			 
										$tweet_found = true;
										$tweet_count++;
			 
										// Add hyperlink html tags to any urls, twitter ids or hashtags in the tweet.
										$tweet_desc = preg_replace('/(https?:\/\/[^\s"<>]+)/','<a href="$1">$1</a>',$tweet_desc);
										$tweet_desc = preg_replace('/(^|[\n\s])@([^\s"\t\n\r<:]*)/is', '$1<a href="http://twitter.com/$2">@$2</a>', $tweet_desc);
										$tweet_desc = preg_replace('/(^|[\n\s])#([^\s"\t\n\r<:]*)/is', '$1<a href="http://twitter.com/search?q=%23$2">#$2</a>', $tweet_desc);
			 
			 							// Convert Tweet display time to a UNIX timestamp. Twitter timestamps are in UTC/GMT time.
										$tweet_time = strtotime($tweet->pubDate);	
			 							if ($twitter_style_dates){
											// Current UNIX timestamp.
											$current_time = time();
											$time_diff = abs($current_time - $tweet_time);
											switch ($time_diff) 
											{
												case ($time_diff < 60):
													$display_time = $time_diff.' seconds ago';                  
													break;      
												case ($time_diff >= 60 && $time_diff < 3600):
													$min = floor($time_diff/60);
													$display_time = $min.' minutes ago';                  
													break;      
												case ($time_diff >= 3600 && $time_diff < 86400):
													$hour = floor($time_diff/3600);
													$display_time = 'about '.$hour.' hour';
													if ($hour > 1){ $display_time .= 's'; }
													$display_time .= ' ago';
													break;          
												default:
													$display_time = date($date_format,$tweet_time);
													break;
											}
			 							} else {
			 								$display_time = date($date_format,$tweet_time);
			 							}
			 
										// Render the tweet.
										$twitter_html .= $tweet_wrap_open.$tweet_desc.$meta_wrap_open.'<a href="http://twitter.com/'.$twitter_user_id.'">'.$display_time.'</a>'.$meta_wrap_close.$tweet_wrap_close;
			 
									}
			 
									// If we have processed enough tweets, stop.
									if ($tweet_count >= $tweets_to_display){
										break;
									}
			 
								}
			 
								// Close the twitter wrapping element.
								$twitter_html .= $twitter_wrap_close;
								echo $twitter_html;
			 
								// Generate a new cache file.
								$file = @fopen($cache_file, 'w');
			 
								// Save the contents of output buffer to the file, and flush the buffer. 
								@fwrite($file, ob_get_contents()); 
								@fclose($file); 
								ob_end_flush();
			 
							}
						}
					}
				} 
				// In case the RSS feed did not parse or load correctly, show a link to the Twitter account.
				if (!$tweet_found){
					echo $twitter_wrap_open.$tweet_wrap_open.'Oops, our twitter feed is unavailable right now. '.$meta_wrap_open.'<a href="http://twitter.com/'.$twitter_user_id.'">Follow us on Twitter</a>'.$meta_wrap_close.$tweet_wrap_close.$twitter_wrap_close;
				}
			}
			 
			display_latest_tweets('osuthorpe');
			 
			?>
	  </div>  
	  
	  <div id="comments" class="parent">
	    <div class="fb-like-box" data-href="http://www.facebook.com/pages/OSU-Volunteer-With-Kids/115706665154258" data-width="300" data-show-faces="true" data-stream="true" data-header="true"></div>
	  </div>
	  
	  <div id="category" class="parent">
	    <div id="flickr_badge_uber_wrapper"><a href="http://www.flickr.com" id="flickr_www">www.<strong style="color:#3993ff">flick<span style="color:#ff1c92">r</span></strong>.com</a><div id="flickr_badge_wrapper">
		<script type="text/javascript" src="http://www.flickr.com/badge_code_v2.gne?show_name=1&count=10&display=latest&size=s&layout=x&source=all&user=72947939%40N07"></script>
		<div id="flickr_badge_source">
		<span id="flickr_badge_source_txt">More <a href="http://www.flickr.com/photos/">photos and video</a> on Flickr</span><br clear="all" /></div></div></div>
	  </div>
	  
	  <div id="famous" class="parent">
	    <script type="text/javascript">pid='111297306144520956414';nbp='3';hll='yes';bkg='transparent';txt='0c0c0c';lin='36c';bor='f5f5f5';rad='0';pad='10';wid='290';fav='yes';hea='yes';gwi='yes';pag='no';api='AIzaSyC3Fos8rWwIPqoGWXOPLv5uABcHirt_suo';</script><script type="text/javascript" src="http://gplusapi.appspot.com/b.js"></script> 
	  </div>
	  
	  <div id="random" class="parent">
	    <script src="http://platform.linkedin.com/in.js" type="text/javascript"></script>
		<script type="IN/MemberProfile" data-id="http://www.linkedin.com/pub/alexander-thorpe/b/72/ba" data-format="inline"></script> 
	  </div>   
	  
	  <div id="stumbleupon" class="parent">
	  	<div id="stblpn-w-1326729860760"></div>
		<script type="text/javascript">
			(function() {
			var widget = {
			  id: 'stblpn-w-1326729860760', 
			  version: '1', 
			  layout: '3',
			  title: 'StumbleUpon', 
			  request: {topics: ['Open Source','Software','Web Development']}
			};
			if (window.SuWidget) { if (typeof SuWidget == 'function') { new SuWidget(widget); } else { SuWidget.push(widget); } } else {var e, e1; SuWidget = [widget]; e = document.createElement('SCRIPT'); e.type = 'text/javascript'; e.async = true; e.src = 'http://cdn.stumble-upon.com/js/widgets.js'; e1 = document.getElementsByTagName('SCRIPT')[0]; e1.parentNode.insertBefore(e, e1); } 
			})();
		</script>
	  </div>     
	
		<div id="rss" class="parent">
			<?php if(function_exists('fetch_feed')) {
				include_once(ABSPATH . WPINC . '/feed.php'); // the file to rss feed generator
				$feed = fetch_feed('http://www.brettthompsonracing.com/feed/'); // specify the rss feed
			
				$limit = $feed->get_item_quantity(7); // specify number of items
				$items = $feed->get_items(0, $limit); // create an array of items
			}
			
			if ($limit == 0) echo '<div>The feed is either empty or unavailable.</div>';
			else foreach ($items as $item) : ?>
			
			<h4><a href="<?php echo $item->get_permalink(); ?>" alt="<?php echo $item->get_title(); ?>"><?php echo $item->get_title(); ?></a></h4>
			<p><?php echo $item->get_date('j F Y @ g:i a'); ?></p>
			<p><?php echo substr($item->get_description(), 0, 200); ?> ...</p>
			
			<?php endforeach; ?>
		</div>
	</div>
	
	<div class="boxBottom"></div>

</div>
<?php
		/* After widget (defined by themes). */
		echo $after_widget;
	}

	/* ---------------------------- */
	/* ------- Update Widget -------- */
	/* ---------------------------- */
	
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		/* Strip tags to remove HTML (important for text inputs). */
		$instance['title'] = strip_tags( $new_instance['title'] );

		/* No need to strip tags */
		$instance['tab1'] = $new_instance['tab1'];
		$instance['tab2'] = $new_instance['tab2'];
		$instance['tab3'] = $new_instance['tab3'];
		$instance['tab4'] = $new_instance['tab4'];
		
		return $instance;
	}
	
	/* ---------------------------- */
	/* ------- Widget Settings ------- */
	/* ---------------------------- */
	
	/**
	 * Displays the widget settings controls on the widget panel.
	 * Make use of the get_field_id() and get_field_name() function
	 * when creating your form elements. This handles the confusing stuff.
	 */
	
	function form( $instance ) {
	
		/* Set up some default widget settings. */
		$defaults = array(
		'title' => '',
		'tab1' => 'Popular',
		'tab2' => 'Recent',
		'tab3' => 'Comments',
		'tab4' => 'Tags',
		);
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>

		<!-- Widget Title: Text Input -->
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', 'framework') ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" />
		</p>

		<!-- tab 1 title: Text Input -->
		<p>
			<label for="<?php echo $this->get_field_id( 'tab1' ); ?>"><?php _e('Tab 1 Title:', 'framework') ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'tab1' ); ?>" name="<?php echo $this->get_field_name( 'tab1' ); ?>" value="<?php echo $instance['tab1']; ?>" />
		</p>
		
		<!-- tab 2 title: Text Input -->
		<p>
			<label for="<?php echo $this->get_field_id( 'link1' ); ?>"><?php _e('Tab 2 Title:', 'framework') ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'tab2' ); ?>" name="<?php echo $this->get_field_name( 'tab2' ); ?>" value="<?php echo $instance['tab2']; ?>" />
		</p>
		
		<!-- tab 3 title: Text Input -->
		<p>
			<label for="<?php echo $this->get_field_id( 'tab2' ); ?>"><?php _e('Tab 3 Title:', 'framework') ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'tab3' ); ?>" name="<?php echo $this->get_field_name( 'tab3' ); ?>" value="<?php echo $instance['tab3']; ?>" />
		</p>
		
		<!-- tab 4 title: Text Input -->
		<p>
			<label for="<?php echo $this->get_field_id( 'link2' ); ?>"><?php _e('Tab 4 Title:', 'framework') ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'tab4' ); ?>" name="<?php echo $this->get_field_name( 'tab4' ); ?>" value="<?php echo $instance['tab4']; ?>" />
		</p>
		
	
	<?php
	}
}
?>