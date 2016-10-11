<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Wpsb_Base_Element extends WP_Widget {

    public $form_args;
    public $id_base;

    /**
     * Wpsb_Base_Element constructor.
     * @param string $name
     * @param array $widget_options
     * @param array $control_options
     * @param array $form
     * @param array $demo
     */
    function __construct($name, $id_base, $widget_options = array(), $control_options = array(), $form = array(), $demo = array()){
        parent::__construct( $id_base, $name, $widget_options);
        $this->id_base = $id_base;
        $this->form_args = $form;
    }

    /**
     * Update the widget.
     *
     * @param array $old
     * @param array $new
     * @return array
     */
    function update($new, $old) {

        foreach($this->form_args as $field_id => $field_args) {
            if($field_args['type'] == 'checkbox') {
                $new[$field_id] = !empty($new[$field_id]);
            }
        }

        return $new;
    }

    /**
     * Display the form for the widget. Auto generated from form array.
     *
     * @param array $instance
     * @return string|void
     */
    public function form($instance){

        ?>
            <?php
            foreach($this->form_args as $field_id => $field_args) {
                if(isset($field_args['default']) && !isset($instance[$field_id])) {
                    $instance[$field_id] = $field_args['default'];
                }
                if(!isset($instance[$field_id])) $instance[$field_id] = false;

                ?>
                <p><label for="<?php echo $this->get_field_id( $field_id ); ?>"><?php echo esc_html($field_args['label']) ?></label><?php

                    if($field_args['type'] != 'checkbox') echo '<br />';

                    switch($field_args['type']) {
                        case 'text' :
                            ?><input type="text" class="widefat" id="<?php echo $this->get_field_id( $field_id ); ?>" name="<?php echo $this->get_field_name( $field_id ); ?>" value="<?php echo esc_attr($instance[$field_id]) ?>" /><?php
                            break;
                        case 'textarea' :
                            if(empty($field_args['height'])) $field_args['height'] = 6;
                            ?><textarea class="widefat" id="<?php echo $this->get_field_id( $field_id ); ?>" name="<?php echo $this->get_field_name( $field_id ); ?>" rows="<?php echo intval($field_args['height']) ?>"><?php echo esc_textarea($instance[$field_id]) ?></textarea><?php
                            break;
                        case 'number' :
                            ?><input type="number" class="small-text" id="<?php echo $this->get_field_id( $field_id ); ?>" name="<?php echo $this->get_field_name( $field_id ); ?>" value="<?php echo floatval($instance[$field_id]) ?>" /><?php
                            break;
                        case 'checkbox' :
                            ?><input type="checkbox" class="small-text" id="<?php echo $this->get_field_id( $field_id ); ?>" name="<?php echo $this->get_field_name( $field_id ); ?>" <?php checked(!empty($instance[$field_id])) ?>/><?php
                            break;
                        case 'select' :
                            ?>
                            <select id="<?php echo $this->get_field_id( $field_id ); ?>" name="<?php echo $this->get_field_name( $field_id ); ?>">
                                <?php foreach($field_args['options'] as $k => $v) : ?>
                                    <option value="<?php echo esc_attr($k) ?>" <?php selected($instance[$field_id], $k) ?>><?php echo esc_html($v) ?></option>
                                <?php endforeach; ?>
                            </select>
                            <?php
                            break;
                    }
                    if(!empty($field_args['description'])) echo '<small class="description">'.esc_html($field_args['description']).'</small>';

                    ?></p>
                <?php
            }
            ?>
        <?php
    }

    /**
     * Render the widget.
     *
     * @param array $args
     * @param array $instance
     * @return bool|void
     */
    function widget($args, $instance){

        // Set up defaults for all the widget args
        foreach($this->form_args as $field_id => $field_args) {
            if(isset($field_args['default']) && !isset($instance[$field_id])) {
                $instance[$field_id] = $field_args['default'];
            }
            if(!isset($instance[$field_id])) $instance[$field_id] = false;
        }

        // Filter the title
        if(!empty($instance['title'])) {
            $instance['title'] = apply_filters('widget_title', $instance['title'], $instance, $this->id_base);
        }


        if(empty($template)) $template = 'default';

        /*echo $args['before_widget'];
        echo '<div class="'.esc_attr(implode(' ', $widget_classes) ).'">';
        include $template_file;
        echo '</div>';
        echo $args['after_widget'];*/


    }
}

