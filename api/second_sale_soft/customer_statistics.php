<?php

/**
 * 售后统计
 * 
 */
use Models\Base\Model;
use Models\p_customerstatistics_soft;
use Models\p_customerrecord_soft;
use Models\Base\SqlOperator;
use Models\Base\SqlSortType;
use Models\p_platform_soft;
use Models\p_physica_soft;
use Models\p_decoration_soft;
require '../../Common/ExportData2Excel.php';
require '../../application.php';
require '../../loader-api.php';

$action = request_action();
execute_request(HttpRequestMethod::Get, function() use($action) {
    
    //判断当前登录的用户是否有权限查看所有的售后统计记录，没有的话就只能查看他自己的售后统计记录
    $userid = request_userid();       
    $manager_role_ids = array(0, 401, 402, 403, 404, 405, 406, 407, 408, 1103); //超级管理员能看所有记录
    $manger_user_ids =array(1,161,163);//目前除了超级管理员只允许乔峰，虚竹能查看所有售后的统计记录。其他售后只能看自己的记录，其他部门和角色的都不能看售后统计.为了调试方便，暂时加上422
    $employee = get_employees()[$userid];
    $role_id = $employee['role_id'];
    $user_id = $employee['userid'];
    $is_role_manager = in_array($role_id, $manager_role_ids);
    $is_user_manager = in_array($user_id, $manger_user_ids);
    $is_manager = $is_role_manager || $is_user_manager;
    //日统计
    if($action == 1){
        $searchTeam = get_team_id()[request_string('searchTeam')];
        $searchGroup = get_group_id()[request_string('searchGroup')];
        $searchName = request_string('searchName');
        $searchTime = request_string('searchTime');      
        $h = (int) date("H");
        if (!isset($searchTime)) {
            $searchTime = date("Y-m-d");
            if ($h < 3) {
                $searchTime = date('Y-m-d', strtotime("-1 day"));
            }
        }
        $customerstatistics = new p_customerstatistics_soft();
        if(isset($searchTeam)){
            $customerstatistics->set_where_and(p_customerstatistics_soft::$field_team, SqlOperator::Equals, $searchTeam);
        }
        if(isset($searchGroup)){
            $customerstatistics->set_where_and(p_customerstatistics_soft::$field_group, SqlOperator::Equals, $searchGroup);
        }
        if (isset($searchName)) {
            $customerstatistics->set_where_and(p_customerstatistics_soft::$field_user_name, SqlOperator::Like, '%'.$searchName.'%');
        }
        if (!$is_manager) {
             $customerstatistics->set_custom_where(" AND ( user_id = '" . $userid . "' OR suser_id = '" . $userid . "' ) "); 
        }
        if (isset($searchTime)) {
            $customerstatistics->set_custom_where(" AND DATE_FORMAT(date,'%Y-%m-%d') = '" . $searchTime . "' ");
        }
        
        $customerstatistics->set_order_by(p_customerstatistics_soft::$field_date, SqlSortType::Desc);
        $customerstatistics->set_limit_paged(request_pageno(), request_pagesize());
        $db = create_pdo();
        $customerstatistics_res = Model::query_list($db, $customerstatistics, null, true);
        if (!$customerstatistics_res[0]) die_error(USER_ERROR, "获取售后统计资料失败，请重试");

        $models = Model::list_to_array($customerstatistics_res['models'], array(), function(&$d) use($is_manager, $userid, $searchTime, $db) {                    
             
        $d['second_sales'] = round(($d['flow_num']+$d['physical_things']+$d['decorate']),2);//二销/日
        $d['ww_commission'] = round(($d['receive_amount']*get_customer_commission($d['second_sales'],$d,0)),2);//旺旺提成/日
        $d['second_commission'] = round(($d['second_sales']*get_customer_commission($d['second_sales'],$d,1)),2);//二销提成/日
        $d['conversion_rate'] = round((($d['singular'] / $d['have_received']) * 100),2)."%";//转化率/日
        $d['average_price'] = round(($d['second_sales'] / $d['have_received']),2);//均价/日
        $d['total_commission'] = round(($d['ww_commission']+$d['second_commission']),2);//合计提成/日
        if ($is_manager) {
            $d['is_manager'] = $is_manager;
        }
            }); 
       $models = merge_double_group($models);
        //添加统计总数
            if($is_manager){
                $statistics_total_data = statistics_total_data(1,$searchTime,$searchTeam,$searchGroup,$searchName);
                $total_array = statistics_total_for_unit_time($statistics_total_data);
                $total_array = statistics_total_average($total_array,count($statistics_total_data));
                $models = add_statistics_total($is_manager,$models,$total_array);
            }   
        echo_list_result($customerstatistics_res, $models, array('is_manager' => $is_manager,'total_statistics_data'=>get_title_statistics_data()));
    }
    //月统计
    if($action == 2){
        $searchTeam = get_team_id()[request_string('searchTeam')];
        $searchGroup = get_group_id()[request_string('searchGroup')];
        $searchName = request_string('searchName');
        $searchTime = request_string('searchTime');
       
        if (!isset($searchTime)) {
            $searchTime = date("Y-m");
        }
        $customerstatistics = new p_customerstatistics_soft();
        $sql = " AND DATE_FORMAT(s.date,'%Y-%m') = '" . $searchTime . "' ";
        
        if (!$is_manager) {
            $sql.=" AND (s.user_id = '" . $userid . "' OR s.suser_id = '".$userid."')";
        }
        if (isset($searchName)) {
            $sql .= " AND s.user_name like '%" . $searchName . "%' ";
        }
        if (isset($searchTeam)) {
            $sql.=" AND s.team = '" . $searchTeam . "' ";
        }
        if(isset($searchGroup)){
            $sql.=" AND s.group = '" . $searchGroup . "' ";
        }
        $sql = sql_month_statistics($sql);
        $db = create_pdo();
        $count_result = Model::execute_custom_sql($db, $sql);
        if (!$count_result[0]) die_error(USER_ERROR, "获取统计资料失败，请重试");
        $total_count = $count_result['count'];
        $max_page_no = ceil($total_count / request_pagesize());      
        $sql .= "LIMIT " . request_pagesize() * (request_pageno() - 1) . "," . request_pagesize();
        $result = Model::query_list($db, $customerstatistics, $sql, true);
        if (!$result[0]) {
            die_error(USER_ERROR, '获取统计资料失败，请重试');
        }
        $models = Model::list_to_array($result['models'], array(), function(&$d) use($is_manager, $userid, $searchTime, $db){
         
        $d['second_sales'] = round(($d['flow_num']+$d['physical_things']+$d['decorate']),2);//二销/月
        $d['ww_commission'] = round(($d['receive_amount']*get_customer_commission($d['second_sales'],$d,0)),2);//旺旺提成/月    
        $d['second_commission'] = round(($d['second_sales']*get_customer_commission($d['second_sales'],$d,1)),2); //二销提成/月
        $d['conversion_rate'] = round((($d['singular'] / $d['have_received']) * 100),2). "%";//转化率/月
        $d['average_price'] = round(($d['second_sales'] / $d['have_received']),2);//均价/月
        $d['refund_rate'] = round((($d['refund_num'] / $d['have_received']) * 100),2). "%";//退款率/月
        $d['deductions_rate'] =get_deductions_rate(round(($d['refund_num'] / $d['have_received'])*100,2));//扣款率/月       
        $d['deductions'] = round($d['refund_amount']*(float)$d['deductions_rate']/100,2);//扣款金额/月
        $d['average_deductions'] = get_average_deductions($d['average_price'],$d['team']);//均价扣款/月
        $average_deductions_temp = is_no_average_deductions($d['average_deductions']);
        $d['average_deductions'] = $average_deductions_temp?round($d['second_commission'] * (float)$d['average_deductions']/100,2):$d['average_deductions'];  
        $d['total_commission'] = round(($d['ww_commission']+$d['second_commission']-$d['deductions']-(float)$d['average_deductions']),2);//合计提成/月
        if ($is_manager) {
                $d['is_manager'] = $is_manager;
            }
        }); 
        $models = merge_double_group($models);
         //添加统计总数
        if($is_manager){
            $statistics_total_data = statistics_total_data(2,$searchTime,$searchTeam,$searchGroup,$searchName);
            $total_array = statistics_total_for_unit_time($statistics_total_data,2);
            $total_array = statistics_total_average($total_array,count($statistics_total_data),2);
            $models = add_statistics_total($is_manager,$models,$total_array);
        }
        echo_result(array('code' => 0, 'list' => $models, 'is_manager' => $is_manager, 'total_count' => $total_count, 'page_no' => request_pageno(), 'max_page_no' => $max_page_no, 'start_year' => START_YEAR, 'current_year' => date('Y')));
    }
    //日导出
    if ($action == 11) {
        $startTime = request_datetime("start_time");
        $endTime = request_datetime("end_time");
        $export = new ExportData2Excel();
        $customerStatistics = new p_customerstatistics_soft();
        if (isset($startTime)) {
            $customerStatistics->set_custom_where(" AND DATE_FORMAT(date, '%Y-%m-%d') >= '" . $startTime . "' ");
        }
        if (isset($endTime)) {
            $customerStatistics->set_custom_where(" AND DATE_FORMAT(date, '%Y-%m-%d') <= '" . $endTime . "' ");
        }
        $customerStatistics->set_order_by(p_customerstatistics_soft::$field_date, 'desc');
        $db = create_pdo();
        $result = Model::query_list($db, $customerStatistics);
        if (!$result[0]) {
            $export->create(array('导出错误'), array(array('售前统计(日)数据导出失败,请稍后重试!')), "售前统计(日)数据导出", "售前统计(日)");
        }
        
        $models = Model::list_to_array($result['models'], array(), function(&$d) use($db) {
               
            $d['second_sales'] = round(($d['flow_num']+$d['physical_things']+$d['decorate']),2);//二销/日
            $d['ww_commission'] = round(($d['receive_amount']*get_customer_commission($d['second_sales'],$d,0)),2);//旺旺提成/日
            $d['second_commission'] = round(($d['second_sales']*get_customer_commission($d['second_sales'],$d,1)),2);//二销提成/日
            $d['conversion_rate'] = round((($d['singular'] / $d['have_received']) * 100),2)."%";//转化率/日
            $d['average_price'] = round(($d['second_sales'] / $d['have_received']),2);//均价/日
            $d['total_commission'] = round(($d['ww_commission']+$d['second_commission']),2);//合计提成/日
            $d['date'] = substr($d['date'],0,10);
                });
        $models = merge_double_group($models);
        //添加统计总数
        $is_manager = true;
        $total_array = statistics_total_for_unit_time($models);
        $total_array = statistics_total_average($total_array,count($models));
        $models = add_statistics_total($is_manager,$models,$total_array);
        
        $title_array = array('姓名','接待/日','接待金额/日','旺旺提成/日','退款数/日','退款金额/日','转Q数/日','流量/日','实物/日','装修/日','二销/日','二销提成/日','出单数/日','转化率/日', '均价/日','提成合计/日', '日期');
        $field = array('user_name','have_received','receive_amount','ww_commission','refund_num','refund_amount','change_Q','flow_num','physical_things','decorate','second_sales','second_commission','singular','conversion_rate','average_price','total_commission', 'date');
        $export->set_field($field);
        $export->set_field_width(array(12, 12, 12, 12, 12, 12, 12, 12, 12, 12, 12, 12, 12, 12, 12, 12,25));
        $export->create($title_array, $models, "售后统计(日)数据导出", "售后统计(日)");
    }
    //月导出
    if ($action == 12) {
        $startTime = request_string("start_time");
        $endTime = request_string("end_time");
        $export = new ExportData2Excel();
        $customerStatistics = new p_customerstatistics_soft();
        $date_sql = '';
         if (isset($startTime)) {
            $date_sql.= " AND DATE_FORMAT(s.date, '%Y-%m') >= '" . $startTime . "' ";
        }
        if (isset($endTime)) {
            $date_sql.= " AND DATE_FORMAT(s.date, '%Y-%m') <= '" . $endTime . "' ";
        }
        $sql = sql_month_statistics($date_sql); 
        $customerStatistics->set_order_by(p_customerstatistics_soft::$field_date, 'desc');
        $db = create_pdo();
        $result = Model::query_list($db, $customerStatistics,$sql);
        if (!$result[0]) {
            $export->create(array('导出错误'), array(array('售前统计(月)数据导出失败,请稍后重试!')), "售前统计(月)数据导出", "售前统计(月)");
        }       
        $models = Model::list_to_array($result['models'], array(), function(&$d) use($db) {

            $d['second_sales'] = round(($d['flow_num']+$d['physical_things']+$d['decorate']),2);//二销/月
            $d['ww_commission'] = round(($d['receive_amount']*get_customer_commission($d['second_sales'],$d,0)),2);//旺旺提成/月    
            $d['second_commission'] = round(($d['second_sales']*get_customer_commission($d['second_sales'],$d,1)),2); //二销提成/月
            $d['conversion_rate'] = round((($d['singular'] / $d['have_received']) * 100),2). "%";//转化率/月
            $d['average_price'] = round(($d['second_sales'] / $d['have_received']),2);//均价/月
            $d['refund_rate'] = round((($d['refund_num'] / $d['have_received']) * 100),2). "%";//退款率/月
            $d['deductions_rate'] =get_deductions_rate(round(($d['refund_num'] / $d['have_received'])*100,2));//扣款率/月       
            $d['deductions'] = round($d['refund_amount']*(float)$d['deductions_rate']/100,2);//扣款金额/月
            $d['average_deductions'] = get_average_deductions($d['average_price'],$d['team']);//均价扣款/月
            $average_deductions_temp = is_no_average_deductions($d['average_deductions']);
            $d['average_deductions'] = $average_deductions_temp?round($d['second_commission'] * (float)$d['average_deductions']/100,2):$d['average_deductions'];  
            $d['total_commission'] = round(($d['ww_commission']+$d['second_commission']-$d['deductions']-(float)$d['average_deductions']),2);//合计提成/月
            $d['date'] = substr($d['date'],0,7);
                });
            $models = merge_double_group($models);
         //添加统计总数
        $is_manager = true; 
        $total_array = statistics_total_for_unit_time($models,2);
        $total_array = statistics_total_average($total_array,count($models),2);
        $models = add_statistics_total($is_manager,$models,$total_array);
        
        $title_array = array('姓名','接待/月','接待金额/月','旺旺提成/月','退款数/月','退款金额/月','退款率/月','扣款率/月','扣款金额/月','转Q数/月','流量/月','实物/月','装修/月','二销/月','二销提成/月','出单数/月','转化率/月', '均价/月','均价扣款/月','提成合计/月', '日期');
        $field = array('user_name','have_received','receive_amount','ww_commission','refund_num','refund_amount','refund_rate','deductions_rate','deductions','change_Q','flow_num','physical_things','decorate','second_sales','second_commission','singular','conversion_rate','average_price','average_deductions','total_commission', 'date');
        $export->set_field($field);
        $export->set_field_width(array(12, 12, 12, 12, 12, 12, 12, 12,12,12,12,12, 12, 12, 12, 12, 12, 12, 12, 12,25));
        $export->create($title_array, $models, "售后统计(月)数据导出", "售后统计(月)");
    }
    //排行榜
    if($action ==13){
           $time_unit = request_string("time_unit");
           $team = request_string("team");
           $ranking_group = array(1=>1.5,2=>1);
           $customerStatistics = new p_customerstatistics_soft();
           $db = create_pdo();
           switch($time_unit){
               case 1:
               default:                  
                   $sql = 'SELECT pc.user_name,pc.group,pc.flow_num+pc.physical_things+pc.decorate AS sum FROM p_customerstatistics_soft AS pc LEFT JOIN m_user AS mu ON pc.user_id=mu.userid WHERE mu.`status` =1';
                   $time = date("Y-m-d 00:00:00",time());       
                   $sql.=" AND DATE_FORMAT(date,'%Y-%m-%d %H:%i:%s') = '" . $time . "' ";
                   $sql.=' AND pc.team = '.$team;
                   $sql.=' ORDER BY pc.flow_num+pc.physical_things+pc.decorate DESC';
                   $result_temp = Model::execute($db, $sql,$customerStatistics);
                   $result_temp = $result_temp['results'];
                   $result = array();
                   foreach($result_temp as $key=>$data_array){
//                      if($data_array['sum'] == 0)
//                          continue;
                       $result[$key]['user_name'] = $data_array['user_name'];
                       $result[$key]['sum'] = round($data_array['sum'],2);
                       $result[$key]['proportion'] =round($data_array['sum'] * $ranking_group[$data_array['group']],2);
                   }
                   echo_result($result);
                   break;
               case 2: 
                    $searchTime = date("Y-m");
                    $date_sql = " AND DATE_FORMAT(s.date,'%Y-%m') = '" . $searchTime . "' ";                 
                    $sql="SELECT * FROM (
                    (SELECT 
                    s.user_name,s.group,SUM(s.have_received) AS have_received,SUM(s.flow_num)+SUM(s.physical_things)+SUM(s.decorate) AS second_sales,
                    SUM(s.singular) AS singular,date 
                    FROM p_customerstatistics_soft AS s LEFT JOIN m_user AS mu ON s.user_id=mu.userid WHERE s.group=2 ".$date_sql."  AND mu.`status` =1 AND s.team=".$team." GROUP BY s.group_members)
                    UNION ALL
                    (SELECT 
                    s.user_name,s.group,SUM(s.have_received) AS have_received,SUM(s.flow_num)+SUM(s.physical_things)+SUM(s.decorate) AS second_sales,
                    SUM(s.singular) AS singular,date 
                    FROM p_customerstatistics_soft AS s LEFT JOIN m_user AS mu ON s.user_id=mu.userid WHERE s.group=1 ".$date_sql." AND mu.`status` =1 AND s.team=".$team." GROUP BY s.user_id)) AS u 
                    WHERE 1=1 ORDER BY second_sales DESC";
                   
                    $result_temp = Model::execute($db, $sql,$customerStatistics);  
                    $result_temp = $result_temp['results'];
                    $result = array();
                    $promotion_level_number = calculate_promotion_level_number(count($result_temp));
                    $index = 1;
                     foreach($result_temp as $key=>$data_array){
//                         if($data_array['second_sales'] == 0)
//                             continue;
                       $result[$key]['user_name'] = $data_array['user_name'];
                       $result[$key]['second_sales'] = round($data_array['second_sales'],2);
                       $result[$key]['proportion'] = round($data_array['second_sales'] * $ranking_group[$data_array['group']],2);
                       $result[$key]['conversion_rate'] = round((($data_array['singular'] / $data_array['have_received']) * 100) ,2) . "%";//转化率/月
                       $result[$key]['average_price'] = sprintf("%.4f", ($data_array['second_sales'] / $data_array['have_received'])); //均价/月
                       $result[$key]['average_price'] = round(($data_array['second_sales'] / $data_array['have_received']),2); //均价/月
                       //计算晋升等级
                       foreach($promotion_level_number as $level_array){
                            if($index<$level_array['count']){
                                $result[$key]['promotion_level'] = $level_array['level'];
                                $index++;
                                break;
                            }
                            elseif($index==$level_array['count']){
                                $result[$key]['promotion_level'] = $level_array['level'];
                                $index=1;
                                array_shift($promotion_level_number);
                                break;
                            }
                        }
                         //如果多出来的就默认为最后一档
                       $result[$key]['promotion_level']=isset($result[$key]['promotion_level'])?$result[$key]['promotion_level']:'↓2';
                   }   
                   echo_result($result);
                   break;
           } 
    }
});

