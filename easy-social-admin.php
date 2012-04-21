<?php 
	if($_POST['easy_social_hidden'] == 'Y') {
		//Form data sent
		$twitter = $_POST['easy_social_twitter'];
		update_option('easy_social_twitter', $twitter);
		
		$facebook = $_POST['easy_social_facebook'];
		update_option('easy_social_facebook', $facebook);
		
		$googleplus = $_POST['easy_social_googleplus'];
		update_option('easy_social_googleplus', $googleplus);
		
		$linkedin = $_POST['easy_social_linkedin'];
		update_option('easy_social_linkedin', $linkedin);

		$youtube = $_POST['easy_social_youtube'];
		update_option('easy_social_youtube', $youtube);

		$vimeo = $_POST['easy_social_vimeo'];
		update_option('easy_social_vimeo', $vimeo);
		
		?>
		<div class="updated"><p><strong><?php _e('Options saved.' ); ?></strong></p></div>
		<?php
	} else {
		//Normal page display
		$twitter = get_option('easy_social_twitter');
		$facebook = get_option('easy_social_facebook');
		$googleplus = get_option('easy_social_googleplus');
		$linkedin = get_option('easy_social_linkedin');
		$youtube = get_option('easy_social_youtube');
		$vimeo = get_option('easy_social_vimeo');
	}
	
	
?>

<div class="wrap">
<?php    echo "<h2>" . __( 'Easy Social Settings', 'easy_social_trdom' ) . "</h2>"; ?>

<form name="easy_social_form" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
	<input type="hidden" name="easy_social_hidden" value="Y">
	<?php    echo "<h4>" . __( 'inclue your social media sites bellow. Any left blank will not display in widget', 'easy_social_trdom' ) . "</h4>"; ?>
	<p><?php _e("Twitter Username: " ); ?><input type="text" name="easy_social_twitter" value="<?php echo $twitter; ?>" size="20"></p>
	<p><?php _e("Facebook URL: " ); ?><input type="text" name="easy_social_facebook" value="<?php echo $facebook; ?>" size="40"></p>
	<p><?php _e("Google+ URL: " ); ?><input type="text" name="easy_social_googleplus" value="<?php echo $googleplus; ?>" size="40"></p>
	<p><?php _e("Linkedin URL: " ); ?><input type="text" name="easy_social_linkedin" value="<?php echo $linkedin; ?>" size="40"></p>
	<p><?php _e("Vimeo URL: " ); ?><input type="text" name="easy_social_vimeo" value="<?php echo $vimeo; ?>" size="40"></p>
	<p><?php _e("Youtube URL: " ); ?><input type="text" name="easy_social_youtube" value="<?php echo $youtube; ?>" size="40"></p>


	<p class="submit">
	<input type="submit" name="Submit" value="<?php _e('Update Options', 'easy_social_trdom' ) ?>" />
	</p>
</form>

<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
	<p>If you find this plugin useful consider a donation, half of the proceeds go charity and the other half goes to my college tuition, a win win.</p>
	<input type="hidden" name="cmd" value="_s-xclick">
	<input type="hidden" name="hosted_button_id" value="6C2XMVL58ZS7A">
	<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
	<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
</form>
</div>