<?php

/**
 * 系统账户管理
 *
 * @author 科比
 * @copyright 2016 星密码
 * @version 2016/3/1
 */
use Models\Base\Model;
use Models\sem_account_channel;
use Models\sem_channel_account;
use Models\Base\SqlOperator;
use Models\Base\SqlSortType;

require '../../Data/common-datas.php';
require '../../application.php';
require '../../loader-api.php';
require_once '../../common/http.php';

$action = request_action();
execute_request(HttpRequestMethod::Get, function() use($action, $use_redis, $redis_config, $threshold) {
    $login_userid = request_login_userid();
    $is_manager = is_manager($login_userid);
    
    if (!isset($action)) $action = 1;
    //------获得系统人员信息---------------------------------
    if ($action == 1) {
    	$testdata = new sem_account_channel();
    	
        $sort = request_string('sort');
        $sortname = request_string('sortname');
        
        $searchName = request_string('searchName');
        
        
        $sql='select ac.user_id as id,u.username,u.nickname,u.truename,ac.created,group_concat(ca.account) as accounts,group_concat(ca.id) as caids
from sem_account_channel ac
inner join sem_channel_account ca on ca.id=ac.channel_account_id
inner join m_user u on ac.user_id=u.userid
where 1=1';
        
        $countsql='select count(distinct ac.user_id) as total
from sem_account_channel ac
inner join sem_channel_account ca on ca.id=ac.channel_account_id
inner join m_user u on ac.user_id=u.userid
where 1=1 ';

        if(!empty($searchName)){
            $sql.=" and (u.nickname like '%".$searchName."%')";
            $countsql.=" and (u.nickname like '%".$searchName."%')";
        }
        $sql.=' group by user_id';
       
        
        $pagesize=request_pagesize();
        $pageno=request_pageno();
        $start=($pageno-1)*$pagesize;
        
        $sql.=" limit $start,$pagesize";
        
        $db = create_pdo();
        
        $result=Model::execute($db,$sql);
        $total=Model::execute_custom_sql($db, $countsql);
        $totalnum=isset($total['results'][0]['total'])?$total['results'][0]['total']:0;
        
        
        if (!$result[0]) die_error(USER_ERROR, '获取数据失败，请重试');
        
        $models = $result['results'];
        $result['total_count']=$totalnum;

        
        echo_list_result($result, $models, array('is_manager' => $is_manager));
    }
    //------获取所有的渠道账户-----------------------------
    if ($action == 2) {
        $initdata = request_string('initdata');
        $channelaccdatas = new sem_channel_account();
        $db = create_pdo();
        $result = Model::query_list($db, $channelaccdatas, NULL, true);
        if (!$result[0]) die_error(USER_ERROR, '获取账号数据失败，请重试');
    
        $models = Model::list_to_array($result['models'], array());
        
        $channels=CommonDatas::getSemChannels();
        $redata=array();
        foreach ($channels as $k=>$v){
            $md=array('id'=>$k,'name'=>$v,'initdata'=>'');
            if(!empty($initdata)){
                $ch=new sem_channel_account();
                $ch->set_query_fields(sem_channel_account::$field_id);
                $ch->set_where_and(sem_channel_account::$field_channel, SqlOperator::Equals,$k);
                $idata=explode(",",$initdata);
                $ch->set_where_and(sem_channel_account::$field_id, SqlOperator::In,$idata);
                $res=Model::query_list($db,$ch,NULL, true);
                if($res[0]){
                    $subaccounts=Model::list_to_array($res['models']);
                    $subacids="";
                    foreach ($subaccounts as $sa){
                        $subacids.=empty($subacids)?$sa['id']:",".$sa['id'];
                    }
                    $md['initdata']=$subacids;
                }
            }
            $redata[$k]=$md;
        }
        
        
        
        if(count($models)>0 && is_array($models)){
            foreach ($models as $k=>$v){
                $chanenl=$v['channel'];
                $redata[$chanenl]['data'][]=array("id"=>$v['id'],"text"=>$v['account']);
            }
        }
    
        echo_result($redata);
    }
    //------获取当前登录用户的所有的渠道账户-----------------------------
    if ($action == 3) {
        $initdata = request_string('initdata');
        $pt = request_string('pt');//产品类型，多个类型中间用，间隔开
        $db = create_pdo();
        
        $sql="select c.*,a.channel,a.account,a.product_type from sem_account_channel c inner join sem_channel_account a on c.channel_account_id=a.id
            where c.user_id=".$login_userid;
        if(!empty($pt)){
           $pt=explode(",",$pt);
           $ext="";
           if(count($pt)>1){
               $ex="";
               foreach ($pt as $v){
                   $ex.=empty($ex)?"a.product_type='".$v."'":" or a.product_type='".$v."'";
               }
               $ext=" and ($ex)";
           }else{
               $ext=" and a.product_type='".$pt[0]."'";
           }
           $sql.=$ext;
        }
        $result=Model::execute($db,$sql);
        $result['total_count']=10000;
        $data=$result['results'];
        
        if($result['count']>0){
            foreach ($data as $k=>$v){
                if(empty($v['channel'])){
                    $data[$k]['channel_name']="";
                }else{
                    $data[$k]['channel_name']=CommonDatas::getSemChannels($v['channel']);
                }
            }
        }
        echo_list_result($result, $data);
    }
});