execute_action(HttpRequestMethod::Post, 1, function() {
    $customerStatisticsData = request_object();
    $customerstatistics = new p_customerstatistics_soft();
    
    $customerstatistics->set_change_Q($customerStatisticsData->change_Q);
    if($customerStatisticsData->is_manager)
    {
        $customerstatistics->set_have_received($customerStatisticsData->have_received);
        $customerstatistics->set_receive_amount($customerStatisticsData->receive_amount);
        $customerstatistics->set_singular($customerStatisticsData->singular);
        $customerstatistics->set_refund_num($customerStatisticsData->refund_num);
        $customerstatistics->set_refund_amount($customerStatisticsData->refund_amount);
        $customerstatistics->set_flow_num($customerStatisticsData->flow_num);
        $customerstatistics->set_physical_things($customerStatisticsData->physical_things);
        $customerstatistics->set_decorate($customerStatisticsData->decorate);
    }
   
    $customerstatistics->set_where_and(p_customerstatistics_soft::$field_id, SqlOperator::Equals, $customerStatisticsData->id);
    $db = create_pdo();
    pdo_transaction($db, function($db) use($customerstatistics) {
        $result = $customerstatistics->update($db,true);
        if (!$result[0]) die_error(PDO_ERROR_CODE, '修改失败');
    });
    echo_msg('修改成功');
});
 //在列表上方实时显示流量业绩、实物业绩、装修业绩的笔数和金额
