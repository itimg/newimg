<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access directly.
/**
 *
 * Field: sorter
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if ( ! class_exists( 'CSF_Field_sorter' ) ) {
  class CSF_Field_sorter extends CSF_Fields {

    public function __construct( $field, $value = '', $unique = '', $where = '', $parent = '' ) {
      parent::__construct( $field, $value, $unique, $where, $parent );
    }

    public function render() {

      $args = wp_parse_args( $this->field, array(
        'disabled'       => true,
        'enabled_title'  => esc_html__( 'Enabled', 'csf' ),
        'disabled_title' => esc_html__( 'Disabled', 'csf' ),
      ) );

      echo $this->field_before();

      $this->value      = ( ! empty( $this->value ) ) ? $this->value : $this->field['default'];
      $enabled_options  = ( ! empty( $this->value['enabled'] ) ) ? $this->value['enabled'] : array();
      $disabled_options = ( ! empty( $this->value['disabled'] ) ) ? $this->value['disabled'] : array();

      echo '<div class="csf-sorter" data-depend-id="'. esc_attr( $this->field['id'] ) .'"></div>';
      
      // iowen.cn 
      $term = '';
      $is_enabled = false;
      if( isset($this->field['class']) && $this->field['class'] == 'iowen_cn' ){
        $term = get_all_taxonomy();
        $is_enabled = true;
      }
      if($term != ''){
        if($is_enabled){
          $new_disabled =array_intersect_key($term,$disabled_options);//交集
          if( ! empty( $enabled_options ) ){
            $term = array_diff_assoc($term,$new_disabled);//删除已经禁用的
          }
          $all = array_merge($enabled_options,$term);
          $new_enabled = array_intersect_assoc($all,$term); 
          $add_options = array_keys(array_diff_key($term,$enabled_options)); //获取新增的项
          $enabled_options = $new_enabled;
          $disabled_options = $new_disabled;
        }else{
          $new_temp_enabled =array_intersect_key($term,$enabled_options);//交集
          if( ! empty( $disabled_options ) ){
            $term = array_diff_assoc($term,$new_temp_enabled);//删除已经启用的
          }
          $all = array_merge($disabled_options,$term);
          $new_disabled = array_intersect_assoc($all,$term); 
          $add_options = array_keys(array_diff_key($term,$disabled_options)); //获取新增的项
          $new_key = array_keys($new_temp_enabled);
          $new_enabled = [];
          foreach ($enabled_options as $key => $value) {
            if(in_array($key,$new_key))
              $new_enabled[$key] = $value;
          }
          $enabled_options = $new_enabled;
          $disabled_options = $new_disabled;
        }
      }
      // ------------------------------------------------------------------------

      echo ( $args['disabled'] ) ? '<div class="csf-modules">' : '';

      echo ( ! empty( $args['enabled_title'] ) ) ? '<div class="csf-sorter-title">'. esc_attr( $args['enabled_title'] ) .'</div>' : '';
      echo '<ul class="csf-enabled">';
      if ( ! empty( $enabled_options ) ) {
        foreach ( $enabled_options as $key => $value ) {
          // iowen.cn 追加新增的项到列表，并且标记 增加if判断
          if(isset($add_options) && $is_enabled && in_array($key,$add_options))
            echo '<li style="background: rgb(208, 255, 173);"><input type="hidden" name="'. $this->field_name( '[enabled]['. $key .']' ) .'" value="'. $value .'"/><label>'. $value .'</label></li>';
          else
            echo '<li><input type="hidden" name="'. esc_attr( $this->field_name( '[enabled]['. $key .']' ) ) .'" value="'. esc_attr( $value ) .'"/><label>'. esc_attr( $value ) .'</label></li>';
        }
      }
      echo '</ul>';

      // Check for hide/show disabled section
      if ( $args['disabled'] ) {

        echo '</div>';

        echo '<div class="csf-modules">';
        echo ( ! empty( $args['disabled_title'] ) ) ? '<div class="csf-sorter-title">'. esc_attr( $args['disabled_title'] ) .'</div>' : '';
        echo '<ul class="csf-disabled">';
        if ( ! empty( $disabled_options ) ) {
          foreach ( $disabled_options as $key => $value ) {
            // iowen.cn 追加新增的项到列表，并且标记 增加if判断
            if(isset($add_options) && !$is_enabled && in_array($key,$add_options))
              echo '<li style="background: rgb(208, 255, 173);"><input type="hidden" name="'. $this->field_name( '[disabled]['. $key .']' ) .'" value="'. $value .'"/><label>'. $value .'</label></li>';
            else
              echo '<li><input type="hidden" name="'. esc_attr( $this->field_name( '[disabled]['. $key .']' ) ) .'" value="'. esc_attr( $value ) .'"/><label>'. esc_attr( $value ) .'</label></li>';
          }
        }
        echo '</ul>';
        echo '</div>';

      }


      echo $this->field_after();

    }

    public function enqueue() {

      if ( ! wp_script_is( 'jquery-ui-sortable' ) ) {
        wp_enqueue_script( 'jquery-ui-sortable' );
      }

    }

  }
}