execute_request(HttpRequestMethod::Post, function() use($action, $use_redis, $redis_config, $threshold) {

    $postData = request_object();
 
    if ($action == 1) {
        
        $db = create_pdo();
        
        $cdata=new sem_account_channel();
        $cdata->set_where_and(sem_account_channel::$field_user_id, SqlOperator::Equals,$postData->userid);
        $total=$cdata->count($db);
        if($total >0 ){
            die_error(USER_ERROR, '该用户信息已经存在~');
        }else{
            $caccount=$postData->caccount;
            if(is_array($caccount)){
                $acs=implode(',', $caccount);
            }else{
                $acs=$caccount;
            }
           
            $accounts=explode(",",$acs);
            $accounts=array_unique($accounts);
            
            if(count($accounts)>0 && is_array($accounts)){
                foreach ($accounts as $v){
                    if(!empty($v)){
                        $accountdata = new sem_account_channel();
                        $accountdata->set_channel_account_id($v);
                        $accountdata->set_user_id($postData->userid);
                        $accountdata->set_created(date("Y-m-d H:i:s"));
                        $result=$accountdata->insert($db);
                    }
                }
            }
            //输出
            echo_msg("保存成功");
        }
    }
    
    //-------编辑-------------------------
    if ($action == 2) {
        $user_id=$postData->id;
        $caccount=$postData->caccount;
        if(is_array($caccount)){
            $acs=implode(",", $caccount);
        }else{
            $acs=$caccount;
        }
 
        $accounts=explode(",",$acs);
        $accounts=array_unique($accounts);
        
        $db = create_pdo();
        
        pdo_transaction($db, function($db) use($user_id, $accounts) {

            $sql="delete from sem_account_channel where user_id=".$user_id;
            $res=Model::execute($db,$sql);
            if (!$res[0]) throw new TransactionException(PDO_ERROR_CODE, '删除失败~', $res);
            
            if(count($accounts)>0 && is_array($accounts)){
                foreach ($accounts as $v){
                    if(!empty($v)){
                        $accountdata = new sem_account_channel();
                        $accountdata->set_channel_account_id($v);
                        $accountdata->set_user_id($user_id);
                        $accountdata->set_created(date("Y-m-d H:i:s"));
                        $result=$accountdata->insert($db);
                        if (!$result[0]) throw new TransactionException(PDO_ERROR_CODE, '新增失败~', $result);
                    }
                }
            }
        });
    	//输出
    	echo_msg("修改成功");
    }
    //删除
    if ($action == 3) {
    	$db = create_pdo();
    	$user_id=$postData->id;
        $curdata=new sem_account_channel();
        $curdata->set_where_and(sem_account_channel::$field_user_id, SqlOperator::Equals,$user_id);
        $result=$curdata->delete($db);
    	if (!$result) die_error(USER_ERROR, '删除失败~');
    	//输出
    	echo_result(array("code" => 0, "msg" => "删除成功"));
    }
});