function get_title_statistics_data(){ 
        $db = create_pdo();
        $searchTime = date("Y-m-d");      
        if(date("H")<3){
            $searchTime = date("Y-m-d",strtotime("-1 day"));
        }
        $searchStartTime = date("Y-m-d 03:00:00", strtotime($searchTime));
        $searchEndTime = date("Y-m-d 03:00:00", strtotime('+1 day', strtotime($searchTime)));
        $total_array = array(
            'channel_baidu'=>array(
                'platform'=>array('count'=>0,'money'=>0),
                'physica'=>array('count'=>0,'money'=>0),
                'decoration'=>array('count'=>0,'money'=>0)
                ),
            'channel_360'=>array(
                'platform'=>array('count'=>0,'money'=>0),
                'physica'=>array('count'=>0,'money'=>0),
                'decoration'=>array('count'=>0,'money'=>0)
                ),
            'total'=>array('count'=>0,'money'=>0)
        );
        $total_count=$total_money=0;
        $channel_array=array(1=>'channel_baidu',2=>'channel_360');
        $sql="SELECT SUM(platform.money) AS platform_money,COUNT(0) AS platform_count,customerrecord.team FROM p_platform_soft AS platform LEFT JOIN p_customerrecord_soft AS customerrecord ON platform.customer_id = customerrecord.userid WHERE DATE_FORMAT(add_time,'%Y-%m-%d %H:%i:%s') >= '" . $searchStartTime . "' AND DATE_FORMAT(add_time,'%Y-%m-%d %H:%i:%s') <= '" . $searchEndTime . "' GROUP BY customerrecord.team";
        $result = Model::execute($db, $sql); 
        $result = $result['results'];     
        foreach($result as $data){
           // $total_array[$data['team']]['platform']=array($data['platform_count']=>$data['platform_money']);
             $total_array[$channel_array[$data['team']]]['platform']=array('count'=>$data['platform_count'],'money'=>$data['platform_money']);
            $total_count+=$data['platform_count'];
            $total_money+=$data['platform_money'];
        }
        $sql="SELECT SUM(physica.agent_price) AS physica_money,COUNT(0) AS physica_count,customerrecord.team FROM p_physica_soft AS physica LEFT JOIN p_customerrecord_soft AS customerrecord ON physica.customer_id = customerrecord.userid WHERE DATE_FORMAT(add_time,'%Y-%m-%d %H:%i:%s') >= '" . $searchStartTime . "' AND DATE_FORMAT(add_time,'%Y-%m-%d %H:%i:%s') <= '" . $searchEndTime . "' GROUP BY customerrecord.team";
        $result = Model::execute($db, $sql);  
        $result = $result['results'];
          foreach($result as $data){
          //  $total_array[$data['team']]['physica']=array($data['physica_count']=>$data['physica_money']);
            $total_array[$channel_array[$data['team']]]['physica']=array('count'=>$data['physica_count'],'money'=>$data['physica_money']);
            $total_count+=$data['physica_count'];
            $total_money+=$data['physica_money'];
        }
        $sql="SELECT SUM(decoration.decoration_price) AS decoration_money ,COUNT(0) AS decoration_count,customerrecord.team FROM p_decoration_soft AS decoration LEFT JOIN p_customerrecord_soft AS customerrecord ON decoration.customer_id = customerrecord.userid WHERE DATE_FORMAT(add_time,'%Y-%m-%d %H:%i:%s') >= '" . $searchStartTime . "' AND DATE_FORMAT(add_time,'%Y-%m-%d %H:%i:%s') <= '" . $searchEndTime . "' GROUP BY customerrecord.team";
        $result = Model::execute($db, $sql);  
        $result = $result['results'];
        foreach($result as $data){
            //$total_array[$data['team']]['decoration']=array($data['decoration_count']=>$data['decoration_money']);
            $total_array[$channel_array[$data['team']]]['decoration']=array('count'=>$data['decoration_count'],'money'=>$data['decoration_money']);
            $total_count+=$data['decoration_count'];
            $total_money+=$data['decoration_money'];
        }
        $total_array['total']=array('count'=>$total_count,'money'=>$total_money);
        return $total_array;
}
function get_customer_commission($receive_amount,$res,$type){

    $amount = array(
        1 => array(4000,6000),
        2 => array(5200,7800),
        3 => array(6400,9600),
        4 => array(7600,11400),
        5 => array(8800,13200),
        6 => array(10000,15000),
        7 => array(11200,16800),  
        8 => array(12400,18600)
    );
    if($receive_amount < $amount[1][$res['group'] - 1]) {
        $point = 0;
    }else if($receive_amount > $amount[8][$res['group'] - 1]){
        $point = 8;
    }else{
        foreach ($amount as $key => $value){
            if($receive_amount < $value[$res['group'] - 1]) {
                $point = $key - 1;
                break;
            }
        }
    }
    return get_customer_commission_point()[$res['group']][$res['category']][$type][$point];
}

