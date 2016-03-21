<?php

use Models\Base\Model;
use Models\p_customerrecord_soft;
use Models\Base\SqlOperator;
use Models\p_customerstatistics_soft;
require '../../Common/ExportData2Excel.php';
require '../../application.php';
require '../../loader-api.php';

execute_action(HttpRequestMethod::Get, 1, function() {
    
    $searchName = request_string('searchName');
    
    $sql = 'SELECT * FROM ((SELECT id,userid,username,`group`,team,category,group_members FROM p_customerrecord_soft WHERE group_members!=userid and `group`=2)';
    $sql .= 'UNION ALL';
    $sql .= '(SELECT id,userid,username,`group`,team,category,group_members FROM p_customerrecord_soft WHERE group_members=0 and `group`=1)) as u ';
    if(isset($searchName)) $sql .= "where username like '%" . $searchName . "%' ";

    $customerrecord = new p_customerrecord_soft();
    $db = create_pdo();
    $count_result = Model::execute_custom_sql($db, $sql);

    if (!$count_result[0]) die_error(USER_ERROR, "获取统计资料失败，请重试");
    $total_count = $count_result['count'];
    $max_page_no = ceil($total_count / request_pagesize());
    $sql .= "LIMIT " . request_pagesize() * (request_pageno() - 1) . "," . request_pagesize();

    $result = Model::query_list($db, $customerrecord, $sql, true);

    if (!$result[0]) die_error(USER_ERROR, '获取售后统计资料失败，请重试');
    $models = Model::list_to_array($result['models'], array(), function(&$d) {

        if($d['userid'] != $d['group_members'] && $d['group'] == 2) {
            $d['username_owner'] = $d['username']; 
            $d['username_stuff'] = get_employees()[$d['group_members']]['username'];
            $d['username'] = $d['username'].'+'.get_employees()[$d['group_members']]['username'];  
        }else
        {
            $d['username_stuff'] = 0;
            $d['username_owner'] = 0;
        }
        
       // $d['team'] = get_team_name()[$d['team']];
         $d['team'] = 1;
        $d['group'] = get_group_name()[$d['group']];
        $d['edit'] = true;
    });
 
     echo_result(array('code' => 0, 'list' => $models, 'total_count' => $total_count, 'page_no' => request_pageno(), 'max_page_no' => $max_page_no, 'total_month_count' => $d));
});

execute_action(HttpRequestMethod::Get, 2, function() {   
    $userid = request_int('userid');
   // $team = array_keys( get_team_name(),request_string('team')); 
  
    $customerrecord = new p_customerrecord_soft();
    $customerrecord->set_where_and(p_customerrecord_soft::$field_userid, SqlOperator::NotEquals, $userid);
    $customerrecord->set_where_and(p_customerrecord_soft::$field_group, SqlOperator::Equals, 1);
    //$customerrecord->set_where_and(p_customerrecord_soft::$field_team, SqlOperator::Equals, $team[0]);
    $db = create_pdo();
    $result = Model::query_list($db, $customerrecord);
    if (!$result[0]) die_error(USER_ERROR, '获取售后名单失败，请重试');
    $models = Model::list_to_array($result['models']);
   
    echo_list_result($result, $models);
});

execute_action(HttpRequestMethod::Post, 1, function() {
    $customerrecordData = request_object();
    $customerrecordData = convert_value_for_submit($customerrecordData);
    $customerrecord = new p_customerrecord_soft();
   // $customerrecord->set_team($customerrecordData->team);
    $customerrecord->set_team(1);
    $customerrecord->set_category($customerrecordData->category);
    $customerrecord->set_group($customerrecordData->group);
    $customerrecord->set_group_members($customerrecordData->group_members);
    $customerrecord->set_suserid($customerrecordData->group_members);
    $customerrecord->set_where_and(p_customerrecord_soft::$field_userid, SqlOperator::Equals, $customerrecordData->userid);
    
    $groupmember = new p_customerrecord_soft();
  //  $groupmember->set_team($customerrecordData->team);
    $groupmember->set_team(1);
    $groupmember->set_category($customerrecordData->category);
    $groupmember->set_group($customerrecordData->group);
    $groupmember->set_group_members($customerrecordData->group_members);
    $groupmember->set_suserid($customerrecordData->userid);
    $groupmember->set_where_and(p_customerrecord_soft::$field_userid, SqlOperator::Equals, $customerrecordData->group_members);
    
    $db = create_pdo();
    pdo_transaction($db, function($db) use($customerrecord, $groupmember) {
        $result = $customerrecord->update($db,true);
        if (!$result[0]) die_error(PDO_ERROR_CODE, '更新售后名单失败');

        $result = $groupmember->update($db,true);
        if (!$result[0]) die_error(PDO_ERROR_CODE, '更新售后名单失败');
    });
    echo_msg('组队成功');
});

