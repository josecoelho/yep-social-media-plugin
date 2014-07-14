<?php
/*
Plugin Name: YEP Social Media Widjet
Plugin URI: http://yepdev.com
Description: Simple plugin to add a widget with links to social media
Version: 1.0
Author: José Coelho
Author URI: http://josecoelho.com
*/


add_action( 'widgets_init', 'yep_social_media_widget_init' );
add_action( 'wp_enqueue_scripts', 'yep_social_media_widget_stylesheet' );

function yep_social_media_widget_init() {
  register_widget( 'yep_social_media_Widget' );
}

function yep_social_media_widget_stylesheet() {
    wp_register_style( 'yep_social_media_widget-style', plugins_url('style.css', __FILE__) );
    wp_enqueue_style( 'yep_social_media_widget-style' );
}


class yep_social_media_widget extends WP_Widget {

  var $social_options = array(
    'facebook' => array('label'=>'Facebook'),
    'twitter' => array('label'=>'Twitter'),
    'youtube' => array('label'=>'Youtube'),
    'picasa' => array('label'=>'Picasa'),
  );

  function yep_social_media_Widget() {

    /* Widget settings */
    $widget_ops = array( 'classname' => 'yep_social_media_widget', 'description' => __('Mostrar links para mídias sociais', 'Triton') );

    /* Create the widget */
    $this->WP_Widget( 'yep_social_media_widget', __('Mídias sociais', 'Triton'), $widget_ops );

  }

  function widget( $args, $instance ) {
    extract( $args );

    /* Our variables from the widget settings. */
    $title = apply_filters('widget_title', $instance['title'] );
    $num = $instance['num'];
    /* Before widget (defined by themes). */
    echo $before_widget;

    /* Display the widget title if one was input (before and after defined by themes). */
    if ( $title )
      echo $before_title . $title . $after_title;
    ?>
    <ul>
      <?php foreach ($this->social_options as $name => $options) { ?>
          <?php $url_key = $name.'_url'; ?>
          <?php if($instance[$url_key]) { ?>
            <li class="<?php echo $name; ?>"><a title="<?php echo $options['label']; ?>"  href="<?php echo $instance[$url_key] ?>" target="__blank"><?php echo $options['label']; ?></a></li>
          <?php } ?>
      <?php } ?>
    </ul>
    <p>&nbsp;</p>
    <?php
    /* After widget (defined by themes). */
    echo $after_widget;
  }

  function update( $new_instance, $old_instance ) {
    $instance = $old_instance;


    $instance['title'] = wp_filter_nohtml_kses( $new_instance['title'] );
    foreach ($this->social_options as $name => $options) {
      $url_key = $name.'_url';
      $instance[$url_key] = wp_filter_nohtml_kses( $new_instance[$url_key] );
    }

    /* Strip tags for title and name to remove HTML (important for text inputs). */
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
      'title' => ''
    );
    $instance = wp_parse_args( (array) $instance, $defaults ); ?>

    <!-- Widget Title: Text Input -->
    <p>
      <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Título:') ?></label>
      <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" />
    </p>

   <?php foreach ($this->social_options as $name => $options) { ?>
        <?php $url_key = $name.'_url'; ?>
        <p>
          <label for="<?php echo $this->get_field_id( $url_key ); ?>"><?php _e($options['label']) ?></label>
          <input class="widefat" id="<?php echo $this->get_field_id( $url_key ); ?>" name="<?php echo $this->get_field_name( $url_key ); ?>" value="<?php echo $instance[ $url_key ]; ?>" />
        </p>
    <?php } ?>

  <?php
  }

}
?>