function get_team_id(){
    
    return array(
        '百度' => 1,
        '360' => 2
    );
}
function get_group_id(){
    return array(
        '单人组' => 1,
        '双人组' => 2
    );
}

function get_deductions_rate($refund_rate){
    $arr = array(
        '3' => '1%',
        '3.5' => '2%',
        '4' => '3%',
        '4.5' => '5%',
        '5' => '10%',
        '5.5' => '15%',
        '6' => '20%'
    );
    if($refund_rate >= 6){
        return '20%';
    }else if($refund_rate < 3){
        return 0;
    }else{
        foreach ($arr as $key => $value) {
           
            if($refund_rate >= $key){
                $really_value = $value;
            } 
        }
        return $really_value;
    }
}

function get_average_deductions($average_price,$team){  
    $arr = array(
        '2' => array(
            '450' => '停岗学习',
            '500' => '15.00%',
            '550' => '12.50%',
            '600' => '10.00%',
            '650' => '7.50%',
            '700' => '5.00%',
            '750' => '2.50%',
            '800' => '0.00%'
        ),
        '1' => array(
            '400' => '停岗学习',
            '500' => '15.00%',
            '550' => '12.50%',
            '600' => '10.00%',
            '650' => '7.50%',
            '700' => '5.00%',
            '750' => '2.50%',
            '800' =>  '0.00%'
        )
    );
    $average_deductions = false;
    foreach ($arr[$team] as $key => $value){
        if($average_price >= $key) {
            $average_deductions = $value;
        }
    }   
    $average_deductions= $average_deductions?$average_deductions:'辞退/换部门';
    return $average_deductions;
}

