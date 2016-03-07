<?php

/**
 * Custom Categories Widget
 * easyread Theme
 */
class easyread_categories extends WP_Widget
{
	 function easyread_categories(){

        $widget_ops = array('classname' => 'easyread-cats','description' => esc_html__( "Easyread widget to display categories" ,'easyread') );
		    parent::__construct('easyread-cats', esc_html__('Easyread Categories','easyread'), $widget_ops);
    }

    function widget($args , $instance) {
    	extract($args);
        $title = isset($instance['title']) ? esc_html( $instance['title'] ) : esc_html__('Categories' , 'easyread');
        $enable_count = '';
        if(isset($instance['enable_count']))
        $enable_count = $instance['enable_count'] ? $instance['enable_count'] : 'checked';

        $limit = isset($instance['limit']) ? esc_html( $instance['limit'] ) : 4;


      echo $before_widget;
      echo $before_title;
      echo $title;
      echo $after_title;

		/**
		 * Widget Content
		 */

		?>


    <div class="cats-widget">

        <ul><?php
        if($enable_count != '') {
              $args = array (
              'echo' => 0,
              'show_count' => 1,
              'title_li' => '',
              'depth' => 1 ,
              'orderby' => 'count' ,
              'order' => 'DESC' ,
              'number' => $limit
              );
        }
        else{
            $args = array (
              'echo' => 0,
              'show_count' => 0,
              'title_li' => '',
              'depth' => 1 ,
              'orderby' => 'count' ,
              'order' => 'DESC' ,
              'number' => $limit
              );
        }
    $variable = wp_list_categories($args);
    $variable = str_replace ( "(" , "<span>", $variable );
    $variable = str_replace ( ")" , "</span>", $variable );
    echo $variable; ?></ul>

    </div><!-- end widget content -->

		<?php

		echo $after_widget;
    }


    function form($instance) {
      if(!isset($instance['title'])) $instance['title'] = esc_html__('Categories' , 'easyread');
      if(!isset($instance['limit'])) $instance['limit'] = 4;
      if(!isset($instance['enable_count'])) $instance['enable_count'] = '';


    	?>

      <p><label for="<?php echo $this->get_field_id('title'); ?>"><?php esc_html_e('Title ','easyread') ?></label>

        <input  type="text" value="<?php echo esc_attr($instance['title']); ?>"
                name="<?php echo $this->get_field_name('title'); ?>"
                id="<?php $this->get_field_id('title'); ?>"
                class="widefat" />
      </p>

      <p><label for="<?php echo $this->get_field_id('limit'); ?>"> <?php esc_html_e('Limit Categories ','easyread') ?></label>

        <input  type="number" value="<?php echo esc_attr($instance['limit']); ?>"
                name="<?php echo $this->get_field_name('limit'); ?>"
                id="<?php $this->get_field_id('limit'); ?>"
                class="widefat" />
      </p>

      <p><label>
        <input  type="checkbox"
                name="<?php echo $this->get_field_name('enable_count'); ?>"
                id="<?php $this->get_field_id('enable_count'); ?>" <?php if($instance['enable_count'] != '') echo 'checked=checked '; ?>
         />
         <?php esc_html_e('Enable Posts Count','easyread') ?></label>
       </p>

    	<?php
    }

    /**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? esc_html( $new_instance['title'] ) : '';
		$instance['limit'] = ( ! empty( $new_instance['limit'] ) && is_numeric( $new_instance['limit'] )  ) ? esc_html( $new_instance['limit'] ) : '';

		return $instance;
	}
}

?>
