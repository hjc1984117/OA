<?php

use Models\Base\Model;
use Models\Base\SqlOperator;
use Models\p_customerstatistics;
use Models\p_customerrecord;;
//增加一个日期参数来匹配记录，不然就会出现修改的是非当天的
function update_customerstatistics($db, $customer_id, $money, $action = 0, $date= 0,$ind_refund_rate = 0, $isupdate = 0) {
    //不允许之前的数据进入，避免脏数据
    $date_timestamp = $date== 0?time():strtotime($date);
     if($date_timestamp<strtotime("2016-01-12 03:00:00"))
         return ;   
    if(!$customer_id) die_error (USER_ERROR, '分配失败');
    //如果人员离职就不计入统计
    if(!get_employees()[$customer_id]) 
        return ;
    $customerrecord = new p_customerrecord();
    $customerrecord->set_where_and(p_customerrecord::$field_userid, SqlOperator::Equals, $customer_id);
    $customerrecord->load($db, $customerrecord);
    $customerrecord_res = $customerrecord->to_array();

    $customerstatistics = new p_customerstatistics();
    $customerstatistics->set_query_fields(p_customerstatistics::$field_id, p_customerstatistics::$field_have_received);
    $customerstatistics->set_where_and(p_customerstatistics::$field_group, SqlOperator::Equals, $customerrecord_res['group']);
    if($customerrecord_res['suserid']){       
        $customerstatistics->set_custom_where(" AND ( user_id = '" . $customerrecord_res['userid'] . "' OR suser_id = '" . $customerrecord_res['userid'] . "' ) "); 
        //组长
        if($customerrecord_res['suserid']==$customerrecord_res['group_members']){
            $username = $customerrecord_res['username']."+".get_employees()[$customerrecord_res['suserid']]['username'];
        }
        //组员
        else{
            $username = get_employees()[$customerrecord_res['suserid']]['username']."+".$customerrecord_res['username'];
            $customer_id = $customerrecord_res['suserid'];
            $customerrecord_res['suserid'] = $customerrecord_res['group_members'];
        }
    }else{
       $customerstatistics->set_where_and(p_customerstatistics::$field_user_id, SqlOperator::Equals, $customerrecord_res['userid']);
       $username = $customerrecord_res['username'];
    }
     $date = date_operation_for_business($date_timestamp);
     $customerstatistics->set_custom_where(" AND DATE_FORMAT(date, '%Y-%m-%d') = '" . $date . "' "); 
     $result = Model::query_list($db, $customerstatistics, NULL, true);
     $count = $result['total_count'];
     $customerstatistics_res = Model::list_to_array($result['models']);
     $customerstatistics_res = $customerstatistics_res[0];
     $customerstatistics_act = new p_customerstatistics();
 
    switch ($action){
        //接待数
        case 0:
            //如果$count=1则进行更新的操作，否则是插入操作。
            if($count){
                $customerstatistics_act->set_have_received(($customerstatistics_res['have_received']+$isupdate));
                $customerstatistics_act->set_receive_amount(($customerstatistics_res['receive_amount']+$money));
                $customerstatistics_act->set_where_and(p_customerstatistics::$field_id, SqlOperator::Equals, $customerstatistics_res['id']);
                $result = $customerstatistics_act->update($db,true);
               // if(!$result0['0']) die_error (PDO_ERROR_CODE, '日接待数更新失败');
            }else{
                $customerstatistics_act->set_user_id($customer_id);
                $customerstatistics_act->set_user_name($username);
                $customerstatistics_act->set_have_received($isupdate);
                $customerstatistics_act->set_date($date);
                $customerstatistics_act->set_receive_amount($money);
                $customerstatistics_act->set_team($customerrecord_res['team']);
                $customerstatistics_act->set_group($customerrecord_res['group']);
                $customerstatistics_act->set_group_members($customerrecord_res['group_members']);
                $customerstatistics_act->set_suser_id($customerrecord_res['suserid']);
                $customerstatistics_act->set_category($customerrecord_res['category']);
                $result = $customerstatistics_act->insert($db);
                //if(!$result0['0']) die_error (PDO_ERROR_CODE, '日接待数新增失败');
            }
            break;
        //补欠款
        case 1:
            if($count){
                $customerstatistics_act->set_receive_amount(($customerstatistics_res['receive_amount']+$money));
                $customerstatistics_act->set_have_received(($customerstatistics_res['have_received']+$isupdate));
                $customerstatistics_act->set_where_and(p_customerstatistics::$field_id, SqlOperator::Equals, $customerstatistics_res['id']);
                $result = $customerstatistics_act->update($db,true);
               // if(!$result['0']) die_error (PDO_ERROR_CODE, '日补欠款数更新失败');
            }else{
                $customerstatistics_act->set_user_id($customer_id);
                $customerstatistics_act->set_user_name($username);
                $customerstatistics_act->set_date($date);
                $customerstatistics_act->set_receive_amount($money);
                $customerstatistics_act->set_have_received($isupdate);
                $customerstatistics_act->set_team($customerrecord_res['team']);
                $customerstatistics_act->set_group($customerrecord_res['group']);
                $customerstatistics_act->set_group_members($customerrecord_res['group_members']);
                $customerstatistics_act->set_suser_id($customerrecord_res['suserid']);
                $customerstatistics_act->set_category($customerrecord_res['category']);
                $result = $customerstatistics_act->insert($db);
               // if(!$result['0']) die_error (PDO_ERROR_CODE, '日补欠款数新增失败');
            }
            break;
        //个人退款数
        case 2:
            if($count){
                $customerstatistics_act->set_refund_num(($customerstatistics_res['refund_num']+$ind_refund_rate));
                $customerstatistics_act->set_refund_amount(($customerstatistics_res['refund_amount']+$money));
                $customerstatistics_act->set_where_and(p_customerstatistics::$field_id, SqlOperator::Equals, $customerstatistics_res['id']);
                $result = $customerstatistics_act->update($db,true);
              //  if(!$result['0']) die_error (PDO_ERROR_CODE, '日个人退款数更新失败');
            }else{
                $customerstatistics_act->set_user_id($customer_id);
                $customerstatistics_act->set_user_name($username);
                $customerstatistics_act->set_date($date);
                $customerstatistics_act->set_refund_num($ind_refund_rate);
                $customerstatistics_act->set_refund_amount($money);
                $customerstatistics_act->set_team($customerrecord_res['team']);
                $customerstatistics_act->set_group($customerrecord_res['group']);
                $customerstatistics_act->set_group_members($customerrecord_res['group_members']);
                $customerstatistics_act->set_suser_id($customerrecord_res['suserid']);
                $customerstatistics_act->set_category($customerrecord_res['category']);
                $result = $customerstatistics_act->insert($db);
                //if(!$result['0']) die_error (PDO_ERROR_CODE, '日个人退款数新增失败');
            }
            break;
        //流量金额
        case 3:
            //新增或者删除流量业绩，会对出单数造成影响
            $customerstatistics_act->set_singular(($customerstatistics_res['singular']+$ind_refund_rate));
          
            if($count){               
                $customerstatistics_act->set_flow_num(($customerstatistics_res['flow_num']+$money));
                $customerstatistics_act->set_where_and(p_customerstatistics::$field_id, SqlOperator::Equals, $customerstatistics_res['id']);
                $result = $customerstatistics_act->update($db,true);
              //  if(!$result['0']) die_error (PDO_ERROR_CODE, '日流量金额更新失败');
            }else{
               
                $customerstatistics_act->set_user_id($customer_id);
                $customerstatistics_act->set_user_name($username);
                $customerstatistics_act->set_date($date);
                $customerstatistics_act->set_flow_num($money);
                $customerstatistics_act->set_team($customerrecord_res['team']);
                $customerstatistics_act->set_group($customerrecord_res['group']);
                $customerstatistics_act->set_group_members($customerrecord_res['group_members']);
                $customerstatistics_act->set_suser_id($customerrecord_res['suserid']); 
                $customerstatistics_act->set_category($customerrecord_res['category']);
                $result = $customerstatistics_act->insert($db);
               // if(!$result['0']) die_error (PDO_ERROR_CODE, '日流量金额新增失败');
            }
            break;
        //实物金额
        case 4:
            if($count){                
                $customerstatistics_act->set_physical_things(($customerstatistics_res['physical_things']+$money));
                $customerstatistics_act->set_where_and(p_customerstatistics::$field_id, SqlOperator::Equals, $customerstatistics_res['id']);
                $result = $customerstatistics_act->update($db,true);
               // if(!$result['0']) die_error (PDO_ERROR_CODE, '日实物金额更新失败');
            }else{               
                $customerstatistics_act->set_user_id($customer_id);
                $customerstatistics_act->set_user_name($username);
                $customerstatistics_act->set_date($date);
                $customerstatistics_act->set_physical_things($money);
                $customerstatistics_act->set_team($customerrecord_res['team']);
                $customerstatistics_act->set_group($customerrecord_res['group']);
                $customerstatistics_act->set_group_members($customerrecord_res['group_members']);
                $customerstatistics_act->set_suser_id($customerrecord_res['suserid']);
                $customerstatistics_act->set_category($customerrecord_res['category']);
                $result = $customerstatistics_act->insert($db);
              //  if(!$result['0']) die_error (PDO_ERROR_CODE, '日实物金额新增失败');
            }
            break;
        //装修金额     
        case 5:
            if($count){          
                $customerstatistics_act->set_decorate(($customerstatistics_res['decorate']+$money));
                $customerstatistics_act->set_where_and(p_customerstatistics::$field_id, SqlOperator::Equals, $customerstatistics_res['id']);
                $result = $customerstatistics_act->update($db,true);
               // if(!$result['0']) die_error (PDO_ERROR_CODE, '日装修金额更新失败');
            }else{              
                $customerstatistics_act->set_user_id($customer_id);
                $customerstatistics_act->set_user_name($username);
                $customerstatistics_act->set_date($date);
                $customerstatistics_act->set_decorate($money);
                $customerstatistics_act->set_team($customerrecord_res['team']);
                $customerstatistics_act->set_group($customerrecord_res['group']);
                $customerstatistics_act->set_group_members($customerrecord_res['group_members']);
                $customerstatistics_act->set_suser_id($customerrecord_res['suserid']); 
                $customerstatistics_act->set_category($customerrecord_res['category']);
                $result = $customerstatistics_act->insert($db);
              //  if(!$result['0']) die_error (PDO_ERROR_CODE, '日装修金额新增失败');
            }
            break;
    }
}