function is_no_average_deductions($average_deductions)
{
    $average_deductions = ($average_deductions == '停岗学习' || $average_deductions == '辞退/换部门')? false:$average_deductions;
    return $average_deductions;
}
//当日或当月统计的数据源（排除了分页，搜索条件的总数据）
function statistics_total_data($unit_time,$searchTime=0,$searchTeam=0,$searchGroup=0,$searchName=0){
    $customerstatistics = new p_customerstatistics_soft();
    $db = create_pdo();
    switch ($unit_time){
        case 1:
        default :          
            $h = (int) date("H");
           if (!$searchTime) {
                    $searchTime = date("Y-m-d");
               if ($h < 3) {
                    $searchTime = date('Y-m-d', strtotime("-1 day"));
               }
           }       
            $customerstatistics->set_custom_where(" AND DATE_FORMAT(date,'%Y-%m-%d') = '" . $searchTime . "' "); 
            if($searchTeam){
                    $customerstatistics->set_where_and(p_customerstatistics_soft::$field_team, SqlOperator::Equals, $searchTeam);
                }   
            if($searchGroup){
                    $customerstatistics->set_where_and(p_customerstatistics_soft::$field_group, SqlOperator::Equals, $searchGroup);
                }
            if ($searchName) {
                    $customerstatistics->set_where_and(p_customerstatistics_soft::$field_user_name, SqlOperator::Like, '%'.$searchName.'%');
                }
            $customerstatistics_res = Model::query_list($db, $customerstatistics, null, true);
            $models = Model::list_to_array($customerstatistics_res['models'], array(), function(&$d) use($is_manager, $userid, $searchTime, $db) {                    
            $d['second_sales'] = round(($d['flow_num']+$d['physical_things']+$d['decorate']),2);//二销/日
            $d['ww_commission'] = round(($d['receive_amount']*get_customer_commission($d['second_sales'],$d,0)),2);//旺旺提成/日
            $d['second_commission'] = round(($d['second_sales']*get_customer_commission($d['second_sales'],$d,1)),2);//二销提成/日
            $d['conversion_rate'] = round((($d['singular'] / $d['have_received']) * 100),2)."%";//转化率/日
            $d['average_price'] = round(($d['second_sales'] / $d['have_received']),2);//均价/日
            $d['total_commission'] = round(($d['ww_commission']+$d['second_commission']),2);//合计提成/日
                });
            $models = merge_double_group($models);
            break;
        case 2:
            $sql='';
            if (!$searchTime) {
                $searchTime = date("Y-m");
            }
            $sql .= " AND DATE_FORMAT(s.date,'%Y-%m') = '" . $searchTime . "' "; 
            if ($searchName) {
                $sql .= " AND s.user_name like '%" . $searchName . "%' ";
            }
            if ($searchTeam) {
                $sql.=" AND s.team = '" . $searchTeam . "' ";
            }
            if($searchGroup){
                $sql.=" AND s.group = '" . $searchGroup . "' ";
            }
            $sql = sql_month_statistics($sql);
            $result = Model::query_list($db, $customerstatistics, $sql, true);
            $models = Model::list_to_array($result['models'], array(), function(&$d) use($is_manager, $userid, $searchTime, $db){       
            $d['second_sales'] = round(($d['flow_num']+$d['physical_things']+$d['decorate']),2);//二销/月
            $d['ww_commission'] = round(($d['receive_amount']*get_customer_commission($d['second_sales'],$d,0)),2);//旺旺提成/月    
            $d['second_commission'] = round(($d['second_sales']*get_customer_commission($d['second_sales'],$d,1)),2); //二销提成/月
            $d['conversion_rate'] = round((($d['singular'] / $d['have_received']) * 100),2). "%";//转化率/月
            $d['average_price'] = round(($d['second_sales'] / $d['have_received']),2);//均价/月
            $d['refund_rate'] = round((($d['refund_num'] / $d['have_received']) * 100),2). "%";//退款率/月
            $d['deductions_rate'] =get_deductions_rate(round(($d['refund_num'] / $d['have_received'])*100,2));//扣款率/月       
            $d['deductions'] = round($d['refund_amount']*(float)$d['deductions_rate']/100,2);//扣款金额/月
            $d['average_deductions'] = get_average_deductions($d['average_price'],$d['team']);//均价扣款/月
            $average_deductions_temp = is_no_average_deductions($d['average_deductions']);
            $d['average_deductions'] = $average_deductions_temp?round($d['second_commission'] * (float)$d['average_deductions']/100,2):$d['average_deductions'];  
            $d['total_commission'] = round(($d['ww_commission']+$d['second_commission']-$d['deductions']-(float)$d['average_deductions']),2);//合计提成/月
        }); 
            $models = merge_double_group($models);
            break;
    } 
    return $models;
}
//统计指定时间内的总数
function statistics_total_for_unit_time($data_array,$unit_time=1){
    if(!count($data_array))
        return array();
    $data_total = array();
    $unit_time_array = array(
    1=>array(
        'have_received','receive_amount','ww_commission','refund_num','refund_amount','change_Q','flow_num','physical_things','decorate','second_sales','second_commission','singular','conversion_rate','average_price','total_commission'
    ),
    2=>array(
        'have_received','receive_amount','ww_commission','refund_num','refund_amount','refund_rate','deductions_rate','deductions','change_Q','flow_num','physical_things','decorate','second_sales','second_commission','singular','conversion_rate','average_price','average_deductions','total_commission'
    )
);
    $data_total['id'] = 0;
    $data_total['user_id'] = 0;
    $data_total['user_name'] = $unit_time==1?"当日统计":"当月统计";
    $data_total['date'] = $data_array[0]['date'];
    
    foreach($data_array as $data_index_array){
        foreach($data_index_array as $key=>$data){
            if(in_array($key,$unit_time_array[$unit_time])){
                 $data_total[$key]+=$data;
            }
        }
    }
  //  $data_total['conversion_rate'] = $data_total['conversion_rate'].'%';
    return $data_total;
}
//有一些统计是要计算平均值的
function statistics_total_average($data_total,$sum,$unit_time=1){
      $unit_time_array = array(
    1=>array(
        'conversion_rate','average_price'
    ),
    2=>array(
       'refund_rate','deductions_rate','conversion_rate','average_price','average_deductions'
    )     
);
      foreach($data_total as $key=>$data){
           if(in_array($key,$unit_time_array[$unit_time])){
                  $data_total[$key]= round(($data/$sum),2);
            }
      }
      return $data_total;
}
//将统计总数加入到列表数据数组中
function add_statistics_total($is_manager,$list_array,$total_array){
    if($is_manager && count($list_array)){
            foreach($list_array as $key=>$array){
            $list_array[$key+1] = $array;
        }
            $list_array[0] = $total_array;
    }
   return $list_array;
}

