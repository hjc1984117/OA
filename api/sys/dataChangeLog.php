<?php

/*
 * 数据变更信息
 */

use Models\Base\Model;
use Models\Base\SqlOperator;
use Models\S_DataChangeLog;
use Models\Base\SqlSortType;

require '../../application.php';
require '../../loader-api.php';

check_token(null);

$action = request_action();
execute_request(HttpRequestMethod::Get, function() use($action) {
    list($obj_id) = filter_request(array(request_int('obj_id')));
    $data_change_log = new S_DataChangeLog();
    $data_change_log->set_query_fields(array(S_DataChangeLog::$field_id, S_DataChangeLog::$field_obj_id, S_DataChangeLog::$field_type, S_DataChangeLog::$field_addtime, S_DataChangeLog::$field_userid, S_DataChangeLog::$field_username, S_DataChangeLog::$field_role_type, S_DataChangeLog::$field_role_id, S_DataChangeLog::$field_comment, S_DataChangeLog::$field_changed_desc));
    $data_change_log->set_where_and(S_DataChangeLog::$field_obj_id, SqlOperator::Equals, $obj_id);
    $data_change_log->set_where_and(S_DataChangeLog::$field_type, SqlOperator::Equals, $action);
    //$data_change_log->set_order_by(S_DataChangeLog::$field_addtime, SqlSortType::Desc);
    $db = create_pdo();
    $result = Model::query_list($db, $data_change_log, NULL, false);
    if (!$result[0]) die_error(USER_ERROR, '获取数据变更信息失败，请重试');
    $models = Model::list_to_array($result['models'], array(), function(&$w) {
                $w['role_type_name'] = $w['role_type'] == 0 ? '' : get_role_type_name($w['role_type'], 'name_cn');
                $w['role_name'] = get_role_name_by_id($w['role_id']);
            });
    echo_list_result($result, $models);
});
