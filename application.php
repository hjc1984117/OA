<?php

define('DEBUG_MODE', true);

define('DEFAULT_ENC', 'UTF-8');
define('EMPTY_STRING', '');
define('ROOT_PATH', __DIR__);

// 数据库配置
define('LOCALHOST', false);
if (LOCALHOST) {
    define('DB_HOST', 'localhost');
    define('DB_PORT', 3306);
    define('DB_NAME', 'yc_oa');
    define('DB_USR', '');
    define('DB_PWD', '');
} else {
    define('DB_HOST', '121.40.50.61');
    define('DB_PORT', 3306);
    define('DB_NAME', 'yc_oa_test');
    define('DB_USR', 'ycadmin');
    define('DB_PWD', 'f98001ce038deb37');
}

define('BACKEND_KEY', '400636D5E1B1217701B4A62C996CB9BB');
define('PRIVATE_KEY', '1F1B6CB7B86832D808B5AF5FA7AE8748');
define('PUBLIC_KEY', 'D3CD5F8C7906544E36731BA9F395A83A');
define('SESSION_EXPIRE_HOUR', 168); //会话有效期，单位：小时
// API配置
define('ONLY_ALLOW_DEFAULT_CONTENT_TYPE', true); // 仅允许使用默认的Content-Type
define('DEFAULT_CONTENT_TYPE', 'json'); // 默认Content-Type
define('DEFAULT_CHARSET', 'utf-8'); // 默认Charset
define('DEFAULT_PAGESIZE', 20); // 默认列表每页数据条数
define("START_YEAR", 2015);
define("PAGE_VERSION", '1.0.0');

/*
  word 转pdf目录
 */
define("DEFAULT_FILE_UPLOAD_DIR", 'D:/word2pdf2show/files/');
define('DEFAULT_PDF_OUTPUT_DIR', 'D:/word2pdf2show/pdfs/');
define('DEFAULT_SWF_OUTPUT_DIR', 'D:/word2pdf2show/swfs/');

/* * PDF转SWF* */
define("CMD_CONVERSION_SINGLEDOC", "D:\swftools\pdf2swf.exe {path.pdf}{pdffile} -o {path.swf}{pdffile}.swf -f -T 9 -t -s storeallcharacters");
define("CMD_CONVERSION_SPLITPAGES", "D:\swftools\pdf2swf.exe {path.pdf}{pdffile} -o {path.swf}{pdffile}%.swf -f -T 9 -t -s storeallcharacters -s linknameurl");
define("CMD_SEARCHING_EXTRACTTEXT", "D:\swftools\swfstrings.exe {path.swf}{swffile}");

/**
 * 消息发送接口地址
 */
define('LOCALHOST_PUSH_API', true);
if (LOCALHOST_PUSH_API) {
    define('PUSH_API_URL', 'http://localhost:8087/');
    define('PUSH_MESSAGE_URL', 'http://localhost:8087/api/push/set-msg.php');
} else {
    define('PUSH_API_URL', 'http://121.40.50.70:10352/');
    define('PUSH_MESSAGE_URL', 'http://121.40.50.70:10352/api/push/set-msg.php');
}
define('PUSH_MESSAGE_PRIVATE_KEY', '4792D6631BC47547DEDBC008C5538EE3');
define('PUSH_MESSAGE_CALLER', 0);
//客户端配置
define('CLIENT_VERSION', '1.0.0.0');
define('CLIENT_DOWNLOAD_URL', 'http://121.40.50.70:35761/oa_client/a9efa0a642d27330/');
define('CLIENT_AES_KEY', 'ZD6Q2L0W7IYK1E94SG7L34S1J6P8VOFU');

error_reporting(E_ERROR | E_COMPILE_ERROR | E_CORE_ERROR | E_RECOVERABLE_ERROR);
date_default_timezone_set('PRC');
mb_internal_encoding(DEFAULT_ENC);
mb_regex_encoding(DEFAULT_ENC);

spl_autoload_register(function($class) {
    ud_require_once('/' . $class . '.php');
});

function ud_require_once($filename) {
    $filename = str_replace('\\', '/', $filename);
    isset($GLOBALS[$filename]) or require __DIR__ . $filename;
}