//月统计SQL
function sql_month_statistics($sql){
    return "SELECT * FROM (
(SELECT 
s.id,s.user_id,s.suser_id,s.user_name,s.team,s.group,s.group_members,s.category,SUM(s.have_received) AS have_received,SUM(s.receive_amount) AS receive_amount,SUM(s.refund_num) AS refund_num,
SUM(s.refund_amount) AS refund_amount,SUM(s.change_Q) AS change_Q,SUM(s.flow_num) AS flow_num,SUM(s.physical_things) AS physical_things,SUM(s.decorate) AS decorate,
SUM(s.singular) AS singular,date 
FROM p_customerstatistics_soft as s WHERE s.group=2 ".$sql." GROUP BY s.group_members)
UNION ALL
(SELECT 
s.id,s.user_id,s.suser_id,s.user_name,s.team,s.group,s.group_members,s.category,SUM(s.have_received) AS have_received,SUM(s.receive_amount) AS receive_amount,SUM(s.refund_num) AS refund_num,
SUM(s.refund_amount) AS refund_amount,SUM(s.change_Q) AS change_Q,SUM(s.flow_num) AS flow_num,SUM(s.physical_things) AS physical_things,SUM(s.decorate) AS decorate,
SUM(s.singular) AS singular,date 
FROM p_customerstatistics_soft AS s WHERE s.group=1 ".$sql." GROUP BY s.user_id)) AS u where 1=1 ";
}

