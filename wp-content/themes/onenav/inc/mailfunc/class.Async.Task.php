<?php
/**
 * Plugin Name: WP Asynchronous Tasks
 * Plugin URI: https://github.com/techcrunch/wp-async-task
 * Version: 1.0
 * Description: Creates an abstract class to execute asynchronous tasks
 * Author: 10up, Eric Mann, Luke Gedeon, John P. Bloch
 * License: MIT
 */
?>
<?php

/**
 * Class WPAsyncTask
 */
abstract class WPAsyncTask {
    /**
     * 一个任务的恒定标识符，应该对登录的用户可用。
     *
     * 更多细节请见构造函数文档。
     */
    const LOGGED_IN = 1;
    /**
     * 一个任务的恒定标识符，应该对注销的用户可用。
     *
     * 更多细节请见构造函数文档。
     */
    const LOGGED_OUT = 2;
    /**
     * 一个任务的恒定标识符，应该对所有用户开放，无论认证状态如何。
     *
     * 更多细节请见构造函数文档。
     */
    const BOTH = 3;
    /**
     * 这是构造函数中设置的主要操作的参数计数。
     * 它被设置为一个任意高的值 20，但如果需要可以被重写。
     * wp钩子后面的参数个数，默认20
     *
     * @var int
     */
    protected $argument_count = 20;
    /**
     * 优先触发中间动作。
     * wp钩子触发顺序，默认10
     *
     * @var int
     */
    protected $priority = 10;
    /**
     * @var string
     */
    protected $action;
    /**
     * @var array
     */
    protected $_body_data;
    /**
     * 构造器连接必要的动作
     *
     * 异步回传发生在哪个钩子上，可以通过$auth_level参数设置。
     * 基本上有三个选项：仅限登录用户、仅限注销用户，或两者兼而有之。
     * 在使用三个类常量之一实例化对象时设置此项：
     *  - LOGGED_IN
     *  - LOGGED_OUT
     *  - BOTH
     * $auth_level 默认为 BOTH
     *
     * @throws Exception 如果该类的$action值未被设置
     *
     * @param int $auth_level 要使用的验证级别（见上文）。
     */
    public function __construct( $auth_level = self::BOTH ) {
        if ( empty( $this->action ) ) {
            throw new Exception( 'Action not defined for class ' . __CLASS__ );
        }
        add_action( $this->action, array( $this, 'launch' ), (int) $this->priority, (int) $this->argument_count );//触发 launch()
        if ( $auth_level & self::LOGGED_IN ) {
            add_action( "admin_post_io_async_$this->action", array( $this, 'handle_postback' ) );
        }
        if ( $auth_level & self::LOGGED_OUT ) {
            add_action( "admin_post_nopriv_io_async_$this->action", array( $this, 'handle_postback' ) );
        }
    }
    /**
     * 如果我们没有收到prepare_data()抛出的异常，添加关闭动作以启动真正的回传。
     *
     * @uses func_get_args() 抓取操作所传递的所有参数
     */
    public function launch() {
        $data = func_get_args();
        try {
            $data = $this->prepare_data( $data );
        } catch ( Exception $e ) {
            return;
        }
        $data['action'] = "io_async_$this->action";
        $data['_nonce'] = $this->create_async_nonce();
        $this->_body_data = $data;
        if ( ! has_action( 'shutdown', array( $this, 'launch_on_shutdown' ) ) ) {
            add_action( 'shutdown', array( $this, 'launch_on_shutdown' ) );
        }
        
    }
    /**
     * 在WordPress关闭钩上发起请求
     *
     * On VIP we got into data races due to the postback sometimes completing
     * faster than the data could propogate to the database server cluster.
     * This made WordPress get empty data sets from the database without
     * failing. On their advice, we're moving the actual firing of the async
     * postback to the shutdown hook. Supposedly that will ensure that the
     * data at least has time to get into the object cache.
     * 在VIP上，由于回传的速度有时比数据传播到数据库服务器集群的速度快，我们进入了数据竞赛。
     * 这使得WordPress从数据库中获得空的数据集而不会失败。
     * 在他们的建议下，我们把异步回传的实际启动转移到关闭钩子上。
     * 据说这将确保数据至少有时间进入对象缓冲区。
     *
     * @uses $_COOKIE        为异步回传发送一个cookie头 
     * @uses apply_filters()
     * @uses admin_url()
     * @uses wp_remote_post()
     */
    public function launch_on_shutdown() {
        
        if ( ! empty( $this->_body_data ) ) {
            $cookies = array();
            foreach ( $_COOKIE as $name => $value ) {
                $cookies[] = "$name=" . urlencode( is_array( $value ) ? serialize( $value ) : $value );
            }
            $request_args = array(
                'timeout'   => 0.01,
                'blocking'  => false,
                'sslverify' => false,//apply_filters( 'https_local_ssl_verify', true ),
                'body'      => $this->_body_data,
                'headers'   => array(
                    'cookie' => implode( '; ', $cookies ),
                ),
            );
            $url = admin_url( 'admin-post.php' );
            wp_remote_post( $url, $request_args );
        }
    }
    /**
     * 验证回传是否有效，然后触发所有预定的事件。
     *
     * @uses $_POST['_nonce']
     * @uses is_user_logged_in()
     * @uses add_filter()
     * @uses wp_die()
     */
    public function handle_postback() {
        if ( isset( $_POST['_nonce'] ) && $this->verify_async_nonce( $_POST['_nonce'] ) ) {
            if ( ! is_user_logged_in() ) {
                $this->action = "nopriv_$this->action";
            }
            $this->run_action();
        }
        add_filter( 'wp_die_handler', function() { die(); } );
        wp_die();
    }
    /**
     * 创建一个随机的、一次性使用的令牌。
     *
     * 完全基于wp_create_nonce()，但不将nonce与当前登录的用户联系起来。
     *
     * @uses wp_nonce_tick()
     * @uses wp_hash()
     *
     * @return string 一次性令牌
     */
    protected function create_async_nonce() {
        $action = $this->get_nonce_action();
        $i      = wp_nonce_tick();
        return substr( wp_hash( $i . $action . get_class( $this ), 'nonce' ), - 12, 10 );
    }
    /**
     * 验证是否在时间限制内使用了正确的nonce。
     *
     * @uses wp_nonce_tick()
     * @uses wp_hash()
     *
     * @param string $nonce 待验证的Nonce
     *
     * @return bool 当前检查是否通过或失败
     */
    protected function verify_async_nonce( $nonce ) {
        $action = $this->get_nonce_action();
        $i      = wp_nonce_tick();
        // Nonce generated 0-12 hours ago
        if ( substr( wp_hash( $i . $action . get_class( $this ), 'nonce' ), - 12, 10 ) == $nonce ) {
            return 1;
        }
        // Nonce generated 12-24 hours ago
        if ( substr( wp_hash( ( $i - 1 ) . $action . get_class( $this ), 'nonce' ), - 12, 10 ) == $nonce ) {
            return 2;
        }
        // 无效的nonce
        return false;
    }
    /**
     * 根据类 $action 的值，获得一个nonce动作。
     *
     * @return string The nonce action for the current instance
     */
    protected function get_nonce_action() {
        $action = $this->action;
        if ( substr( $action, 0, 7 ) === 'nopriv_' ) {
            $action = substr( $action, 7 );
        }
        $action = "io_async_$action";
        return $action;
    }
    /**
     * 准备要传递给异步请求的所有数据
     *
     * The array this function receives will be a numerically keyed array from
     * func_get_args(). It is expected that you will return an associative array
     * so that the $_POST values used in the asynchronous call will make sense.
     *
     * The array you send back may or may not have anything to do with the data
     * passed into this method. It all depends on the implementation details and
     * what data is needed in the asynchronous postback.
     *
     * Do not set values for 'action' or '_nonce', as those will get overwritten
     * later in launch().
     *
     * @throws Exception If the postback should not occur for any reason
     *
     * @param array $data The raw data received by the launch method
     *
     * @return array The prepared data
     */
    abstract protected function prepare_data( $data );
    /**
     * 运行异步任务操作
     *
     * 该方法需要从$_POST超全局中获取和处理所有数据，并将它们提供给do_action调用。
     *
     * The action should be constructed as "io_async_task_$this->action"
     */
    abstract protected function run_action();
}