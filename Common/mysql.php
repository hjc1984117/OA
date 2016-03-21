<?php

/**
 * 基础mysql类
 */
$GLOBALS['/common/mysql.php'] = 1;

class TransactionException extends Exception {

    public $result;

    public function __construct($code, $message, $result = null) {
        parent::__construct($message, $code, null);
        $this->result = $result;
    }

}

function create_pdo() {
    $pdo_dsn = 'mysql:host=' . DB_HOST . ';port=' . DB_PORT . ';dbname=' . DB_NAME;
    $pdo_options = array(
        PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'/*,
        PDO::ATTR_PERSISTENT => true*/
    );
    try {
        $pdo = new PDO($pdo_dsn, DB_USR, DB_PWD, $pdo_options);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_SILENT);
        return $pdo;
    } catch (PDOException $ex) {
        die_error(PDO_ERROR_CODE, PDO_CREATE_ERROR_MSG);
    }
}

function pdo_transaction(\PDO $db, callable $proc, $response_code = null) {
    $result = false;
    if (!$db->beginTransaction()) die_error(PDO_ERROR_CODE, '创建事务失败');
    try {
        $result = $proc($db);
        $db->commit();
    } catch (TransactionException $ex) {
        $db->rollBack();
        if (isset($ex->result)) die_pdo_error(PDO_ERROR_CODE, $ex->getMessage(), $ex->result, $response_code);
        die_error(PDO_ERROR_CODE, $ex->getMessage(), $response_code);
    }

    return $result;
}