//根据人数计算晋升每个等级的名额
function calculate_promotion_level_number($count){
    $promotion_level_rule =array(
      array('count'=>'0.1','level'=>"↑3"),
      array('count'=>'0.2','level'=>"↑2"),
      array('count'=>'0.3','level'=>"↑1"),
      array('count'=>'0.1','level'=>'0'),  
      array('count'=>'0.2','level'=>"↓1"),
      array('count'=>'0.1','level'=>"↓2")
    );
    $index = 0;
    $promotion_level_number = array();
    foreach($promotion_level_rule as $level_array){
        $promotion_level_number[$index] = array('count'=>round($level_array['count'] * $count),'level'=>$level_array['level']);
        $index++;
    }
    return $promotion_level_number;
}
//合并双人组。解决类似1号是A+B，2号是B+A，月统计出现2条记录的问题
function merge_double_group($models){
      $models_temp=array();
      $count= count($models);
       for($key_first = 0;$key_first<count($models);$key_first++){
            if($models[$key_first]['group']==1){
                  $models[$key_first]['type'] = 1;
                  $models_temp[$key_first]=$models[$key_first];            
                  continue;
              }
        for($key_second = 0;$key_second<(count($models));$key_second++){             
                if(($models[$key_first]['user_id']!=$models[$key_second]['user_id']) && ($models[$key_first]['user_id'] == $models[$key_second]['suser_id'])&& ($models[$key_second]['user_id'] == $models[$key_first]['suser_id']) && ($models[$key_second]['group'] == 2) && !$models[$key_first]['type'] && !$models[$key_second]['type']){
                    $models[$key_first]['type'] = 1;
                    $models[$key_second]['type'] = 1;                 
                    $models[$key_first]['have_received']+=$models[$key_second]['have_received'];
                    $models[$key_first]['receive_amount']+=$models[$key_second]['receive_amount'];
                    $models[$key_first]['refund_num']+=$models[$key_second]['refund_num'];
                    $models[$key_first]['refund_amount']+=$models[$key_second]['refund_amount'];
                    $models[$key_first]['change_Q']+=$models[$key_second]['change_Q'];
                    $models[$key_first]['flow_num']+=$models[$key_second]['flow_num'];
                    $models[$key_first]['physical_things']+=$models[$key_second]['physical_things'];
                    $models[$key_first]['decorate']+=$models[$key_second]['decorate'];
                    $models[$key_first]['singular']+=$models[$key_second]['singular'];
                    $models[$key_first]['second_sales']+=$models[$key_second]['second_sales'];
                    $models[$key_first]['ww_commission']+=$models[$key_second]['ww_commission'];
                    $models[$key_first]['second_commission']+=$models[$key_second]['second_commission'];
                    $models[$key_first]['conversion_rate']=(($models[$key_first]['conversion_rate']+$models[$key_second]['conversion_rate'])/2).'%';
                    $models[$key_first]['average_price']+=$models[$key_second]['average_price'];
                    $models[$key_first]['refund_rate']=(($models[$key_first]['refund_rate']+$models[$key_second]['refund_rate'])/2).'%';
                    $models[$key_first]['deductions_rate']=(($models[$key_first]['deductions_rate']+$models[$key_second]['deductions_rate'])/2).'%';
                    $models[$key_first]['deductions']+=$models[$key_second]['deductions'];
                    $models[$key_first]['average_deductions']+=$models[$key_second]['average_deductions'];
                    $models[$key_first]['total_commission']+=$models[$key_second]['total_commission'];
                    $models_temp[$key_first]=$models[$key_first];
               //     unset($models[$key_first]);
               //     unset($models[$key_second]);
              //      $models = array_values($models);                     
                    break;
             }
             else{
                    if(!$models[$key_first]['type']){
                         $models_temp[$key_first]=$models[$key_first];
                 }                  
             }
          }
      }
      $models = array_values($models_temp); 
      return $models;
}

