<?php
/**
 * Theme Update Checker Library 1.2
 * http://w-shadow.com/
 * 
 * Copyright 2012 Janis Elsts
 * Licensed under the GNU GPL license.
 * http://www.gnu.org/licenses/gpl.html
 */

if ( !class_exists('ThemeUpdateChecker') ):
/**
 * A custom theme update checker. 
 * 
 * @author Janis Elsts, iowen
 * @copyright 2012
 * @version 1.2
 * @access public
 */
class ThemeUpdateChecker {
	public $theme = '';              //The theme slug.
	public $metadataUrl = '';        //更新检查地址。
	public $enableAutomaticChecking = true; //启用/禁用自动更新检查。
	
	protected $optionName = '';      //哪里存储更新信息。
	protected $automaticCheckDone = false; 
	/**
	 * Class constructor.
	 *
	 * @param string $theme Theme slug, e.g. "twentyten".
	 * @param string $metadataUrl 更新检查地址。
	 * @param boolean $enableAutomaticChecking 启用/禁用自动更新检查。如果设置为FALSE，则需要主动调用checkForUpdates()来检查更新。
	 */
	public function __construct($theme, $metadataUrl, $enableAutomaticChecking = true){
		$this->theme = $theme;
		$this->metadataUrl = $metadataUrl;
		$this->enableAutomaticChecking = $enableAutomaticChecking;
		$this->optionName = 'external_theme_updates-'.$this->theme;
		
		$this->installHooks();		
	}
	
	/**
	 * 安装运行定期更新检查并注入更新信息所需的挂钩
	 * 进入WP数据结构。
	 * 
	 * @return void
	 */
	public function installHooks(){
		//通过跟踪WordPress对“update_themes”瞬态的更新（仅在wp_update_themes()中发生），来自动触发主题更新检查。
		if ( $this->enableAutomaticChecking ){
			add_filter('pre_set_site_transient_update_themes', array($this, 'onTransientUpdate'));
		}
		
		//将我们的更新信息插入WP维护的更新列表中。
		add_filter('site_transient_update_themes', array($this,'injectUpdate')); 
		
		//当WP删除自己的更新信息时，请删除我们的更新信息。
		//这通常在安装，删除或升级主题时发生。
		add_action('delete_site_transient_update_themes', array($this, 'deleteStoredData'));
	}
	
	/**
	 * 从URL检索更新信息。
	 * 
	 * 返回ThemeUpdate的实例，如果没有可用的较新版本或发生错误，则返回NULL。
	 * 
	 * @uses wp_remote_get()
	 * 
	 * @param array $queryArgs 附加到请求的其他查询参数。可选。
	 * @return ThemeUpdate 
	 */
	public function requestUpdate($queryArgs = array()){
		$queryArgs = array_merge(
			array(
				'theme' 			=> $this->theme,
				'version' 			=> strval($this->getInstalledVersion()),
			),
			$queryArgs
		);
		$queryArgs = apply_filters('theme_update_query_args-'.$this->theme, $queryArgs);

		$options = array(
			'timeout' => 10, //seconds
			'headers' => array(
				'Accept' => 'application/json',
			),
			'sslverify'=>false
		);
		
		$url = $this->metadataUrl; 
		if ( !empty($queryArgs) ){
			$url = add_query_arg($queryArgs, $url);
		}

		$result = wp_remote_get($url, $options);

		$themeUpdate = null;
		$code = wp_remote_retrieve_response_code($result);
		$body = wp_remote_retrieve_body($result);
		if ( ($code == 200) && !empty($body) ){
			$themeUpdate = ThemeUpdate::fromJson($body);
			if ( ($themeUpdate != null) && version_compare($themeUpdate->version, $this->getInstalledVersion(), '<=') ){
				$themeUpdate = null;
			}
		}

		return $themeUpdate;
	}
	
	/**
	 * 获取当前安装的主题版本。
	 * 
	 * @return string Version number.
	 */
	public function getInstalledVersion(){
		if ( function_exists('wp_get_theme') ) {
			$theme = wp_get_theme($this->theme);
			return $theme->get('Version');
		}
		return '';
	}
	
	/**
	 * 检查主题更新。
	 * 
	 * @return void
	 */
	public function checkForUpdates(){
		$state = get_option($this->optionName);
		if ( empty($state) ){
			$state = new StdClass;
			$state->lastCheck = 0;
			$state->checkedVersion = '';
			$state->update = null;
		}
		
		$state->lastCheck = time();
		$state->checkedVersion = $this->getInstalledVersion();
		update_option($this->optionName, $state); //检查前保存以防出错
		
		$state->update = $this->requestUpdate();
		update_option($this->optionName, $state);
	}
	
	/**
	 * 运行自动更新检查，但每个页面加载不超过一次。
	 * 这是WP的回调。不要直接调用它。
	 * 
	 * @param mixed $value
	 * @return mixed
	 */
	public function onTransientUpdate($value){
		if ( !$this->automaticCheckDone ){
			$this->checkForUpdates();
			$this->automaticCheckDone = true;
		}
		return $value;
	}
	
	/**
	 * 将最新更新（如果有）插入WP维护的更新列表中。
	 * 
	 * @param StdClass $updates Update list.
	 * @return array Modified update list.
	 */
	public function injectUpdate($updates){
		$state = get_option($this->optionName);
		
		if ( !empty($state) && isset($state->update) && !empty($state->update) && is_object($state->update) && method_exists($state->update, 'toWpFormat') ){
			$updates->response[$this->theme] = $state->update->toWpFormat();
		}
		
		return $updates;
	}
	/**
	 * 删除所有存储的数据。
	 * 
	 * @return void
	 */
	public function deleteStoredData(){
		delete_option($this->optionName);
	} 
}
	
endif;

if ( !class_exists('ThemeUpdate') ):

/**
 * 用于保存有关可用更新的信息。
 * 
 * @author Janis Elsts
 * @copyright 2012
 * @version 1.0
 * @access public
 */
class ThemeUpdate {
	public $slug;
	public $version;      //版本号。
	public $details_url;  //用户可以在其中了解有关此版本的更多信息的URL。
	public $download_url; //此主题版本的下载URL。可选的。
	
	/**
	 * 根据其JSON编码表示形式创建一个新的ThemeUpdate实例。
	 * 
	 * @param string $json 表示主题信息对象的有效JSON字符串。
	 * @return ThemeUpdate ThemeUpdate 的新实例，错误时为NULL。
	 */
	public static function fromJson($json){
		$apiResponse = json_decode($json);
		if ( empty($apiResponse) || !is_object($apiResponse) ){
			return null;
		}
		
		//非常非常基本的验证。
		$valid = isset($apiResponse->version) && !empty($apiResponse->version) && isset($apiResponse->details_url) && !empty($apiResponse->details_url);
		if ( !$valid ){
			return null;
		}
		
		$update = new self();
		foreach(get_object_vars($apiResponse) as $key => $value){
			$update->$key = $value;
		}
		
		return $update;
	}
	
	/**
	 * 将更新转换为WordPress核心期望的格式。
	 * 
	 * @return array
	 */
	public function toWpFormat(){
		$update = array(
			'theme' => $this->slug,
			'new_version' => $this->version,
			'url' => $this->details_url,
		);
		
		if ( !empty($this->download_url) ){
			$update['package'] = $this->download_url;
		}
		
		return $update;
	}
}
	
endif;
