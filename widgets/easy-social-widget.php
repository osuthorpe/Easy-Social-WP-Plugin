<?php
class socialIcons extends WP_Widget {
  function socialIcons() {
  	parent::WP_Widget('my_widget_base', 'Easy Social Icons', array('description' => 'Very simple widget example', 'class' => 'my-widget-class'));
  }

  function widget( $args, $instance ) {
    extract( $args );
    $title = apply_filters( 'widget_title', $instance['title'] );

	echo $before_widget;

	if ($title) {
		echo $before_title . $title . $after_title;
	}
	
	echo '<div class="easy-social-icons">'; ?>

	<a href="<?php bloginfo('rss2_url'); ?>" target="_blank" class="social-icon rss-icon">rss</a>
	
	<?php if(get_option('easy_social_facebook') != "") {
		echo '<a href="'.get_option('easy_social_facebook').'" target="_blank" class="social-icon facebook-icon">facebook</a>';
	}
	
	if(get_option('easy_social_googleplus') != "") {
		echo '<a href="'.get_option('easy_social_googleplus').'" target="_blank" class="social-icon googleplus-icon">google+</a>';
	}
	
	if(get_option('easy_social_twitter') != "") {
		echo '<a href="http://www.twitter.com/'.get_option('easy_social_twitter').'" target="_blank" class="social-icon twitter-icon">twitter</a>';
	}
	
	if(get_option('easy_social_youtube') != "") {
		echo '<a href="'.get_option('easy_social_youtube').'" target="_blank" class="social-icon youtube-icon">youtube</a>';
	}
	
	if(get_option('easy_social_vimeo') != "") {
		echo '<a href="'.get_option('easy_social_vimeo').'" target="_blank" class="social-icon vimeo-icon">vimeo</a>';
	}
	
	if(get_option('easy_social_flikr') != "") {
		echo '<a href="'.get_option('easy_social_flikr').'" target="_blank" class="social-icon flikr-icon">flikr</a>';	
	}
	
	echo '</div>';
	echo $after_widget;
  }

  function update( $new_instance, $old_instance ) {
    return $new_instance;
  }

  function form( $instance ) {
    $title = esc_attr( $instance['title'] );
    ?>

    <p>
      <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?>
      <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" />
      </label>
    </p>
    <?php
  }
}

add_action( 'widgets_init', 'socialIconInit' );

function socialIconInit() {
  register_widget( 'socialIcons' );
}
?>