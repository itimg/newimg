<?php 
if (!defined('ABSPATH')) { die; }
if (!class_exists('IOSF')) {
    class IOSF
    {
        // constans
        public $unique  = '';
        public $args    = array(
            'class'       => '',
            'form'   => true,
            'nonce'   => true,
            'method' => '',
            'action' => '',
            'fields'      => array(),
            'value'       => array(),
            'hidden'    => array(),
        );

        public function __construct($key, $params){
            $this->unique = $key;
            $this->args   = apply_filters("csf_{$this->unique}_args", wp_parse_args($params, $this->args), $this);
            //$this->enqueue_scripts();
            add_filter('csf_enqueue_assets', '__return_true');
            //add_action( 'csf_enqueue_assets','');
            CSF::add_admin_enqueue_scripts();
            //add_action( 'admin_enqueue_scripts', array( 'CSF', 'add_admin_enqueue_scripts' ) );
            $this->form($params);
        }
        // instance
        public static function instance($key, $params){
            return new self($key, $params);
        } 
        // Back-end widget form.
        public function form(){
            if (!empty($this->args['fields'])) {
                $class = ($this->args['class']) ? ' ' . $this->args['class'] : '';

                // echo esc_attr(json_encode($this->args['value']));
                if ($this->args['form']) {
                    $action = !empty($this->args['action']) ? ' action="' . $this->args['action'] . '"' : '';
                    $method = !empty($this->args['method']) ? ' method="' . $this->args['method'] . '"' : '';
                    echo '<form' . $action . $method . '>';
                }
                echo '<div class="csf io-csf csf-onload' . esc_attr($class) . '">';
                if ($this->args['nonce']) {
                    wp_nonce_field('iosf_nonce', 'iosf_nonce');
                }
                foreach ($this->args['fields'] as $field) {
                    $field_unique = '';
                    if (!empty($field['id'])) {
                        $field_unique = '';
                        $field['default'] = $this->get_default($field);
                    }
                    CSF::field($field, $this->get_value($field), $field_unique);
                }
                echo $this->hidden_input();
                echo '</div>';
                if ($this->args['form']) {
                    echo '</form>';
                }
            }
        }

        // get widget value
        public function hidden_input(){
            $hidden_input = isset($this->args['hidden']) ? $this->args['hidden'] : array();
            $html = '';
            foreach ($hidden_input as $input) {
                $name = isset($input['name']) ? $input['name'] : $input[0];
                $value = isset($input['value']) ? $input['value'] : $input[1];
                $html .= '<input type="hidden" name="' . $name . '" value="' . $value . '">';;
            }
            return $html;
        }

        // get widget value
        public function get_value($field){
            $id = isset($field['id']) ? $field['id'] : '';
            $value = '';
            if ($id) {
                $default = $id ? $this->get_default($field) : '';
                $value   = isset($this->args['value'][$id]) ? $this->args['value'][$id] : $default;
            }
            return $value;
        }

        // get default value
        public function get_default($field){
            $default = (isset($field['default'])) ? $field['default'] : '';
            return $default;
        }

        public function save($new_instance, $old_instance){

            // auto sanitize
            foreach ($this->args['fields'] as $field) {
                if (!empty($field['id']) && (!isset($new_instance[$field['id']]) || is_null($new_instance[$field['id']]))) {
                    $new_instance[$field['id']] = '';
                }
            }

            $new_instance = apply_filters("csf_{$this->unique}_save", $new_instance, $this->args, $this);

            do_action("csf_{$this->unique}_save_before", $new_instance, $this->args, $this);

            return $new_instance;
        }
    }
}
