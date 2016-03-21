<?php
/**
 * 消费数据
 *
 * @author 科比
 * @copyright 2016 星密码
 * @version 2016/3/4
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
execute_request(HttpRequestMethod::Get, function () use($action, $use_redis, $redis_config, $threshold)
{
    $login_userid = request_login_userid();
    $is_manager = is_manager($login_userid);
    
    if (! isset($action))
        $action = 1;
        // ------获得消费信息---------------------------------
    if ($action == 1) {
        $sqls = getSqlData();
        
        $sql = $sqls['datasql'];
        $countsql = $sqls['countsql'];
        
        $pagesize = request_pagesize();
        $pageno = request_pageno();
        $start = ($pageno - 1) * $pagesize;
        
        $sql .= " limit $start,$pagesize";
        $db = create_pdo();
        $result = Model::execute($db, $sql);
        $total = Model::execute_custom_sql($db, $countsql);
        $totalnum = isset($total['results'][0]['total']) ? $total['results'][0]['total'] : 0;
        
        if (! $result[0])
            die_error(USER_ERROR, '获取数据失败，请重试');
        
        $models = $result['results'];
        $result['total_count'] = $totalnum;
        $reporttimetype = request_string('reporttimetype');
        if (count($models) > 0 && is_array($models)) {
            foreach ($models as $k => $v) {
                $channel = CommonDatas::getSemChannels($v['channel']);
                $models[$k]['channel_name'] = $channel;
                if (isset($v['ctr'])) {
                    $models[$k]['ctr'] = (floor($v['ctr'] * 10000) / 100) . "%";
                }
                if (isset($v['qq_rate'])) {
                    $models[$k]['qq_rate'] = (floor($v['qq_rate'] * 10000) / 100) . "%";
                }
                if (isset($v['sale_rate'])) {
                    $models[$k]['sale_rate'] = (floor($v['sale_rate'] * 10000) / 100) . "%";
                }
            }
        }
        
        echo_list_result($result, $models, array(
            'is_manager' => $is_manager
        ));
    }
    // ------导出结果集---------------------------------
    if ($action == 2) {
        $sqls = getSqlData();
        $sql = $sqls['datasql'];
        $db = create_pdo();
        $result = Model::execute($db, $sql);
        $data = array();
        if ($result[0]) {
            $data = $result['results'];
        }
        
        $resstr = "";
        $reporttimetype = request_string('reporttimetype');
        $fname = request_string('fname');
        if (! empty($fname)) {
            $ext = explode(".", $fname);
            $ext = strtolower(array_pop($ext));
            if ($ext != 'csv') {
                $fname .= ".csv";
            }
        } else {
            $filename = date("Y-m-d") . ".cvs";
        }
        
        $reportnt = request_string('reportnt');
        if($reportnt=="1"){
            switch ($reporttimetype) {
                case 1:
                    $resstr = "时间,账户,展现量,点击量,点击率,消费,点击价格\n";
                    if (count($data) > 0) {
                        foreach ($data as $k) {
                            $resstr .= '"' . $k['sem_time_show'] . '","' . filerCsvData($k['account']) . '","' . $k['impression'] . '","' . $k['click'] . '","' . $k['ctr'] . '","' . $k['cost'] . '","' . $k['cpc'] . '"' . "\n";
                        }
                    }
                    break;
                case 2:
                    $resstr = "时间,账户,推广计划,展现量,点击量,点击率,消费,点击价格\n";
                    if (count($data) > 0) {
                        foreach ($data as $k) {
                            $resstr .= '"' . $k['sem_time_show'] . '","' . filerCsvData($k['account']) . '","' . filerCsvData($k['project']) . '","' . $k['impression'] . '","' . $k['click'] . '","' . $k['ctr'] . '","' . $k['cost'] . '","' . $k['cpc'] . '"' . "\n";
                        }
                    }
                    break;
                case 3:
                    $resstr = "时间,账户,推广计划,推广单元,展现量,点击量,点击率,消费,点击价格\n";
                    if (count($data) > 0) {
                        foreach ($data as $k) {
                            $resstr .= '"' . $k['sem_time_show'] . '","' . filerCsvData($k['account']) . '","' . filerCsvData($k['project']) . '","' . filerCsvData($k['unit']) . '","' . $k['impression'] . '","' . $k['click'] . '","' . $k['ctr'] . '","' . $k['cost'] . '","' . $k['cpc'] . '"' . "\n";
                        }
                    }
                    break;
                case 4:
                    $resstr = "时间,账户,推广计划,推广单元,关键词,展现量,点击量,点击率,消费,点击价格,平均排名\n";
                    if (count($data) > 0) {
                        foreach ($data as $k) {
                            $resstr .= '"' . $k['sem_time_show'] . '","' . filerCsvData($k['account']) . '","' . filerCsvData($k['project']) . '","' . filerCsvData($k['unit']) . '","' . filerCsvData($k['keyword']) . '","' . $k['impression'] . '","' . $k['click'] . '","' . $k['ctr'] . '","' . $k['cost'] . '","' . $k['cpc'] . '","' . $k['position'] . '"' . "\n";
                        }
                    }
                    break;
                case 5:
                    $resstr = "时间,账户,推广计划,推广单元,关键词,搜索词,展现量,点击量,转Q量,点击转Q率,成交量,成交率,渠道来源\n";
                    if (count($data) > 0) {
                        foreach ($data as $k) {
            
                            $channel_name = CommonDatas::getSemChannels($k['channel']);
            
                            $resstr .= '"' . $k['sem_time_show'] . '","' . filerCsvData($k['account']) . '","' . filerCsvData($k['project']) . '","' . filerCsvData($k['unit']) . '","' . filerCsvData($k['keyword']) . '","' . filerCsvData($k['search']) . '","' . $k['impression'] . '","' . $k['click'] . '","' . $k['qq_total'] . '","' . $k['qq_rate'] . '","' . $k['sale_total'] . '","' . $k['sale_rate'] . '","' . $channel_name . '"' . "\n";
                        }
                    }
                    break;
            }
        }else{
            $resstr = "时间,展现量,点击量,点击率,消费,点击价格\n";
            if (count($data) > 0) {
                foreach ($data as $k) {
                    $resstr .= '"' . $k['sem_time_show'] . '","' . $k['impression'] . '","' . $k['click'] . '","' . $k['ctr'] . '","' . $k['cost'] . '","' . $k['cpc'] . '"' . "\n";
                }
            }
        }
        
        export_csv($fname, $resstr);
    }
});

function filerCsvData($str)
{
    $str = str_replace('"', '""', $str);
    return $str;
}

/**
 * 获得获取数据的sql
 *
 * @return Ambigous <multitype:, multitype:string >
 */
