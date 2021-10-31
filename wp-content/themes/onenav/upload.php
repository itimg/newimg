<?php
/*!
 * Theme Name:One Nav
 * Theme URI:https://www.iotheme.cn/
 * Author:iowen
 * Author URI:https://www.iowen.cn/
 */
?>
<?php
if ( 'POST' != $_SERVER['REQUEST_METHOD'] ) {
	header('Allow: POST');
	header('HTTP/1.1 405 Method Not Allowed');
	header('Content-Type: text/plain');
	exit;
}

require_once '../../../wp-load.php';


defined('IO_AVATARS_PATH') || define('IO_AVATARS_PATH', WP_CONTENT_DIR . '/uploads/avatars');

defined('IO_AVATARS_URL') || define('IO_AVATARS_URL', home_url('wp-content/uploads/avatars'));

$user_id = get_current_user_id();

if(!$user_id){
    header("HTTP/1.1 403 Forbidden");
    exit;
}

// 确保文件未缓存（例如在iOS设备上发生的情况）
header("HTTP/1.1 200 OK");
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");


$imageFor = htmlspecialchars($_POST['imgFor']);
$imageForTypes = array('avatar', 'cover', 'default');
if (!in_array($imageFor, $imageForTypes)) {
    $imageFor = 'default';
}

// 1分钟执行时间
set_time_limit(1 * 60);

// Settings
$tmpDir = WP_CONTENT_DIR . '/uploads/tmp';
$avatarDir = IO_AVATARS_PATH;
$uploadDir = WP_CONTENT_DIR . '/uploads/images';
$uploadUrl = home_url('wp-content/uploads/images');
$cleanupTargetDir = true; // 删除旧文件
$maxFileAge = 5 * 3600; // 临时文件的年龄（以秒为单位）
// 创建零时目标目录
if (!file_exists($tmpDir)) {
    if(!mkdir($tmpDir))
        wp_die('创建图像缓存文件夹失败，请检测文件夹权限！', '创建文件夹失败', array('response'=>403));
}
// 创建avatar目录
if (!file_exists($avatarDir)) {
    if(!mkdir($avatarDir))
        wp_die('创建头像文件夹失败，请检测文件夹权限！', '创建文件夹失败', array('response'=>403));
}
// 创建上传目录
if (!file_exists($uploadDir)) {
    if(!mkdir($uploadDir))
        wp_die('创建图像缓存文件夹失败，请检测文件夹权限！', '创建文件夹失败', array('response'=>403));
}

// 获取文件名
if (isset($_POST["name"])) { // 格式： [user_id].jpg
    $fileName = $_POST["name"];
} elseif (!empty($_FILES)) {
    $fileName = $_FILES["file"]["name"];
} else {
    $fileName = uniqid("file_");
}

$fileName = io_uniioque_img_name($fileName, isset($_POST['type']) ? trim($_POST['type']) : 'image/jpg');

$filePath = $tmpDir . DIRECTORY_SEPARATOR . $fileName;
$uploadPath = $uploadDir . DIRECTORY_SEPARATOR . $fileName;

