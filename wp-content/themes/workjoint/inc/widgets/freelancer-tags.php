<?php

class Freeio_Widget_Freelancer_Tags extends WP_Widget {
    public function __construct() {
        parent::__construct(
            'apus_freelancer_tags',
            esc_html__('Freelancer Detail:: Tags', 'freeio'),
            array( 'description' => esc_html__( 'Show freelancer tags', 'freeio' ), )
        );
        $this->widgetName = 'freelancer_tags';
    }

    public function widget( $args, $instance ) {
        get_template_part('widgets/freelancer-tags', '', array('args' => $args, 'instance' => $instance));
    }
    
    public function form( $instance ) {
        $defaults = array(
            'title' => '',
        );
        $instance = wp_parse_args((array) $instance, $defaults);
        // Widget admin form
        ?>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id( 'title' )); ?>"><?php esc_html_e( 'Title:', 'freeio' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id( 'title' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'title' )); ?>" type="text" value="<?php echo esc_attr( $instance['title'] ); ?>" />
        </p>
<?php
    }

    public function update( $new_instance, $old_instance ) {
        return $new_instance;

    }
}

call_user_func( implode('_', array('register', 'widget') ), 'Freeio_Widget_Freelancer_Tags' );
