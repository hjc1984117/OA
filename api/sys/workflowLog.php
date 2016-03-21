<?php

/*
 * 流程流转信息
 */

use Models\Base\Model;
use Models\Base\SqlOperator;
use Models\S_WorkflowLog;
use Models\Base\SqlSortType;

require '../../application.php';
require '../../loader-api.php';

check_token(null);

$action = request_action();
execute_request(HttpRequestMethod::Get, function() use($action) {
    list($workflow_id) = filter_request(array(
        request_int('workflow_id')));
    $workflow_log = new S_WorkflowLog();
    $workflow_log->set_where_and(S_WorkflowLog::$field_workflow_id, SqlOperator::Equals, $workflow_id);
    $workflow_log->set_where_and(S_WorkflowLog::$field_type, SqlOperator::Equals, $action);
    //$workflow_log->set_order_by(S_WorkflowLog::$field_addtime, SqlSortType::Desc);
    $db = create_pdo();
    $result = Model::query_list($db, $workflow_log, NULL, false);
    if (!$result[0]) die_error(USER_ERROR, '获取流程流转信息失败，请重试');
    $models = Model::list_to_array($result['models'], array(), function(&$w) {
                $w['role_type_name'] = $w['role_type'] == 0 ? '' : get_role_type_name($w['role_type'], 'name_cn');
                $w['role_name'] = get_role_name_by_id($w['role_id']);
                $w['opt'] = get_workflow_opt($w['type'], $w['workflow_status']);
            });
    echo_list_result($result, $models);
});
