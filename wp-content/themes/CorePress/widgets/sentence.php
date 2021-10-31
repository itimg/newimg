<?php


class CorePress_sentence_widget extends WP_Widget
{

    function __construct()
    {
        parent::__construct(
            'corepress_sentence_widget',
            'CorePress句子模块',
            array(
                'description' => '随机显示一个句子，可以显示【毒鸡汤】【舔狗】【一言】【社会语录】'
            )
        );
    }

    function form($instance)
    {

        $title = isset($instance['title']) ? $instance['title'] : '句子';
        $type = isset($instance['type']) ? $instance['type'] : 'djt';
        $id = isset($instance['id']) ? $instance['id'] : 'jzmk';

        ?>
        <p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>"
                   name="<?php echo $this->get_field_name('title'); ?>" type="text"
                   value="<?php echo esc_attr($title); ?>"/></p>

        <p><label for="<?php echo $this->get_field_id('id'); ?>">唯一名称</label>
            <input class="widefat" id="<?php echo $this->get_field_id('id'); ?>"
                   name="<?php echo $this->get_field_name('id'); ?>" type="text"
                   value="<?php echo esc_attr($id); ?>"/></p>
        <p>如果一个页面要多个句子模块，请给唯一名称起不一样的名称，否则会显示一样内容</p>
        <p>
            <label for="<?php echo $this->get_field_id('type'); ?>">显示类型</label>
            <select name="<?php echo $this->get_field_name('type'); ?>">
                <option value="djt" <?php if ($type == 'djt') echo 'selected' ?>>毒鸡汤</option>
                <option value="tg" <?php if ($type == 'tg') echo 'selected' ?>>舔狗日记</option>
                <option value="yy" <?php if ($type == 'yy') echo 'selected' ?>>一言</option>
                <option value="sh" <?php if ($type == 'sh') echo 'selected' ?>>社会语录</option>
            </select>
        </p>
        <?php
    }

    public function widget_start($args, $instance)
    {
        echo $args['before_widget'];
        if ($title = apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title'], $instance, $this->id_base)) {
            echo $args['before_title'] . $title . $args['after_title'];
        }
    }

    public function widget_end($args)
    {
        echo $args['after_widget'];
    }


    function update($new_instance, $old_instance)
    {
        return $new_instance;
    }

    function widget($args, $instance)
    {

        $this->widget_start($args, $instance);
        $type = (!empty($instance['type'])) ? $instance['type'] : 'djt';
        $id = isset($instance['id']) ? $instance['id'] : msectime();
        $obj = 'widget-sentence-placeholder-' . $id;
        ?>
        <div class="widget-sentence-placeholder <?php echo $obj ?>">
            <ul>
                <li></li>
                <li></li>
            </ul>
        </div>
        <script>
            $(document).ready(function () {
                widget_sentence_load('<?php echo $type?>', '.<?php echo $obj?>');
            });
        </script>
        <?php
        $this->widget_end($args, $instance);
    }
}

// register widget
function register_corepress_sentence_widget()
{
    register_widget('CorePress_sentence_widget');
}

add_action('widgets_init', 'register_corepress_sentence_widget');