execute_action(HttpRequestMethod::Post, 2, function() {
    $customerrecordData = request_object();
    $customerrecordData = convert_value_for_submit($customerrecordData);
    $customerrecord_owner = new p_customerrecord_soft();
    $customerrecord_owner->set_group('1');
    $customerrecord_owner->set_group_members('0');
    $customerrecord_owner->set_suserid('0');	
   // $customerrecord_owner->set_team(array_search($customerrecordData->team_owner, get_team_name()));
    $customerrecord_owner->set_team(1);
    $customerrecord_owner->set_category($customerrecordData->category_owner);
    $customerrecord_owner->set_where_and(p_customerrecord_soft::$field_userid, SqlOperator::Equals, $customerrecordData->userid);
    
    $customerrecord_stuff = new p_customerrecord_soft();
    $customerrecord_stuff->set_group('1');
    $customerrecord_stuff->set_group_members('0');
    $customerrecord_stuff->set_suserid('0');
  //  $customerrecord_stuff->set_team(array_search($customerrecordData->team_stuff, get_team_name()));
    $customerrecord_stuff->set_team(1);
    $customerrecord_stuff->set_category($customerrecordData->category_stuff);
    $customerrecord_stuff->set_where_and(p_customerrecord_soft::$field_userid, SqlOperator::Equals, $customerrecordData->group_members);
    
    $db = create_pdo();
    pdo_transaction($db, function($db) use($customerrecord_owner,$customerrecord_stuff) {

        $result = $customerrecord_owner->update($db,true);
        if (!$result[0]) die_error(PDO_ERROR_CODE, '更新售后名单失败');
        
        $result = $customerrecord_stuff->update($db,true);
        if (!$result[0]) die_error(PDO_ERROR_CODE, '更新售后名单失败');

    });
    echo_msg('组队解散');
    
});
//编辑
execute_action(HttpRequestMethod::Post, 3, function() {
    $db = create_pdo();
    $customerrecordData = request_object();
    $customerrecordData = convert_value_for_submit($customerrecordData);   
    //如果当天在售后统计中已经有记录了，就不允许再修改
    $customerstatistics = new p_customerstatistics_soft();
    $date = date_operation_for_business(time());
    $customerstatistics->set_where_and(p_customerstatistics_soft::$field_user_id,SqlOperator::Equals, $customerrecordData->userid); 
    $customerstatistics->set_custom_where(" AND DATE_FORMAT(date, '%Y-%m-%d') = '" . $date . "' "); 
    $result = Model::query_list($db, $customerstatistics, NULL, true);
    $count = $result['total_count'];
    if($count) die_error(PDO_ERROR_CODE, '当天已经产生售后统计数据，不允许修改');
       
    $customerrecord = new p_customerrecord_soft();
   // $customerrecord->set_team($customerrecordData->team);
    $customerrecord->set_team(1);
    $customerrecord->set_category($customerrecordData->category);
    $customerrecord->set_where_and(p_customerrecord_soft::$field_userid, SqlOperator::Equals, $customerrecordData->userid);
    
    if($customerrecordData->userid != $customerrecordData->group_members){
        $groupmember = new p_customerrecord_soft();
     //   $groupmember->set_team($customerrecordData->team);
          $groupmember->set_team(1);
        $groupmember->set_category($customerrecordData->category);
        $groupmember->set_where_and(p_customerrecord_soft::$field_userid, SqlOperator::Equals, $customerrecordData->group_members);
    }
    
    pdo_transaction($db, function($db) use($customerrecord,$groupmember,$customerrecordData) {
        $result = $customerrecord->update($db,true);
        if (!$result[0]) die_error(PDO_ERROR_CODE, '更新售后名单失败');
        
        if($customerrecordData->userid != $customerrecordData->group_members && $customerrecordData->group_members != 0){
            $result = $groupmember->update($db,true);
            if (!$result[0]) die_error(PDO_ERROR_CODE, '更新售后名单失败');
        }
    });
    echo_msg('修改成功');
});

function get_team_name(){
    
    return array(
        1 => '百度',
        2 => '360'
    );
}
function get_group_name(){
    return array(
        1 => '单人组',
        2 => '双人组'
    );
}
//专门为group和team字段的值转换定制开发的函数，高耦合
function convert_value_for_submit($customerrecordData){
 
    if($customerrecordData->team && $customerrecordData->group)
    {
        $customerrecordData->team =  array_search($customerrecordData->team, get_team_name());
        $customerrecordData->group = array_search($customerrecordData->group,get_group_name());
    }
    return $customerrecordData;
}