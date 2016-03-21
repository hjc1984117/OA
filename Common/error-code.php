<?php

/**
 * 错误码定义
 */
$GLOBALS['/common/error-code.php'] = 1;

define('PARAM_MISSING_ERROR_CODE', "0x100");
define('PARAM_MISSING_ERROR_MSG', "缺少必要参数");

define('PARAM_INVALID_ERROR_CODE', "0x101");
define('PARAM_INVALID_ERROR_MSG', "参数取值无效");

define('PARAM_ILLEGAL_ERROR_CODE', "0x102");
define('PARAM_ILLEGAL_ERROR_MSG', "输入内容包含非法字符");

define('UNSUPPORTED_REQUEST_METHOD_ERROR', "0x103");

define('PDO_ERROR_CODE', "0x200");
define('PDO_CREATE_ERROR_MSG', "创建数据库连接失败");

define('USER_ERROR', "0x300");
define('USER_LOGIN_EXPIRED', "0x301");

define('NETWORK_ERROR', '0x400');

define('INTERNAL_ERROR', '0x500');

define('PAGE_VERSION_ERROR', '0x600');

define('RUNTIME_ERROR', '0x10000');
