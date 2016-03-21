<?php

/**
 * 渠道账户管理
 *
 * @author 科比
 * @copyright 2016 星密码
 * @version 2016/3/1
 */
use Models\Base\Model;
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
    if ($action == 1) {
    	$testdata = new sem_channel_account();
    	
        $sort = request_string('sort');
        $sortname = request_string('sortname');
        
        $searchName = request_string('searchName');
        $searchChannel = request_string('searchChannel');
        $searchProductType = request_string('searchProductType');
        
        if (isset($searchName)) {
            $testdata->set_where_and(sem_channel_account::$field_account, SqlOperator::Like,"%".$searchName."%");
        }
        
        if (isset($searchChannel)) {
            $testdata->set_where_and(sem_channel_account::$field_channel, SqlOperator::Equals,$searchChannel);
        }
        
        if (isset($searchProductType)) {
            $testdata->set_where_and(sem_channel_account::$field_product_type, SqlOperator::Equals,$searchProductType);
        }
        
        if (isset($sort) && isset($sortname)) {
            $testdata->set_order_by($testdata->get_field_by_name($sortname), $sort);
        } else {
            $testdata->set_order_by(sem_channel_account::$field_id, SqlSortType::Desc);
        }

        $testdata->set_limit_paged(request_pageno(), request_pagesize());
        $db = create_pdo();
        

        $result = Model::query_list($db, $testdata, NULL, true);
        
        
        if (!$result[0]) die_error(USER_ERROR, '获取账户数据失败，请重试');
        
        $models = Model::list_to_array($result['models'], array());
        if(count($models)>0 && is_array($models)){
            foreach ($models as $k=>$v){
                $models[$k]['product_type_name']=CommonDatas::getProductType($v['product_type']);
                $models[$k]['channel_name']=CommonDatas::getSemChannels($v['channel']);
            }
        }

        echo_list_result($result, $models, array('currentDate' => date('Y-m-d'), 'is_manager' => $is_manager));
    }
    if ($action == 2) {
        $fid = intval(request_string('fid'));
        
        $db = create_pdo();
        $mdata = new sem_channel_account();
        $mdata->set_where_and(sem_channel_account::$field_parent_flag, SqlOperator::Equals,1);
        $result = Model::query_list($db, $mdata, NULL, true);
        
        $models = Model::list_to_array($result['models'], array());
        $datas=array();
        if(count($models)>0 && is_array($models)){
            foreach ($models as $k=>$v){
                if($fid==$v['id']){
                    $datas[]=array('id'=>$v['id'],'value'=>$v['id'],'text'=>$v['account'],'default'=>'true');
                }else{
                    $datas[]=array('id'=>$v['id'],'value'=>$v['id'],'text'=>$v['account']);
                }
               
            }
        }
        
        echo_list_result($result,$datas);
    }
});

execute_request(HttpRequestMethod::Post, function() use($action, $use_redis, $redis_config, $threshold) {

    $postData = request_object();
 
    if ($action == 1) {
        $db = create_pdo();
        $testdata = new sem_channel_account();
        
        $testdata->set_account($postData->account);
        $testdata->set_channel($postData->channel);
        $testdata->set_product_type($postData->product_type);
        $testdata->set_parent_flag($postData->account_type);
        $testdata->set_site(trim($postData->site));
        
        if($postData->account_type=="1"){
            $testdata->set_token($postData->token);
            $testdata->set_pwd($postData->pwd);
            $testdata->set_parent_account("");
            $testdata->set_parent_id(0);
        }else{
            $parent_id=$postData->parent_id;
            
            $pardata=new sem_channel_account();
            $pardata->set_id($parent_id);
            $res=Model::get_by_primary_key($db, $pardata);
            if($res[0]){
                $testdata->set_parent_account($pardata->get_account());
                $testdata->set_token($pardata->get_token());
                $testdata->set_pwd($pardata->get_pwd());
                $testdata->set_parent_id($postData->parent_id);
            }else{
               die_error(USER_ERROR, '添加账户失败~');
            }
        }
        
        $testdata->set_created(date("Y-m-d H:i:s"));
        $result=$testdata->insert($db);
        if (!$result) die_error(USER_ERROR, '添加账户失败~');
        //输出
        echo_msg("保存成功");
    }
    if ($action == 2) {
    	$db = create_pdo();
    
    	$testdata = new sem_channel_account();
    	$testdata->set_id($postData->id);
    	$res=Model::get_by_primary_key($db, $testdata);
    	
    	$testdata->set_account($postData->account);
        $testdata->set_channel($postData->channel);
        $testdata->set_product_type($postData->product_type);
        $testdata->set_site(trim($postData->site));
        
        
        if($testdata->get_parent_flag()=="1"){
            $testdata->set_token($postData->token);
            $testdata->set_pwd($postData->pwd);
            $testdata->set_parent_account("");
            $testdata->set_parent_id(0);
            
            $sql="update sem_channel_account set token='".$postData->token."',pwd='".$postData->pwd."',parent_account='".$postData->account."' where parent_id=".$postData->id;
            Model::execute_custom_sql($db, $sql);
        }else{
            $parent_id=$postData->parent_id;
        
            $pardata=new sem_channel_account();
            $pardata->set_id($parent_id);
            $res=Model::get_by_primary_key($db, $pardata);
            if($res[0]){
                $testdata->set_parent_account($pardata->get_account());
                $testdata->set_token($pardata->get_token());
                $testdata->set_pwd($pardata->get_pwd());
                $testdata->set_parent_id($postData->parent_id);
            }else{
                die_error(USER_ERROR, '修改账户失败~');
            }
        }
        
        
    	$result=$testdata->update($db,true);
    	if (!$result) die_error(USER_ERROR, '修改账户失败~');
    	//输出
    	echo_msg("修改成功");
    }
    //删除
    if ($action == 3) {
    	$db = create_pdo();
    	$testdata = new sem_channel_account();
    	$testdata->set_where_and(sem_channel_account::$field_parent_id, SqlOperator::Equals,$postData->id);
    	$count=Model::model_count($db, $testdata);
    	if($count==0){
    	    $testdata = new sem_channel_account();
    	    $testdata->set_id($postData->id);
    	    $result=$testdata->delete($db,true);
    	    if (!$result) die_error(USER_ERROR, '删除失败~');
    	    //输出
    	    echo_result(array("code" => 0, "msg" => "删除成功"));
    	}else{
    	    die_error(USER_ERROR, '删除失败~,该账号还有子账号，不能删除');
    	}
    }
});
