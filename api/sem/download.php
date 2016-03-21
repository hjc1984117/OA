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
use Models\sem_download;
use Models\sem_sale;
use Models\sem_sale_roi;

require '../../Data/common-datas.php';
require '../../Common/ExportData2Excel.php';
require '../../application.php';
require '../../loader-api.php';
require_once '../../common/http.php';

$action = request_action();
execute_request(HttpRequestMethod::Get, function () use($action, $use_redis, $redis_config, $threshold)
{
    $login_userid = request_login_userid();
    $is_manager = is_manager($login_userid);
    
    if (! isset($action))
        $action = 1;
        // ------获得下载列表---------------------------------
    if ($action == 1) {
        $mdata = new sem_download();
        
        $sort = request_string('sort');
        $sortname = request_string('sortname');
        
        if (isset($sort) && isset($sortname)) {
            $mdata->set_order_by($mdata->get_field_by_name($sortname), $sort);
        } else {
            $mdata->set_order_by(sem_download::$field_created, SqlSortType::Desc);
        }
        
        $mdata->set_limit_paged(request_pageno(), request_pagesize());
        $db = create_pdo();
        
        $result = Model::query_list($db, $mdata, NULL, true);
        
        if (! $result[0])
            die_error(USER_ERROR, '获取数据失败，请重试');
        
        $models = Model::list_to_array($result['models'], array());
        if (count($models) > 0 && is_array($models)) {
            foreach ($models as $k => $v) {
                $models[$k]['status_name'] = $v['status'] == 1 ? "下载成功" : "下载失败";
            }
        }
        
        echo_list_result($result, $models, array(
            'is_manager' => $is_manager
        ));
    }
    // -------重新下载-------------------------
    if ($action == 2) {
        $parm['rt'] = request_string('rt');
        $parm['rtt'] = request_string('rtt');
        $parm['ac'] = request_string('ac');
        $parm['st'] = request_string('st');
        $parm['sdate'] = request_string('sdate');
        $parm['ndate'] = request_string('ndate');
        $parm['fname'] = request_string('fname');
        
        exportReportData($parm);
    }
    // 重新下载
    if ($action == 3) {
        
        $id = intval(request_string('id'));
        $mdata = new sem_download();
        $mdata->set_where_and(sem_download::$field_id, SqlOperator::Equals, $id);
        $db = create_pdo();
        $downloaddata = Model::query_list($db, $mdata);
        $edata = array();
        if ($downloaddata[0]) {
            $edata = Model::list_to_array($downloaddata['models'], array());
            $edata = $edata[0];
            $exportparm = json_decode($edata['dowload_parm']);
            
            $parm['rt'] = $exportparm->rt;
            $parm['rtt'] = @$exportparm->rtt;
            $parm['ac'] = $exportparm->ac;
            $parm['st'] = $exportparm->st;
            $parm['sdate'] = $exportparm->sdate;
            $parm['ndate'] = $exportparm->ndate;
            $parm['fname'] = $exportparm->fname;
            exportReportData($parm);
        } else {
            js_alert('下载失败，请重试');
        }
    }
});

execute_request(HttpRequestMethod::Post, function () use($action, $use_redis, $redis_config, $threshold)
{
    
    $postData = request_object();
    
    // 删除历史记录
    if ($action == 1) {
        $ids = $postData->id;
        $db = create_pdo();
        if (! empty($ids)) {
            $ids = explode(",", $ids);
            foreach ($ids as $v) {
                $mdata = new sem_download();
                $mdata->set_id($v);
                $mdata->delete($db);
            }
        }
        echo_result(array(
            "code" => 0,
            "msg" => "删除成功"
        ));
    }
});

/**
 * 导出数据
 *
 * @param unknown $parm            
 */