// Chunking might be enabled
$chunk = isset($_POST["chunk"]) ? intval($_POST["chunk"]) : 0;
$chunks = isset($_POST["chunks"]) ? intval($_POST["chunks"]) : 1;
// Remove old temp files
if ($cleanupTargetDir) {
    if (!is_dir($tmpDir) || !$dir = opendir($tmpDir)) {
        die('{"jsonrpc" : "2.0", "error" : {"code": 100, "message": "无法打开临时目录。"}, "id" : "id"}');
    }
    while (($file = readdir($dir)) !== false) {
        $tmpfilePath = $tmpDir . DIRECTORY_SEPARATOR . $file;
        // If temp file is current file proceed to the next
        if ($tmpfilePath == "{$filePath}_{$chunk}.part" || $tmpfilePath == "{$filePath}_{$chunk}.parttmp") {
            continue;
        }
        // Remove temp file if it is older than the max age and is not the current file
        if (preg_match('/\.(part|parttmp)$/', $file) && (filemtime($tmpfilePath) < time() - $maxFileAge)) {
            unlink($tmpfilePath);
        }
    }
    closedir($dir);
}
// Open temp file
if (!$out = fopen("{$filePath}_{$chunk}.parttmp", "wb")) {
    die('{"jsonrpc" : "2.0", "error" : {"code": 102, "message": "无法打开输出流。"}, "id" : "id"}');
}
if (!empty($_FILES)) {
    if ($_FILES["file"]["error"] || !is_uploaded_file($_FILES["file"]["tmp_name"])) {
        die('{"jsonrpc" : "2.0", "error" : {"code": 103, "message": "无法移动上传的文件。"}, "id" : "id"}');
    }
    // Read binary input stream and append it to temp file
    if (!$in = fopen($_FILES["file"]["tmp_name"], "rb")) {
        die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "无法打开输入流。"}, "id" : "id"}');
    }
} else {
    if (!$in = fopen("php://input", "rb")) {
        die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "无法打开输入流。"}, "id" : "id"}');
    }
}
while ($buff = fread($in, 4096)) {
    fwrite($out, $buff);
}
fclose($out);
fclose($in);
rename("{$filePath}_{$chunk}.parttmp", "{$filePath}_{$chunk}.part");
$index = 0;
$done = true;
for( $index = 0; $index < $chunks; $index++ ) {
    if ( !file_exists("{$filePath}_{$index}.part") ) {
        $done = false;
        break;
    }
}
if ( $done ) {
    if (!$out = fopen($uploadPath, "wb")) {
        die('{"jsonrpc" : "2.0", "error" : {"code": 102, "message": "无法打开输出流。"}, "id" : "id"}');
    }
    if ( flock($out, LOCK_EX) ) {
        for( $index = 0; $index < $chunks; $index++ ) {
            if (!$in = fopen("{$filePath}_{$index}.part", "rb")) {
                break;
            }
            while ($buff = fread($in, 4096)) {
                fwrite($out, $buff);
            }
            fclose($in);
            unlink("{$filePath}_{$index}.part");
        }
        flock($out, LOCK_UN);
    }
    fclose($out);

    if($imageFor == 'avatar'){
        $avatar_path = IO_AVATARS_PATH . DIRECTORY_SEPARATOR . $user_id . '.jpg';
        io_resize_img($uploadPath, $avatar_path, 100, 100, true);
        io_update_user_avatar_by_upload($user_id);
        $avatar_img = IO_AVATARS_URL . '/' . $user_id . '.jpg?_=' . time(); // 加时间戳防止缓存
        update_user_meta($user_id, 'custom_avatar', $avatar_img);
        echo json_encode(array(
            'success' => true,
            'message' => '',
            'data' => array(
                'avatar' => $avatar_img
            )
        ));
        exit;
    }elseif($imageFor == 'cover'){
        $cover_base_path = IO_AVATARS_PATH . DIRECTORY_SEPARATOR . $user_id . '_cover_';
        $cover_path = $cover_base_path . 'full.jpg';
        $cover_mini_path = $cover_base_path . 'mini.jpg';
        io_resize_img($uploadPath, $cover_path, 1400, 300, false);
        io_resize_img($uploadPath, $cover_mini_path, 350, 145, true);
        io_update_user_cover_by_upload($user_id, IO_AVATARS_URL . '/' . $user_id . '_cover_');
        echo json_encode(array(
            'success' => true,
            'message' => '',
            'data' => array(
                'cover' => IO_AVATARS_URL . '/' . $user_id . '_cover_full.jpg?_=' . time() // 加时间戳防止缓存
            )
        ));
        exit;
    }else{
        exit;
    }
}
// 成功
die('{"jsonrpc" : "2.0", "result" : null, "id" : "id"}');