//function merge_double_group($models){
//      $models_temp=array();//
//      foreach($models as $key_first=>$data_array_first){
//         
//          if($data_array_first['group']==1){
//                  $models_temp[$key_first]=$data_array_first;
//                  continue;
//              }// 
//         foreach($models as $key_second=>$data_array_second) {          
//             if(($data_array_first['user_id']!=$data_array_second['user_id']) && ($data_array_first['user_id'] == $data_array_second['suser_id']) && ($data_array_second['group'] == 2)){
//                
//                 $data_array_first['have_received']+=$data_array_second['have_received'];
//                 $data_array_first['receive_amount']+=$data_array_second['receive_amount'];
//                 $data_array_first['refund_num']+=$data_array_second['refund_num'];
//                 $data_array_first['refund_amount']+=$data_array_second['refund_amount'];
//                 $data_array_first['change_Q']+=$data_array_second['change_Q'];
//                 $data_array_first['flow_num']+=$data_array_second['flow_num'];
//                 $data_array_first['physical_things']+=$data_array_second['physical_things'];
//                 $data_array_first['decorate']+=$data_array_second['decorate'];
//                 $data_array_first['singular']+=$data_array_second['singular'];
//                 $data_array_first['second_sales']+=$data_array_second['second_sales'];
//                 $data_array_first['ww_commission']+=$data_array_second['ww_commission'];
//                 $data_array_first['second_commission']+=$data_array_second['second_commission'];
//                 $data_array_first['conversion_rate']=(($data_array_first['conversion_rate']+$data_array_second['conversion_rate'])/2).'%';
//                 $data_array_first['average_price']+=$data_array_second['average_price'];
//                 $data_array_first['refund_rate']=(($data_array_first['refund_rate']+$data_array_second['refund_rate'])/2).'%';
//                 $data_array_first['deductions_rate']=(($data_array_first['deductions_rate']+$data_array_second['deductions_rate'])/2).'%';
//                 $data_array_first['deductions']+=$data_array_second['deductions'];
//                 $data_array_first['average_deductions']+=$data_array_second['average_deductions'];
//                 $data_array_first['total_commission']+=$data_array_second['total_commission'];
//                 $models_temp[$key_first]=$data_array_first;
//                 unset($models[$key_first]);
//                 unset($models[$key_second]);
//                  $models = array_values($models); 
//                 break;
//             }
//             else{
//                 if($data_array_first['user_id']!=$data_array_second['user_id']){
//                      $models_temp[$key_first]=$data_array_first;
//                 }
//                   
//             }
//          }
//      }
//      $models = array_values($models_temp); 
//      return $models;
//}