function getSqlData()
{
    $sort = request_string('sort');
    $sortname = request_string('sortname');
    
    $accounts = request_string('accounts');
    $sdate = request_string('sdate');
    $edate = request_string('edate');
    $reportnt = request_string('reportnt');
    $reporttype = request_string('reporttype');
    $reporttimetype = request_string('reporttimetype');
    
    $sqls = array();
    if ($reportnt == '1') { // 分账户
        switch ($reporttimetype) {
            case 1: // 账户
                $sqls = getAccountSqlByReType($reportnt, $reporttype, $accounts, $sdate, $edate, $sort, $sortname);
                break;
            case 2: // 计划
                $sqls = getProjectSqlByReType($reportnt, $reporttype, $accounts, $sdate, $edate, $sort, $sortname);
                break;
            case 3: // 单元
                $sqls = getUnitSqlByReType($reportnt, $reporttype, $accounts, $sdate, $edate, $sort, $sortname);
                break;
            case 4: // 关键词
                $sqls = getKeywordSqlByReType($reportnt, $reporttype, $accounts, $sdate, $edate, $sort, $sortname);
                break;
            case 5: // 搜索词
                $sqls = getSearchSqlByReType($reportnt, $reporttype, $accounts, $sdate, $edate, $sort, $sortname);
                break;
        }
    } else { // 汇总
        $sqls = getAccountHZSqlByReType($reportnt, $reporttype, $accounts, $sdate, $edate, $sort, $sortname);
    }
    return $sqls;
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
    $data=mb_convert_encoding($data, "GBK",'utf-8');
    echo $data;
}
/**
 * 获得账号的sql语句
 *
 * @param unknown $reporttype
 * @param unknown $timetype
 * @param unknown $accounts
 * @param unknown $start_date
 * @param unknown $end_date
 * @param unknown $sort
 * @param unknown $sort_name
 * @return multitype:string
 */
