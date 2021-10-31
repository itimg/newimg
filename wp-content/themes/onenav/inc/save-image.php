<?php
/*
 * @Author: iowen
 * @Author URI: https://www.iowen.cn/
 * @Date: 2021-03-01 10:19:06
 * @LastEditors: iowen
 * @LastEditTime: 2021-09-28 21:39:53
 * @FilePath: \onenav\inc\save-image.php
 * @Description: 
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
//外链图片自动本地化
function ecp_save_post($post_id, $post) {
	global $wpdb;
	// 只有在点击发布/更新时才执行以下动作
	if($post->post_status == 'publish') {
		// 匹配<img>、src，存入$matches数组,
		$num = preg_match_all('/<img.*[\s]src=[\"|\'](.*)[\"|\'].*>/iU' , $post->post_content, $matches);

		if ($num) {
			// 本地上传路径信息(数组)，用来构造url
			$wp_upload_dir = wp_upload_dir();

			// 脚本执行不限制时间
			set_time_limit(0);
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_HEADER, false);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
			curl_setopt($ch, CURLOPT_MAXREDIRS,20);
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);

			foreach ($matches[1] as $src) {
				if (isset($src) && unexclude_image($src)) {// 如果图片域名是外链

					// 检查src中的url有无扩展名，没有则重新给定文件名
					// 注意：如果url中有扩展名但格式为webp，那么返回的file_info数组为 ['ext' =>'','type' =>'']
					$file_info = wp_check_filetype(basename($src), null);
					if ($file_info['ext'] == false) {
						// 无扩展名和webp格式的图片会被作为无扩展名文件处理
						date_default_timezone_set('PRC');
						$file_name = date('YmdHis-').dechex(mt_rand(100000, 999999)).'.tmp';
					} else {
						// 有扩展名的图片重新给定文件名防止与本地文件名冲突
						$file_name = dechex(mt_rand(100000, 999999)) . '-' . basename($src);
					}
					// 抓取图片, 将图片写入本地文件
					curl_setopt($ch, CURLOPT_URL, $src);
					$file_path = $wp_upload_dir['path'] . '/' . $file_name;
					$img = fopen($file_path, 'wb');

					// curl写入$img
					curl_setopt($ch, CURLOPT_FILE, $img);
					$img_data  = curl_exec($ch);
					fclose($img);
 
					if (file_exists($file_path) && filesize($file_path) > 0) {
						// 将扩展名为tmp和webp的图片转换为jpeg文件并重命名
						$t   = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
						$arr = explode('/', $t);
						// 对url地址中没有扩展名或扩展名为webp的图片进行处理
						if (pathinfo($file_path, PATHINFO_EXTENSION) == 'tmp') {
							$file_path = io_handle_ext($file_path, $arr[1], $wp_upload_dir['path'], $file_name, 'tmp');
						} elseif (pathinfo($file_path, PATHINFO_EXTENSION) == 'webp') {
							$file_path = io_handle_ext($file_path, $arr[1], $wp_upload_dir['path'], $file_name, 'webp');
						}

						// 替换文章内容中的src
						$post->post_content  = str_replace($src, $wp_upload_dir['url'] . '/' . basename($file_path), $post->post_content);
						// 构造附件post参数并插入媒体库(作为一个post插入到数据库)
						$attachment = io_get_attachment_post(basename($file_path), $wp_upload_dir['url'] . '/' . basename($file_path));
						// 生成并更新图片的metadata信息
						$attach_id = wp_insert_attachment($attachment, ltrim($wp_upload_dir['subdir'] . '/' . basename($file_path), '/'), 0);
						$attach_data = wp_generate_attachment_metadata($attach_id, $file_path);
						// 直接调用wordpress函数，将metadata信息写入数据库
						$ss = wp_update_attachment_metadata($attach_id, $attach_data);
					}
				}
			}
			curl_close($ch);

			// 更新posts数据表的post_content字段
			$wpdb->update( $wpdb->posts, array('post_content' => $post->post_content), array('ID' => $post->ID));
		}
	}
}
add_action('save_post', 'ecp_save_post', 120, 2);