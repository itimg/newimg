<?php
/*
 * @Author: iowen
 * @Author URI: https://www.iowen.cn/
 * @Date: 2021-08-07 21:18:28
 * @LastEditors: iowen
 * @LastEditTime: 2021-08-19 08:30:31
 * @FilePath: \swallow\inc\classes\class.Async.Email.php
 * @Description: 
 */
?>
<?php

/**
 * Class AsyncEmail
 */
final class AsyncEmail extends WPAsyncTask {
    protected $action = 'send_mail';

    protected $argument_count = 5;

    /**
     * 准备异步请求的数据
     *
     * @throws Exception If for any reason the request should not happen
     *
     * @param array $data An array of data sent to the hook
     *
     * @return array
     */
    protected function prepare_data( $data ) {
        // $from, $to, $title = '', $args = array(), $template = 'comment'
        return array(
            'from' => $data[0],
            'to' => $data[1],
            'title' => $data[2],
            'args' => $data[3],
            'template' => $data[4]
        );
    }

    /**
     * 运行异步任务操作
     */
    protected function run_action() {
        //$data = $this->_body_data;
        $args = json_decode(base64_decode($_POST['args']));
        $args = $args ? (array)$args : $_POST['args'];
        $data = array(
            'from' => $_POST['from'],
            'to' => $_POST['to'],
            'title' => $_POST['title'],
            'args' => $args,
            'template' => $_POST['template']
        );
        $action = $_POST['action'];
        //do_action( $action, $data['from'], $data['to'], $data['title'], $data['args'], $data['template'] ); //执行io_mail
        io_mail($data['from'], $data['to'], $data['title'], $data['args'], $data['template']); // 也可以直接io_mail(), 则不需要在io_mail下写add_action('io_async_send_mail', xx);
    }
}