function getAccountHZSqlByReType($reporttype, $timetype, $accounts, $start_date, $end_date, $sort, $sort_name)
{
    $condaccs = "";
    if (! empty($accounts)) {
        $accounts = explode(",", $accounts);
        foreach ($accounts as $v) {
            $condaccs .= empty($condaccs) ? "'" . $v . "'" : ",'" . $v . "'";
        }
    }

    $res = array();

    switch ($timetype) {
        case 1:
            // 分时
            $sql = "select substring(a.sem_time,1,16) as sem_time_show,a.impression,a.click,a.cost,a.cpc,a.ctr
from sem_houer_account  a
inner join sem_channel_account ca on ca.account=a.account
            where a.sem_time >='$start_date 00:00:00' and
            a.sem_time <='$end_date 23:59:59'";
            if (! empty($condaccs)) {
                $sql .= " and ca.id in ($condaccs)";
            }
            
            $sql.=" group by a.sem_time";
            
            $countsql="select count(*) as total from ($sql) m";
            
            
            if (! empty($sort) && ! empty($sort_name)) {
                $sql .= " order by $sort_name $sort";
            } else {
                $sql .= " order by sem_time desc";
            }

            $res['datasql'] = $sql;
            $res['countsql'] = $countsql;
            break;
        case 2:
            // 分天
            $sql = "select substring(a.sem_time,1,10) as sem_time_show,sum(a.impression) as impression,sum(a.click) as click,sum(a.cost) as cost,
            if(SUM(a.impression)=0,0,FORMAT(SUM(a.click)/SUM(a.impression),4)) as ctr,
 if(SUM(a.click)=0,0,FORMAT(SUM(a.cost)/SUM(a.click),2)) as cpc
from sem_houer_account  a
inner join sem_channel_account ca on ca.account=a.account
            where a.sem_time >='$start_date 00:00:00' and
            a.sem_time <='$end_date 23:59:59'";
            if (! empty($condaccs)) {
                $sql .= " and ca.id in ($condaccs)";
            }
            
            $sql.=" group by substring(a.sem_time,1,10)";
            
            $countsql="select count(*) as total from ($sql) m";
            
            
            if (! empty($sort) && ! empty($sort_name)) {
                $sql = "select * from ($sql) m order by $sort_name $sort";
            } else {
                $sql .= " order by sem_time desc";
            }

            $res['datasql'] = $sql;
            $res['countsql'] = $countsql;
            break;
        case 3:
        case 4:
            // 分周,分月
            if($timetype==3){
                $dates = getWeekDate($start_date, $end_date);
            }else{
                $dates = getMonthDate($start_date, $end_date);
            }
           
            $sql = "";
            foreach ($dates as $v) {
                $sqlstr = "select a.id,'" . $v['start'] . "~" . $v['end'] . "' as sem_time_show,'" . $v['start'] . "' as sem_time,
                        SUM(a.impression) AS impression, SUM(a.click) AS click, SUM(a.cost) AS cost,
 if(SUM(a.impression)=0,0,FORMAT(SUM(a.click)/SUM(a.impression),4)) as ctr,
 if(SUM(a.click)=0,0,FORMAT(SUM(a.cost)/SUM(a.click),2)) as cpc
                from sem_houer_account  a
                inner join sem_channel_account ca on ca.account=a.account
                where a.sem_time >='" . $v['start'] . " 00:00:00' and
                a.sem_time <='" . $v['end'] . " 23:59:59'";
                
                if (! empty($condaccs)) {
                    $sqlstr .= " and ca.id in ($condaccs)";
                }

                if (empty($sql)) {
                    $sql = "(" . $sqlstr . ")";
                } else {
                    $sql .= " union all (" . $sqlstr . ")";
                }
            }

            $countsql = "select count(*) as total from ($sql) m where m.id >0";

            $sql="select * from ($sql) m where m.id >0";
            if (! empty($sort) && ! empty($sort_name)) {
                $sql .= " order by $sort_name $sort";
            } else {
                $sql .= " order by sem_time desc";
            }


            $res['datasql'] = $sql;
            $res['countsql'] = $countsql;
            break;
        
    }

    return $res;
}
/**
 * 获得账号的sql语句
 *
 * @param unknown $reporttype            
 * @param unknown $timetype            
 * @param unknown $accounts            
 * @param unknown $start_date            
 * @param unknown $end_date            
 * @param unknown $sort            
 * @param unknown $sort_name            
 * @return multitype:string
 */
function getAccountSqlByReType($reporttype, $timetype, $accounts, $start_date, $end_date, $sort, $sort_name)
{
    $condaccs = "";
    if (! empty($accounts)) {
        $accounts = explode(",", $accounts);
        foreach ($accounts as $v) {
            $condaccs .= empty($condaccs) ? "'" . $v . "'" : ",'" . $v . "'";
        }
    }
    
    $res = array();
    
    // if ($reporttype == 1) {
    switch ($timetype) {
        case 1:
            // 分时
            $sql = "select a.*,substring(a.sem_time,1,16) as sem_time_show
                from sem_houer_account  a
                inner join sem_channel_account ca on ca.account=a.account
                where a.sem_time >='$start_date 00:00:00' and 
                a.sem_time <='$end_date 23:59:59'";
            
            $countsql = "select count(*) as total 
                from sem_houer_account  a
                inner join sem_channel_account ca on ca.account=a.account
                where a.sem_time >='$start_date 00:00:00' and 
                a.sem_time <='$end_date 23:59:59'";
            if (! empty($condaccs)) {
                $sql .= " and ca.id in ($condaccs)";
                $countsql .= " and  ca.id in ($condaccs)";
            }
            if (! empty($sort) && ! empty($sort_name)) {
                $sql .= " order by $sort_name $sort";
            } else {
                $sql .= " order by sem_time desc";
            }
            
            $res['datasql'] = $sql;
            $res['countsql'] = $countsql;
            break;
        case 2:
            // 分天
            $sqlbody = "
FROM sem_houer_account a
INNER JOIN sem_channel_account ca ON ca.account=a.account
where a.sem_time >='$start_date 00:00:00' and
a.sem_time <='$end_date 23:59:59'";
            if (! empty($condaccs)) {
                $sqlbody .= " and ca.id in ($condaccs)";
            }
            $sqlbody .= " GROUP BY SUBSTRING(a.sem_time,1,10),a.channel,a.account";
            
            $countsql = "select SUBSTRING(a.sem_time,1,10) AS sem_time_show,a.channel,a.account,count(a.id) as total " . $sqlbody;
            $sql = "SELECT SUBSTRING(a.sem_time,1,10) AS sem_time_show,a.channel,a.account,
 SUM(a.impression) AS impression, SUM(a.click) AS click, SUM(a.cost) AS cost,
 if(SUM(a.impression)=0,0,FORMAT(SUM(a.click)/SUM(a.impression),4)) as ctr,
 if(SUM(a.click)=0,0,FORMAT(SUM(a.cost)/SUM(a.click),2)) as cpc" . $sqlbody;
            
            if (! empty($sort) && ! empty($sort_name)) {
                    $sql = " select * from ($sql) m order by $sort_name $sort";
                } else {
                    $sql .= " order by sem_time desc";
                }
            
            $res['datasql'] = $sql;
            $res['countsql'] = $countsql;
            break;
        case 3:
        case 4:
            // 分周，分月
            if($timetype==3){
                $dates = getWeekDate($start_date, $end_date);
            }else{
                $dates = getMonthDate($start_date, $end_date);
            }
            
            $sql = "";
            foreach ($dates as $v) {
                $sqlstr = "select a.channel,a.account,sum(a.impression) ,'" . $v['start'] . "~" . $v['end'] . "' as sem_time_show,'" . $v['start'] . "' as sem_time,
                        SUM(a.impression) AS impression, SUM(a.click) AS click, SUM(a.cost) AS cost,
 if(SUM(a.impression)=0,0,FORMAT(SUM(a.click)/SUM(a.impression),4)) as ctr,
 if(SUM(a.click)=0,0,FORMAT(SUM(a.cost)/SUM(a.click),2)) as cpc 
                from sem_houer_account  a
                inner join sem_channel_account ca on ca.account=a.account
                where a.sem_time >='" . $v['start'] . " 00:00:00' and 
                a.sem_time <='" . $v['end'] . " 23:59:59'";
                if (! empty($condaccs)) {
                    $sqlstr .= " and ca.id in ($condaccs)";
                }
                $sqlstr .= " group by a.channel,a.account";
                
                if (empty($sql)) {
                    $sql = "(" . $sqlstr . ")";
                } else {
                    $sql .= " union all (" . $sqlstr . ")";
                }
            }
            
            $countsql = "select count(*) as total from ($sql) m";
            
            if (! empty($sort) && ! empty($sort_name)) {
                    $sql = " select * from ($sql) m order by $sort_name $sort";
                } else {
                    $sql .= " order by sem_time desc";
                }

            
            $res['datasql'] = $sql;
            $res['countsql'] = $countsql;
            break;
    }
    // }
    
    return $res;
}

