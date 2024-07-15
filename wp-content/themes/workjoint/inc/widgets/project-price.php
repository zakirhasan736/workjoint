<?php

class Freeio_Widget_Project_Price extends WP_Widget {
    public function __construct() {
        parent::__construct(
            'apus_project_price',
            esc_html__('Project Single:: Project Price', 'freeio'),
            array( 'description' => esc_html__( 'Show project price', 'freeio' ), )
        );
        $this->widgetName = 'project_price';
    }

    public function widget( $args, $instance ) {
        get_template_part('widgets/project-price', '', array('args' => $args, 'instance' => $instance));
    }
    
    public function form( $instance ) {
        $defaults = array(
            'title' => '',
        );
        $instance = wp_parse_args((array) $instance, $defaults);
        
        ?>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id( 'title' )); ?>"><?php esc_html_e( 'Title:', 'freeio' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id( 'title' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'title' )); ?>" type="text" value="<?php echo esc_attr( $instance['title'] ); ?>" />
        </p>
<?php
    }

    public function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
        return $instance;

    }
}

call_user_func( implode('_', array('register', 'widget') ), 'Freeio_Widget_Project_Price' );
