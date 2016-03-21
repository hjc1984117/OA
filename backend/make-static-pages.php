<?php
//废弃不用
die;

use common\HttpClient;

require 'application.php';
require 'loader-api.php';

$key = request_md5_32('key');
if (strcmp($key, BACKEND_KEY) !== 0) die_error(USER_ERROR, '密钥错误');

$template_files = array(
    '/index.php',
    '/soft.php',
    '/member/index.php',
    '/taobao/index.php',
    '/user/loginreg.php',
    '/user/buypoint.php',
    '/help/taskin.php',
    '/help/security.php',
    '/help/taskout-2.php'
);

header("Content-Type:text/html;charset=utf-8");
$document_root = $_SERVER['DOCUMENT_ROOT'];
?>
<!DOCTYPE html>
<html lang="zh-CN">
    <head>
        <title>Make Static Page Results</title>
    </head>
    <body>
        <ul>
            <?php
            foreach ($template_files as $template_file) {
                $url = "http://$_SERVER[HTTP_HOST]$template_file";
                $content = HttpClient::quickGet($url);
                if (!$content[0]) {
                    echo "visit $url failed with error $content[error].\n";
                    continue;
                }
                $page_url = str_replace(".php", ".html", "http://$_SERVER[HTTP_HOST]$template_file");
                $page_path = str_replace(".php", ".html", "$document_root$template_file");
                $length = file_put_contents($page_path, $content['body']);
                ?>
                <li>Build <a href="<?php echo $url ?>"><?php echo $url ?></a> to <a href="<?php echo $page_url ?>"><?php echo $page_url ?></a> <?php echo $length === false ? "failed" : "successed" ?>.</li>
                <?php
            }
            ?>
        </ul>
    </body>
</html>