/**
 * 获得计划的sql语句
 *
 * @param unknown $reporttype            
 * @param unknown $timetype            
 * @param unknown $accounts            
 * @param unknown $start_date            
 * @param unknown $end_date            
 * @param unknown $sort            
 * @param unknown $sort_name            
 * @return multitype:string
 */
function getProjectSqlByReType($reporttype, $timetype, $accounts, $start_date, $end_date, $sort, $sort_name)
{
    $condaccs = "";
    if (! empty($accounts)) {
        $accounts = explode(",", $accounts);
        foreach ($accounts as $v) {
            $condaccs .= empty($condaccs) ? "'" . $v . "'" : ",'" . $v . "'";
        }
    }
    $res = array();
    
    if ($reporttype == 1) {
        switch ($timetype) {
            case 1:
                $sql = "select a.*,substring(a.sem_time,1,16) as sem_time_show
                from sem_houer_project  a
                inner join sem_channel_account ca on ca.account=a.account
                where a.sem_time >='$start_date 00:00:00' and 
                a.sem_time <='$end_date 23:59:59'";
                
                $countsql = "select count(*) as total
                from sem_houer_project  a
                inner join sem_channel_account ca on ca.account=a.account
                where a.sem_time >='$start_date 00:00:00' and 
                a.sem_time <='$end_date 23:59:59'";
                
                if (! empty($condaccs)) {
                    $sql .= " and ca.id in ($condaccs)";
                    $countsql .= " and  ca.id in ($condaccs)";
                }
                if (! empty($sort) && ! empty($sort_name)) {
                    $sql .= " order by $sort_name $sort";
                } else {
                    $sql .= " order by a.sem_time desc";
                }
                
                $res['datasql'] = $sql;
                $res['countsql'] = $countsql;
                // 分时
                break;
            case 2:
                // 分天
                $sql = "
                    SELECT SUBSTRING(a.sem_time,1,10) AS sem_time_show,a.channel,a.account,a.project,
 SUM(a.impression) AS impression, SUM(a.click) AS click, SUM(a.cost) AS cost,
 if(SUM(a.impression)=0,0,FORMAT(SUM(a.click)/SUM(a.impression),4)) as ctr,
 if(SUM(a.click)=0,0,FORMAT(SUM(a.cost)/SUM(a.click),2)) as cpc 
FROM sem_houer_project a
INNER JOIN sem_channel_account ca ON ca.account=a.account
where a.sem_time >='$start_date 00:00:00' and
a.sem_time <='$end_date 23:59:59'";
                if (! empty($condaccs)) {
                    $sql .= " and ca.id in ($condaccs)";
                }
                $sql .= " GROUP BY SUBSTRING(a.sem_time,1,10),a.channel,a.account,a.project";
                
                $countsql = "select count(*) as total  from ($sql) m";
                
                if (! empty($sort) && ! empty($sort_name)) {
                    $sql = " select * from ($sql) m order by $sort_name $sort";
                } else {
                    $sql .= " order by sem_time desc";
                }
                
                
               
        
                
                $res['datasql'] = $sql;
                $res['countsql'] = $countsql;
                break;
            case 3:
            case 4:
                // 分周，分月
                if($timetype==3){
                    $dates = getWeekDate($start_date, $end_date);
                }else{
                    $dates = getMonthDate($start_date, $end_date);
                }
                $sql="";
                foreach ($dates as $v) {
                    $sqlstr = "select '" . $v['start'] . "~" . $v['end'] . "' as sem_time_show,'" . $v['start'] . "' as sem_time,
                        a.channel,a.account,a.project,
                        SUM(a.impression) AS impression, SUM(a.click) AS click, SUM(a.cost) AS cost,
 if(SUM(a.impression)=0,0,FORMAT(SUM(a.click)/SUM(a.impression),4)) as ctr,
 if(SUM(a.click)=0,0,FORMAT(SUM(a.cost)/SUM(a.click),2)) as cpc
                from sem_houer_project  a
                inner join sem_channel_account ca on ca.account=a.account
                where a.sem_time >='" . $v['start'] . " 00:00:00' and
                a.sem_time <='" . $v['end'] . " 23:59:59'";
                    if (! empty($condaccs)) {
                        $sqlstr .= " and ca.id in ($condaccs)";
                    }
                    $sqlstr .= " group by a.channel,a.account,a.project";
                    
                    if (empty($sql)) {
                        $sql = "(" . $sqlstr . ")";
                    } else {
                        $sql .= " union all (" . $sqlstr . ")";
                    }
                }
                
                $countsql = "select count(*) as total from ($sql) m";
                
                if (! empty($sort) && ! empty($sort_name)) {
                    $sql = " select * from ($sql) m order by $sort_name $sort";
                } else {
                    $sql .= " order by sem_time desc";
                }
                $res['datasql'] = $sql;
                $res['countsql'] = $countsql;
                break;
        }
    }
    
    return $res;
}