function exportReportData($parm)
{
    $title = $parm['rt'] == 1 ? "ROI日报表" : "交易数据报表";
    $fname = empty($parm['fname']) ? $title : $parm['fname'];
    if (! empty($fname)) {
        $ext = explode(".", $fname);
        $ext = strtolower(array_pop($ext));
        if ($ext != 'csv') {
            $fname .= ".csv";
        }
    }
    
    $db = create_pdo();
    
    $dhdata = new sem_download();
    $dhdata->set_user_id(request_login_userid());
    $dhdata->set_name($fname);
    $dhdata->set_dowload_parm(json_encode($parm));
    $dhdata->set_created(date("Y-m-d H:i:s"));
    $res = Model::insert_model($db, $dhdata);
    
    $accounts = array();
    if (! empty($parm['ac'])) {
        $accounts = explode(",", $parm['ac']);
    }
    
    $pts = array();
    if (! empty($parm['st'])) {
        $pts = explode(",", $parm['st']);
    }
    
    if ($parm['rt'] == 2) { // 交易数据报表
        
        $str = "时间,QQ,搜索词,及时,销售金额,流量业绩,实物业绩,装修业绩,补欠款金额,二销金额,渠道来源,产品类型\n";
        
        $rtt = $parm['rtt'];
        if ($rtt == '2') { // 汇总
            $sql = "select s.qq,s.channel,s.product_type,s.keywords,
sum(s.sale_money) as sale_money,
sum(s.second_money) as second_money,
sum(s.platform_money) as platform_money,
sum(s.physica_money) as physica_money,
sum(s.decoration_money) as decoration_money,
sum(s.fills_second_money) as fills_second_money,
sum(s.sale_count_money) as sale_count_money,
sum(s.fills_money) as fills_money,
sum(s.cashback_money) as cashback_money,
sum(s.refund_money) as refund_money,
sum(s.upgrade_money) as upgrade_money,
IF(s.product_type='xmm', 
  IF((select count(p.id) 
                from p_salecount p 
                inner join p_qqaccess qq on trim(REPLACE(REPLACE(qq.qq_num, CHAR(10), ''), CHAR(13), ''))=p.qq
                where p.addtime >= '" . $parm['sdate'] . " 00:00:00' AND p.addtime <= '" . $parm['ndate'] . " 23:00:00' 
                    and qq.addtime >= '" . $parm['sdate'] . " 00:00:00' AND qq.addtime <= '" . $parm['ndate'] . " 23:00:00' 
                    and p.qq=s.qq
                    ) > 0,1,0), 
  IF((select count(ps.id) 
                        from p_salecount_soft ps 
                        inner join p_qqaccess_soft qqs on trim(REPLACE(REPLACE(qqs.qq_num, CHAR(10), ''), CHAR(13), ''))=ps.qq
                        where ps.addtime >= '" . $parm['sdate'] . " 00:00:00' AND ps.addtime <= '" . $parm['ndate'] . " 23:00:00'
                            and  qqs.addtime >= '" . $parm['sdate'] . " 00:00:00' AND qqs.addtime <= '" . $parm['ndate'] . " 23:00:00'  
                            and ps.qq=s.qq) > 0,1,0)
  ) AS today_flag
from sem_sale s 
where s.sale_date >='" . $parm['sdate'] . "' and s.sale_date <='" . $parm['ndate'] . "'";
            if (count($pts) > 0) {
                $extstr = "";
                foreach ($pts as $v) {
                    $extstr .= empty($extstr) ? "product_type='$v'" : " or product_type='$v'";
                }
                $sql .= " and ($extstr)";
            }
            
            $sql .= " group by s.qq";
        } else { // 分天
            $sql = "select * from sem_sale where sale_date >='" . $parm['sdate'] . "' and sale_date <='" . $parm['ndate'] . "' ";
            if (count($pts) > 0) {
                $extstr = "";
                foreach ($pts as $v) {
                    $extstr .= empty($extstr) ? "product_type='$v'" : " or product_type='$v'";
                }
                $sql .= " and ($extstr)";
            }
        }
        
        $result = Model::execute_custom_sql($db, $sql);
        if ($result[0]) {
            $data = $result['results'];
            foreach ($data as $v) {
                $channel = empty($v['channel']) ? "" : CommonDatas::getSemChannels($v['channel']);
                $pt = CommonDatas::getProductType($v['product_type']);
                $today_flag = $v['today_flag'] == '1' ? "是" : "否";
                if ($rtt == '2') { // 汇总
                    $sdate = $parm['sdate'] . "~" . $parm['ndate'];
                } else { // 分天
                    $sdate = $v['sale_date'];
                }
                $str .= '"' . $sdate . '","' . $v['qq'] . '","' . filerCsvData($v['keywords']) . '","' . $today_flag . '","' . $v['sale_money'] . '","' . $v['platform_money'] . '","' . $v['physica_money'] . '","' . $v['decoration_money'] . '","' . $v['fills_second_money'] . '","' . $v['second_money'] . '","' . $channel . '","' . $pt . '"' . "\n";
            }
        }
    } else { // ROI日报表
        $str = "时间,关键词,推广单元,推广计划,账户,消费,搜索词,转Q量,转Q成本,及时成交量,成交量,及时成交率,成交金额,成交成本,利润,前端ROI,二销金额,总ROI,渠道来源,产品类型\n";
        
        $rtt = $parm['rtt'];
        if ($rtt == '2') { // 汇总
            
            $ksql = "select k.channel,k.account,k.project,k.unit,k.keyword,sum(k.impression) as impression,sum(k.click) as click,sum(k.cost) as cost,a.product_type,a.site
                    from sem_day_keyword k
                    inner join sem_channel_account a on a.account=k.account
                    where k.sem_time >='" . $parm['sdate'] . " 00:00:00' and k.sem_time <='" . $parm['ndate'] . " 23:59:59'";
            if (count($pts) > 0) {
                $extstr = "";
                foreach ($pts as $v) {
                    $extstr .= empty($extstr) ? " a.product_type='$v'" : " or a.product_type='$v'";
                }
                $ksql .= " and ($extstr)";
            }
            if (count($accounts) > 0) {
                $extstr = "";
                foreach ($accounts as $v) {
                    $extstr .= empty($extstr) ? " k.account='$v'" : " or k.account='$v'";
                }
                $ksql .= " and ($extstr)";
            }
            $ksql.=" group by k.account,k.project,k.unit,k.keyword";
            $result = Model::execute_custom_sql($db, $ksql);
            
            $roi_date=$parm['sdate']."~".$parm['ndate'];
            
            if($result[0]){
                $kdata=$result['results'];
                foreach ($kdata as $k=>$v){
                    
                    if($v['product_type']=="soft"){
                        $qq_table="p_qqaccess_soft";
                        $sale_talbe="p_salecount_soft";
                    }else{
                        $qq_table="p_qqaccess";
                        $sale_talbe="p_salecount";
                    }
                    
                    $channelname=CommonDatas::getSemChannels($v['channel']);
                    $pt = CommonDatas::getProductType($v['product_type']);
                    
                    
                    $sale_sql = "select s.qq,s.channel,s.product_type,s.keywords,
sum(s.sale_money) as sale_money,
sum(s.second_money) as second_money,
sum(s.platform_money) as platform_money,
sum(s.physica_money) as physica_money,
sum(s.decoration_money) as decoration_money,
sum(s.fills_second_money) as fills_second_money,
sum(s.sale_count_money) as sale_count_money,
sum(s.fills_money) as fills_money,
sum(s.cashback_money) as cashback_money,
sum(s.refund_money) as refund_money,
sum(s.upgrade_money) as upgrade_money,
IF((select count(p.id) 
                from $sale_talbe p 
                inner join $qq_table qq on trim(REPLACE(REPLACE(qq.qq_num, CHAR(10), ''), CHAR(13), ''))=p.qq
                where p.addtime >= '" . $parm['sdate'] . " 00:00:00' AND p.addtime <= '" . $parm['ndate'] . " 23:00:00' 
                    and qq.addtime >= '" . $parm['sdate'] . " 00:00:00' AND qq.addtime <= '" . $parm['ndate'] . " 23:00:00' 
                    and p.qq=s.qq
                        ) > 0,1,0) AS today_flag
from sem_sale s
where s.sale_date >='" . $parm['sdate'] . "' and s.sale_date <='" . $parm['ndate'] . "' and 
    s.channel='".$v['channel']."' and s.product_type='".$v['product_type']."'
group by s.qq";
                    
                    
                    
                    $sql="select s.channel,s.account,s.project,s.unit,s.keyword,group_concat(distinct s.search) as search,count(distinct q.qq_num) as qq_num,
count(distinct ss.qq) as sale_qq_num,sum(if(ss.today_flag=1,1,0)) as today_qq_num,
sum(ss.sale_money) as sale_money,sum(ss.second_money) as send_money
from sem_day_search s
inner join sem_channel_account a on a.account=s.account
inner join ".$qq_table." q on q.keyword=s.search and q.channel='$channelname'  
 and trim(REPLACE(REPLACE(q.into_url, CHAR(10), ''), CHAR(13), ''))='".$v['site']."'
and q.addtime >= '" . $parm['sdate'] . " 00:00:00' and q.addtime <= '" . $parm['ndate'] . " 23:59:59'
left join ($sale_sql) ss on ss.keywords=s.search and 
    trim(REPLACE(REPLACE(ss.qq, CHAR(10), ''), CHAR(13), ''))=trim(REPLACE(REPLACE(q.qq_num, CHAR(10), ''), CHAR(13), '')) and 
    ss.today_flag=1
where s.channel='".$v['channel']."' and s.account='".$v['account']."' and s.project='".$v['project']."' and s.unit='".$v['unit']."' and 
    s.keyword='".$v['keyword']."' and  s.sem_time >= '" . $parm['sdate'] . " 00:00:00' and s.sem_time <= '" . $parm['ndate'] . " 23:59:59'
group by s.channel,s.account,s.project,s.unit,s.keyword";
                    
                   
                    
                    
                    $result = Model::execute_custom_sql($db, $sql);
                    $subdata=$result[0]?$result['results'][0]:array();
                    $qq_num=isset($subdata['qq_num'])?intval($subdata['qq_num']):0;
                    $today_qq_num=isset($subdata['today_qq_num'])?intval($subdata['today_qq_num']):0;
                    $sale_qq_num=isset($subdata['sale_qq_num'])?intval($subdata['sale_qq_num']):0;
                    $sale_money=isset($subdata['sale_money'])?intval($subdata['sale_money']):0;
                    $send_money=isset($subdata['send_money'])?intval($subdata['send_money']):0;
                    
                    
                    $cost=isset($v['cost'])?$v['cost']:0;
                    if($qq_num>0){
                        $qq_cost=formatFloat($cost/$qq_num);
                    }else{
                        $qq_cost=0;
                    }
                    $sale_qq_ratio=$sale_qq_num>0?formatFloat($today_qq_num*100/$sale_qq_num):0;
                    
                    $sale_cost=$sale_qq_num>0?formatFloat($cost/$sale_qq_num):0;
                    $sale_profit=$sale_money-$cost;
                    $front_roi=$cost >0 ?formatFloat($sale_profit/$cost):0;
                    $total_roi=$cost >0 ?formatFloat(($sale_profit+$send_money)/$cost):0;
                    
                    
                    $str .= '"' . $roi_date. '","' . filerCsvData($v['keyword']) . '","' . filerCsvData($v['unit']) . '","' . filerCsvData($v['project']) . '","' . filerCsvData($v['account']) . '","' .$v['cost'] . '","' . filerCsvData(@$subdata['search']) . '","' . $qq_num . '","' . $qq_cost . '","' .$today_qq_num. '","' . $sale_qq_num . '","' . $sale_qq_ratio . '%","' . $sale_money . '","' . $sale_cost . '","' . $sale_profit . '","' . $front_roi. '","' . $send_money . '","' . $total_roi . '","' . $channelname . '","' . $pt . '"' . "\n";
                }
            }
            
            
        } else {
            $datamodel = new sem_sale_roi();
            if (count($pts) > 0) {
                $datamodel->set_where_and(sem_sale_roi::$field_product_type, SqlOperator::In, $pts);
            }
            if (count($accounts) > 0) {
                $datamodel->set_where_and(sem_sale_roi::$field_sem_account, SqlOperator::In, $accounts);
            }
            
            $datamodel->set_where_and(sem_sale_roi::$field_roi_date, SqlOperator::GreaterEquals, $parm['sdate']);
            $datamodel->set_where_and(sem_sale_roi::$field_roi_date, SqlOperator::LessEquals, $parm['ndate']);
            $result = Model::query_list($db, $datamodel, NULL, true);
            if ($result[0]) {
                $data = Model::list_to_array($result['models'], array());
                foreach ($data as $v) {
                    $channel = empty($v['channel']) ? "" : CommonDatas::getSemChannels($v['channel']);
                    $pt = CommonDatas::getProductType($v['product_type']);
                    $str .= '"' . $v['roi_date'] . '","' . filerCsvData($v['keywords']) . '","' . filerCsvData($v['sem_dy']) . '","' . filerCsvData($v['sem_plan']) . '","' . filerCsvData($v['sem_account']) . '","' . $v['sem_money'] . '","' . filerCsvData($v['search_keywords']) . '","' . $v['qq_num'] . '","' . $v['qq_cost'] . '","' . $v['today_qq_num'] . '","' . $v['sale_qq_num'] . '","' . $v['sale_qq_ratio'] . '%","' . $v['sale_money'] . '","' . $v['sale_cost'] . '","' . $v['sale_profit'] . '","' . $v['front_roi'] . '","' . $v['send_money'] . '","' . $v['total_roi'] . '","' . $channel . '","' . $pt . '"' . "\n";
                }
            }
        }
    }
    export_csv($fname, $str);
    
    $dhdata->set_status(1);
    $res = Model::update_model($db, $dhdata); // 下载成功
}

/**
 * 导出csv文件
 *
 * @param unknown $filename            
 * @param unknown $data            
 */
function export_csv($filename, $data)
{
    header("Content-type:text/csv");
    header("Content-Disposition:attachment;filename=" . $filename);
    header('Cache-Control:must-revalidate,post-check=0,pre-check=0');
    header('Expires:0');
    header('Pragma:public');
    $data = mb_convert_encoding($data, "GBK", 'utf-8');
    echo $data;
}

/**
 * 过滤导出csv的数据
 *
 * @param unknown $str            
 * @return mixed
 */
function filerCsvData($str)
{
    $str = str_replace('"', '""', $str);
    return $str;
}

/**
 * 格式化数据
 *
 * @param unknown $data
 * @return string
 */
function formatFloat($data){
    $data=sprintf("%.2f",$data);
    return $data;
}
