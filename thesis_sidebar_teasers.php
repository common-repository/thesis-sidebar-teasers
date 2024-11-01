<?php
/**
 * @package Thesis Sidebar Teasers
 * @author Melvin Ram
 * @version 1.2.1
 */
/*
Plugin Name: Thesis Sidebar Teasers
Plugin URI: http://www.webdesigncompany.net/wordpress/plugins/thesis-sidebar-teasers
Description: Displays content from a category inside a widget
Version: 1.2.1
Author URI: http://www.webdesigncompany.net/
*/

global $wp_version;

$exit_msg = 'Thesis Sidebar Teasers for WordPress requires Wordpress 2.8 or newer. <a href="http://codex.wordpress.org/Upgrading_WordPress">Please update!</a>';

if (version_compare($wp_version, "2.8","<")){ exit ($exit_msg); }

require_once 'wdc/wdc.class.php';

class Thesis_Sidebar_Teasers_Widget extends WP_Widget {

  function Thesis_Sidebar_Teasers_Widget() {
    $widget_ops = array( 'classname' => 'thesis_sidebar_teasers', 'description' => 'Displays content from a category inside a widget' );
    $control_ops = array( 'width' => 200, 'height' => 250, 'id_base' => 'thesis_sidebar_teasers' );
    $this->WP_Widget( 'thesis_sidebar_teasers', 'Thesis Sidebar Teasers', $widget_ops, $control_ops );
  }

  function widget( $args, $instance ) {
    extract( $args );
    echo $before_widget;
    $title = apply_filters('widget_title', $instance['title'] );
    if ( $title ) {
      echo $before_title . $title . $after_title;
    }
    sitespress_thesis_sidebar_teasers($instance['cat'], $instance['posts']);
    echo $after_widget;
  }

  function update( $new_instance, $old_instance ) {
    $instance = $old_instance;
    foreach ( array('title') as $val ) {
      $instance[$val] = strip_tags( $new_instance[$val] );
    }
    $instance['cat'] = $new_instance['cat'];
    $instance['posts'] = $new_instance['posts'];
    return $instance;
  }

  function form( $instance ) {
    $defaults = array( 
      'title'     => '', 
      'cat' => '',
      'posts' => '5'
    );
    $instance = wp_parse_args( (array) $instance, $defaults ); ?>
    <p>
      <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e("Title"); ?>:</label>
      <input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" style="width:95%;" />
    </p>
    <p>
      <label for="<?php echo $this->get_field_id( 'cat' ); ?>"><?php _e("Category"); ?>:</label>
      <input id="<?php echo $this->get_field_id( 'cat' ); ?>" name="<?php echo $this->get_field_name( 'cat' ); ?>" value="<?php echo $instance['cat']; ?>" style="width:100px;" /> (slug)
    </p>
    <p>
      <label for="<?php echo $this->get_field_id( 'posts' ); ?>"><?php _e("Posts"); ?>:</label>
      <input id="<?php echo $this->get_field_id( 'posts' ); ?>" name="<?php echo $this->get_field_name( 'posts' ); ?>" value="<?php echo $instance['posts']; ?>" style="width:20px;" /> (default: 5)
    </p>
  <?php 
  }
  
  function wdc_plugins($plugins) {
  	if ( is_array($plugins) ) {
  		$plugins[] = 'Thesis Sidebar Teasers';
  	}
  	return $plugins;
  }
}

function thesis_sidebar_teasers_widget_func() {
  register_widget( 'Thesis_Sidebar_Teasers_Widget' );
}

add_action( 'widgets_init', 'thesis_sidebar_teasers_widget_func' );
  
function sitespress_thesis_sidebar_teasers($category, $posts = "5") { 
  $query_string = "category_name=" . $category . "&showposts=" . $posts;
  $my_query = new WP_Query($query_string); 
  while ($my_query->have_posts()) : $my_query->the_post(); 
    thesis_teaser('sidebar-teasers');
  endwhile; 
}  

add_filter('wdc_plugins', array('Thesis_Sidebar_Teasers_Widget', 'wdc_plugins'));

?>