/**
 * 获得单元的sql语句
 *
 * @param unknown $reporttype            
 * @param unknown $timetype            
 * @param unknown $accounts            
 * @param unknown $start_date            
 * @param unknown $end_date            
 * @param unknown $sort            
 * @param unknown $sort_name            
 * @return multitype:string
 */
function getUnitSqlByReType($reporttype, $timetype, $accounts, $start_date, $end_date, $sort, $sort_name)
{
    $condaccs = "";
    if (! empty($accounts)) {
        $accounts = explode(",", $accounts);
        foreach ($accounts as $v) {
            $condaccs .= empty($condaccs) ? "'" . $v . "'" : ",'" . $v . "'";
        }
    }
    $res = array();
    
    if ($reporttype == 1) {
        switch ($timetype) {
            case 1:
                $sql = "select a.*,substring(a.sem_time,1,16) as sem_time_show
                from sem_houer_unit  a
                inner join sem_channel_account ca on ca.account=a.account
                where a.sem_time >='$start_date 00:00:00' and 
                a.sem_time <='$end_date 23:59:59'";
                
                $countsql = "select count(*) as total
                from sem_houer_unit a
                inner join sem_channel_account ca on ca.account=a.account
                where a.sem_time >='$start_date 00:00:00' and 
                a.sem_time <='$end_date 23:59:59'";
                
                if (! empty($condaccs)) {
                    $sql .= " and ca.id in ($condaccs)";
                    $countsql .= " and  ca.id in ($condaccs)";
                }
                if (! empty($sort) && ! empty($sort_name)) {
                    $sql .= " order by $sort_name $sort";
                } else {
                    $sql .= " order by sem_time desc";
                }
                
                $res['datasql'] = $sql;
                $res['countsql'] = $countsql;
                // 分时
                break;
            case 2:
                // 分天
                $sql = "
                    SELECT SUBSTRING(a.sem_time,1,10) AS sem_time_show,a.channel,a.account,a.project,a.unit,
 SUM(a.impression) AS impression, SUM(a.click) AS click, SUM(a.cost) AS cost,
 if(SUM(a.impression)=0,0,FORMAT(SUM(a.click)/SUM(a.impression),4)) as ctr,
 if(SUM(a.click)=0,0,FORMAT(SUM(a.cost)/SUM(a.click),2)) as cpc 
                FROM sem_houer_unit a
                INNER JOIN sem_channel_account ca ON ca.account=a.account
                where a.sem_time >='$start_date 00:00:00' and
                a.sem_time <='$end_date 23:59:59'";
                if (! empty($condaccs)) {
                    $sql .= " and ca.id in ($condaccs)";
                }
                $sql .= " GROUP BY SUBSTRING(a.sem_time,1,10),a.channel,a.account,a.project,a.unit";
                
                $countsql = "select count(*) as total from ($sql) m";
                
                
                if (! empty($sort) && ! empty($sort_name)) {
                    $sql = " select * from ($sql) m order by $sort_name $sort";
                } else {
                    $sql .= " order by sem_time desc";
                }
                
                $res['datasql'] = $sql;
                $res['countsql'] = $countsql;
                break;
            case 3:
            case 4:
                // 分周，分月
                if($timetype==3){
                    $dates = getWeekDate($start_date, $end_date);
                }else{
                    $dates = getMonthDate($start_date, $end_date);
                }
               
                $sql = "";
                foreach ($dates as $v) {
                    $sqlstr = "select '" . $v['start'] . "~" . $v['end'] . "' as sem_time_show,'" . $v['start'] . "' as sem_time,
                        a.channel,a.account,a.project,a.unit,
                        SUM(a.impression) AS impression, SUM(a.click) AS click, SUM(a.cost) AS cost,
                        if(SUM(a.impression)=0,0,FORMAT(SUM(a.click)/SUM(a.impression),4)) as ctr,
                        if(SUM(a.click)=0,0,FORMAT(SUM(a.cost)/SUM(a.click),2)) as cpc
                        from sem_houer_unit  a
                        inner join sem_channel_account ca on ca.account=a.account
                        where a.sem_time >='" . $v['start'] . " 00:00:00' and
                a.sem_time <='" . $v['end'] . " 23:59:59'";
                    if (! empty($condaccs)) {
                        $sqlstr .= " and ca.id in ($condaccs)";
                    }
                    $sqlstr .= " group by a.channel,a.account,a.project,a.unit";
                    
                    if (empty($sql)) {
                        $sql = "(" . $sqlstr . ")";
                    } else {
                        $sql .= " union all (" . $sqlstr . ")";
                    }
                }
                
                $countsql = "select count(*) as total from ($sql) m";
                
                if (! empty($sort) && ! empty($sort_name)) {
                    $sql = " select * from ($sql) m order by $sort_name $sort";
                } else {
                    $sql .= " order by sem_time desc";
                }
                $res['datasql'] = $sql;
                $res['countsql'] = $countsql;
                break;
        }
    }
    
    return $res;
}

/**
 * 获得关键字的sql语句
 *
 * @param unknown $reporttype            
 * @param unknown $timetype            
 * @param unknown $accounts            
 * @param unknown $start_date            
 * @param unknown $end_date            
 * @param unknown $sort            
 * @param unknown $sort_name            
 * @return multitype:string
 */
function getKeywordSqlByReType($reporttype, $timetype, $accounts, $start_date, $end_date, $sort, $sort_name)
{
    $condaccs = "";
    if (! empty($accounts)) {
        $accounts = explode(",", $accounts);
        foreach ($accounts as $v) {
            $condaccs .= empty($condaccs) ? "'" . $v . "'" : ",'" . $v . "'";
        }
    }
    
    $res = array();
    $today = date("Y-m-d");
    if ($reporttype == 1) {
        switch ($timetype) {
            case 1:
                $sql = "select a.*,substring(a.sem_time,1,16) as sem_time_show
                from sem_houer_keyword  a
                inner join sem_channel_account ca on ca.account=a.account
                where a.sem_time >='$start_date 00:00:00' and 
                a.sem_time <='$end_date 23:59:59'";
                
                $countsql = "select count(*) as total
                from sem_houer_keyword a
                inner join sem_channel_account ca on ca.account=a.account
                where a.sem_time >='$start_date 00:00:00' and 
                a.sem_time <='$end_date 23:59:59'";
                if (! empty($condaccs)) {
                    $sql .= " and ca.id in ($condaccs)";
                    $countsql .= " and  ca.id in ($condaccs)";
                }
                if (! empty($sort) && ! empty($sort_name)) {
                    $sql .= " order by $sort_name $sort";
                } else {
                    $sql .= " order by sem_time desc";
                }
                
                $res['datasql'] = $sql;
                $res['countsql'] = $countsql;
                // 分时
                break;
            case 2:
                // 分天
                $sql_tmp = "select a.*,substring(a.sem_time,1,10) as sem_time_show
                from %s a
                inner join sem_channel_account ca on ca.account=a.account
                where a.sem_time >='$start_date 00:00:00' and
                a.sem_time <='$end_date 23:59:59'";
                if (! empty($condaccs)) {
                    $sql_tmp .= " and ca.id in ($condaccs)";
                }
                $sql1 = sprintf($sql_tmp, "sem_day_keyword");
                $sql2 = sprintf($sql_tmp, "sem_day_keyword_today");
                if ($today >= $start_date && $today <= $end_date) {
                    $sql = "($sql1)  UNION ALL ($sql2)";
                } else {
                    $sql = $sql1;
                }
                
                $countsql = "select count(*) as total from ($sql) m";
                if (! empty($sort) && ! empty($sort_name)) {
                    $sql .= " order by $sort_name $sort";
                } else {
                    $sql .= " order by sem_time desc";
                }
                
                $res['datasql'] = $sql;
                $res['countsql'] = $countsql;
                break;
            case 3:
            case 4:
                // 分周,分月
                if ($timetype == 3) { // 分周
                    $dates = getWeekDate($start_date, $end_date);
                } else { // 分月
                    $dates = getMonthDate($start_date, $end_date);
                }
                
                $sql = "";
                foreach ($dates as $v) {
                    if ($today >= $v['start'] && $today <= $v['end']) { // 包含当天的数据
                        $sql_tmp = "
                             select k.*
                             from %s k
                             inner join sem_channel_account ca on ca.account=k.account
                             where k.sem_time >='" . $v['start'] . " 00:00:00' and
                                   k.sem_time <='" . $v['end'] . " 23:59:59'";
                        if (! empty($condaccs)) {
                            $sql_tmp .= " and ca.id in ($condaccs)";
                        }
                        $sql1 = sprintf($sql_tmp, "sem_day_keyword");
                        $sql2 = sprintf($sql_tmp, "sem_day_keyword_today");
                        $basesql = "($sql1)  UNION ALL ($sql2)";
                        
                        $sqlstr = "select '" . $v['start'] . "~" . $v['end'] . "' as sem_time_show,'" . $v['start'] . "' as sem_time,
                        a.channel,a.account,a.project,a.unit,a.keyword,
                        SUM(a.impression) AS impression, SUM(a.click) AS click, SUM(a.cost) AS cost,
                        if(SUM(a.impression)=0,0,FORMAT(SUM(a.click)/SUM(a.impression),4)) as ctr,
                        if(SUM(a.click)=0,0,FORMAT(SUM(a.cost)/SUM(a.click),2)) as cpc,
                         FORMAT(AVG(position),2) as position
                        from ($basesql)  a 
                        group by a.channel,a.account,a.project,a.unit,a.keyword";
                    } else {
                        $sqlstr = "select '" . $v['start'] . "~" . $v['end'] . "' as sem_time_show,'" . $v['start'] . "' as sem_time,
                        a.channel,a.account,a.project,a.unit,a.keyword,
                        SUM(a.impression) AS impression, SUM(a.click) AS click, SUM(a.cost) AS cost,
                        if(SUM(a.impression)=0,0,FORMAT(SUM(a.click)/SUM(a.impression),4)) as ctr,
                        if(SUM(a.click)=0,0,FORMAT(SUM(a.cost)/SUM(a.click),2)) as cpc,
                        FORMAT(AVG(position),2) as position
                        from sem_day_keyword  a
                        inner join sem_channel_account ca on ca.account=a.account
                             where a.sem_time >='" . $v['start'] . " 00:00:00' and
                                   a.sem_time <='" . $v['end'] . " 23:59:59'";
                        if (! empty($condaccs)) {
                            $sqlstr .= " and ca.id in ($condaccs)";
                        }
                        $sqlstr .= " group by a.channel,a.account,a.project,a.unit,a.keyword";
                    }
                    
                    if (empty($sql)) {
                        $sql = "(" . $sqlstr . ")";
                    } else {
                        $sql .= " union all (" . $sqlstr . ")";
                    }
                }
                
                $countsql = "select count(*) as total from ($sql) m";
                
                if (! empty($sort) && ! empty($sort_name)) {
                    $sql = " select * from ($sql) m order by $sort_name $sort";
                } else {
                    $sql .= " order by sem_time desc";
                }
                
                $res['datasql'] = $sql;
                $res['countsql'] = $countsql;
                break;
        }
    }
    
    return $res;
}

/**
 * 获得搜索的sql语句
 *
 * @param unknown $reporttype            
 * @param unknown $timetype            
 * @param unknown $accounts            
 * @param unknown $start_date            
 * @param unknown $end_date            
 * @param unknown $sort            
 * @param unknown $sort_name            
 * @return multitype:string
 */
function getSearchSqlByReType($reporttype, $timetype, $accounts, $start_date, $end_date, $sort, $sort_name)
{
    $condaccs = "";
    if (! empty($accounts)) {
        $accounts = explode(",", $accounts);
        foreach ($accounts as $v) {
            $condaccs .= empty($condaccs) ? "'" . $v . "'" : ",'" . $v . "'";
        }
    }
    $res = array();
    $today = date("Y-m-d");
    if ($reporttype == 1) {
        switch ($timetype) {
            case 1:
            case 2:
                // 分天
                $sql_tmp = "select a.*,substring(a.sem_time,1,10) as sem_time_show
                from %s a
                inner join sem_channel_account ca on ca.account=a.account
                where a.sem_time >='$start_date 00:00:00' and 
                a.sem_time <='$end_date 23:59:59'";
                if (! empty($condaccs)) {
                    $sql_tmp .= " and ca.id in ($condaccs)";
                }
                $sql1 = sprintf($sql_tmp, "sem_day_search");
                $sql2 = sprintf($sql_tmp, "sem_day_search_today");
                if ($today >= $start_date && $today <= $end_date) {
                    $sql = "($sql1)  UNION ALL ($sql2)";
                } else {
                    $sql = $sql1;
                }
                
                $countsql = "select count(*) as total from ($sql) m";
                if (! empty($sort) && ! empty($sort_name)) {
                    $sql .= " order by $sort_name $sort";
                } else {
                    $sql .= " order by sem_time desc";
                }
                $res['datasql'] = $sql;
                $res['countsql'] = $countsql;
                break;
            case 3:
            case 4:
                // 分周,分月
                if ($timetype == 3) { // 分周
                    $dates = getWeekDate($start_date, $end_date);
                } else { // 分月
                    $dates = getMonthDate($start_date, $end_date);
                }
                
                $sql = "";
                foreach ($dates as $v) {
                    if ($today >= $v['start'] && $today <= $v['end']) { // 包含当天的数据
                        $sql_tmp = "
                             select k.*
                             from %s k
                             inner join sem_channel_account ca on ca.account=k.account
                             where k.sem_time >='" . $v['start'] . " 00:00:00' and
                                   k.sem_time <='" . $v['end'] . " 23:59:59'";
                        if (! empty($condaccs)) {
                            $sql_tmp .= " and ca.id in ($condaccs)";
                        }
                        $sql1 = sprintf($sql_tmp, "sem_day_search");
                        $sql2 = sprintf($sql_tmp, "sem_day_search_today");
                        $basesql = "($sql1)  UNION ALL ($sql2)";
                        
                        $subsql="select '" . $v['start'] . "~" . $v['end'] . "' as sem_time_show,'" . $v['start'] . "' as sem_time,
                        a.channel,a.account,a.project,a.unit,a.keyword,a.search,
                        sum(a.impression) as impression,
                        sum(a.click) as click,
                        sum(a.qq_total) as qq_total,
                        sum(a.sale_total) as sale_total,
                        if(SUM(a.click)=0,0,FORMAT(SUM(a.qq_total)/SUM(a.click),2)) as qq_rate,
                        if(SUM(a.qq_total)=0,0,FORMAT(SUM(a.sale_total)/SUM(a.qq_total),2)) as sale_rate
                        from ($basesql) a
                        group by a.channel,a.account,a.project,a.unit,a.keyword,a.search";
                        
                        
                    } else {
                        $subsql="select '" . $v['start'] . "~" . $v['end'] . "' as sem_time_show,'" . $v['start'] . "' as sem_time,
                        a.channel,a.account,a.project,a.unit,a.keyword,a.search,
                        sum(a.impression) as impression,
                        sum(a.click) as click,
                        sum(a.qq_total) as qq_total,
                        sum(a.sale_total) as sale_total,
                        if(SUM(a.click)=0,0,FORMAT(SUM(a.qq_total)/SUM(a.click),2)) as qq_rate,
                        if(SUM(a.qq_total)=0,0,FORMAT(SUM(a.sale_total)/SUM(a.qq_total),2)) as sale_rate
                        from sem_day_search a
                         inner join sem_channel_account ca on ca.account=a.account
                             where a.sem_time >='" . $v['start'] . " 00:00:00' and
                                   a.sem_time <='" . $v['end'] . " 23:59:59'";
                        if (! empty($condaccs)) {
                            $subsql .= " and ca.id in ($condaccs)";
                        }
                        $subsql.="group by a.channel,a.account,a.project,a.unit,a.keyword,a.search";
                        
                    }
                    
                    if (empty($sql)) {
                        $sql = "(" . $subsql . ")";
                    } else {
                        $sql .= " union all (" . $subsql . ")";
                    }
                   
                }

                $countsql = "select count(*) as total from ($sql) m";
                
        if (! empty($sort) && ! empty($sort_name)) {
                    $sql = " select * from ($sql) m order by $sort_name $sort";
                } else {
                    $sql .= " order by sem_time desc";
                }
                $res['datasql'] = $sql;
                $res['countsql'] = $countsql;
                break;
        }
    }
    
    return $res;
}

/**
 * 获得分周数据
 *
 * @param unknown $startdate            
 * @param unknown $enddate            
 * @return multitype:multitype:unknown
 */
function getWeekDate($startdate, $enddate)
{
    $dates = array();
    while ($startdate <= $enddate) {
        $weekend = getWeekend($startdate);
        if ($weekend >= $enddate) {
            $dates[] = array(
                'start' => $startdate,
                'end' => $enddate
            );
            break;
        } else {
            $dates[] = array(
                'start' => $startdate,
                'end' => $weekend
            );
            $startdate = addOneDay($weekend);
        }
    }
    return $dates;
}

/**
 * 获得分月数据
 *
 * @param unknown $startdate            
 * @param unknown $enddate            
 * @return multitype:multitype:unknown
 */
function getMonthDate($startdate, $enddate)
{
    $dates = array();
    while ($startdate <= $enddate) {
        $monthend = getEndOfMonth($startdate);
        if ($monthend >= $enddate) {
            $dates[] = array(
                'start' => $startdate,
                'end' => $enddate
            );
            break;
        } else {
            $dates[] = array(
                'start' => $startdate,
                'end' => $monthend
            );
            $startdate = addOneDay($monthend);
        }
    }
    return $dates;
}

// the first week and last week
function getWeekend($date)
{
    $sunday = date("Y-m-d", strtotime("Sunday", strtotime($date)));
    return $sunday;
}

function getEndOfMonth($date)
{
    $m_first = date("Y-m-01", strtotime($date));
    return date("Y-m-d", strtotime("+1 month -1day", strtotime($m_first)));
}

/**
 * add one day
 *
 * @param unknown_type $date            
 * @return unknown
 */
function addOneDay($date)
{
    $now_date = date("Y-m-d", strtotime($date));
    $next_date = date("Y-m-d", strtotime("+1day", strtotime($now_date)));
    return $next_date;
}

