<?php
class HourBaiDu implements HouerInter
{

    /**
     * (non-PHPdoc)
     *
     * @see HouerInter::execute()
     */
    public function execute($pdo, $date, $houer, $parm = array(), $updateflag = false)
    {
        if (! empty($parm['token']) && ! empty($parm['pwd'])) {
            $account = array(
                'token' => $parm['token'],
                'password' => $parm['pwd'],
                'site' => $parm['site']
            );
            
            if ($parm['parent_flag'] == 1) {
                $account['username'] = $parm['account'];
            } else {
                $account['username'] = $parm['parent_account'];
                $account['target'] = $parm['account'];
            }
            $bparm = array(
                'report_type' => '1', // 报表报告类型 1:账户 2:计划 3:单元 4:关键词 5:搜索词
                'time_type' => '7', // 时间报告类型 7：小时 5：分天 4：分周 3：分月
                'start_date' => $date, // 开始时间
                'end_date' => $date
            );
            
            $msg = " [date]:" . $date . " [houer]:" . $houer;
            $msg .= " [parm]:" . json_encode($parm);
            
            $res = $this->getAccountData($pdo, $parm['channel'], $date, $houer, $account, $bparm);
            Log::write("task_baidu_houer", "[account]" . $msg);
            $res = $this->getProjectData($pdo, $parm['channel'], $date, $houer, $account, $bparm);
            Log::write("task_baidu_houer", "[project]" . $msg);
            $res = $this->getUnitData($pdo, $parm['channel'], $date, $houer, $account, $bparm);
            Log::write("task_baidu_houer", "[unit]" . $msg);
            $res = $this->getKeywordData($pdo, $parm['channel'], $date, $houer, $account, $bparm, $updateflag);
            Log::write("task_baidu_houer", "[keyword]" . $msg);
            $res = $this->getSearWordData($pdo, $parm['channel'], $date, $houer, $account, $bparm, $parm, $updateflag);
            Log::write("task_baidu_houer", "[search]" . $msg);
        }
    }

    /**
     * 获得账户的分小时数据
     *
     * @param unknown $pdo            
     * @param unknown $channel            
     * @param unknown $date            
     * @param unknown $houer            
     * @param unknown $accountparm            
     * @param unknown $bparm            
     * @return boolean
     */
    private function getAccountData($pdo, $channel, $date, $houer, $accountparm, $bparm)
    {
        $bparm['report_type'] = 1;
        $baidu = new BaiDu();
        $baidu->initAccounts($accountparm);
        $baidu->setApiParm($bparm);
        $content = $baidu->getApiContent();
        $data = @$content->data;
        
        if (count($data) > 0 && is_array($data)) {
            foreach ($data as $v) {
                
                $datetime = $date . " " . $houer . ":00";
                $semtime = $datetime . ":00";
                $kpis = $v->kpis;
                $account = $v->name[0];
                if ($v->date == $datetime) {
                    $indata = array(
                        'channel' => $channel,
                        'account' => $account,
                        'impression' => $kpis[0],
                        'click' => $kpis[1],
                        'cost' => $kpis[2],
                        'cpc' => $kpis[3],
                        'ctr' => $kpis[4],
                        'sem_time' => $semtime
                    );
                    $pdo->add("sem_houer_account", $indata);
                }
            }
        }
        
        return true;
    }

    /**
     * 获得计划的分小时数据
     *
     * @param unknown $pdo            
     * @param unknown $channel            
     * @param unknown $date            
     * @param unknown $houer            
     * @param unknown $accountparm            
     * @param unknown $bparm            
     * @return boolean
     */
    private function getProjectData($pdo, $channel, $date, $houer, $accountparm, $bparm)
    {
        $bparm['report_type'] = 2;
        $baidu = new BaiDu();
        $baidu->initAccounts($accountparm);
        $baidu->setApiParm($bparm);
        $content = $baidu->getApiContent();
        $data = @$content->data;
        
        if (count($data) > 0 && is_array($data)) {
            foreach ($data as $v) {
                
                $datetime = $date . " " . $houer . ":00";
                $semtime = $datetime . ":00";
                $kpis = $v->kpis;
                $account = $v->name[0];
                $project = $v->name[1];
                if ($v->date == $datetime) {
                    $indata = array(
                        'channel' => $channel,
                        'account' => $account,
                        'project' => $project,
                        'impression' => $kpis[0],
                        'click' => $kpis[1],
                        'cost' => $kpis[2],
                        'cpc' => $kpis[3],
                        'ctr' => $kpis[4],
                        'sem_time' => $semtime
                    );
                    $pdo->add("sem_houer_project", $indata);
                }
            }
        }
        return true;
    }

    /**
     * 获得单元的分小时数据
     *
     * @param unknown $pdo            
     * @param unknown $channel            
     * @param unknown $date            
     * @param unknown $houer            
     * @param unknown $accountparm            
     * @param unknown $bparm            
     * @return boolean
     */
    private function getUnitData($pdo, $channel, $date, $houer, $accountparm, $bparm)
    {
        $bparm['report_type'] = 3;
        $baidu = new BaiDu();
        $baidu->initAccounts($accountparm);
        $baidu->setApiParm($bparm);
        $content = $baidu->getApiContent();
        $data = @$content->data;
        
        if (count($data) > 0 && is_array($data)) {
            foreach ($data as $v) {
                
                $datetime = $date . " " . $houer . ":00";
                $semtime = $datetime . ":00";
                $kpis = $v->kpis;
                $account = $v->name[0];
                $project = $v->name[1];
                $unit = $v->name[2];
                if ($v->date == $datetime) {
                    $indata = array(
                        'channel' => $channel,
                        'account' => $account,
                        'project' => $project,
                        'unit' => $unit,
                        'impression' => $kpis[0],
                        'click' => $kpis[1],
                        'cost' => $kpis[2],
                        'cpc' => $kpis[3],
                        'ctr' => $kpis[4],
                        'sem_time' => $semtime
                    );
                    $pdo->add("sem_houer_unit", $indata);
                }
            }
        }
        return true;
    }

    /**
     * 获得关键字的分小时数据
     *
     * @param unknown $pdo            
     * @param unknown $channel            
     * @param unknown $date            
     * @param unknown $houer            
     * @param unknown $accountparm            
     * @param unknown $bparm            
     * @param unknown $updateflag            
     * @return boolean
     */
    private function getKeywordData($pdo, $channel, $date, $houer, $accountparm, $bparm, $updateflag)
    {
        $bparm['report_type'] = 4;
        $baidu = new BaiDu();
        $baidu->initAccounts($accountparm);
        $baidu->setApiParm($bparm);
        $content = $baidu->getApiContent();
        $data = @$content->data;
        
        if (count($data) > 0 && is_array($data)) {
            foreach ($data as $v) {
                
                $datetime = $date . " " . $houer . ":00";
                $semtime = $datetime . ":00";
                $kpis = $v->kpis;
                $account = $v->name[0];
                $project = $v->name[1];
                $unit = $v->name[2];
                $keyword = $v->name[3];
                if ($v->date == $datetime) {
                    $indata = array(
                        'channel' => $channel,
                        'account' => $account,
                        'project' => $project,
                        'unit' => $unit,
                        'keyword' => $keyword,
                        'impression' => $kpis[0],
                        'click' => $kpis[1],
                        'cost' => $kpis[2],
                        'cpc' => $kpis[3],
                        'ctr' => $kpis[4],
                        'position' => 0,
                        'sem_time' => $semtime
                    );
                    $pdo->add("sem_houer_keyword", $indata);
                }
            }
        }
        
        $bparm['time_type'] = 5;
        $baidu->setApiParm($bparm);
        $content = $baidu->getApiContent();
        $data = @$content->data;
        
        if (count($data) > 0 && is_array($data)) {
            foreach ($data as $v) {
                
                $semtime = $date . " 00:00:00";
                
                $kpis = $v->kpis;
                $account = $v->name[0];
                $project = $v->name[1];
                $unit = $v->name[2];
                $keyword = $v->name[3];
                
                $indata = array(
                    'channel' => $channel,
                    'account' => $account,
                    'project' => $project,
                    'unit' => $unit,
                    'keyword' => $keyword,
                    'impression' => $kpis[0],
                    'click' => $kpis[1],
                    'cost' => $kpis[2],
                    'cpc' => $kpis[3],
                    'ctr' => $kpis[4],
                    'position' => $kpis[5],
                    'sem_time' => $semtime
                );
                
                if ($updateflag) { // 更新
                    if ($date == date("Y-m-d")) {
                        $pdo->add("sem_day_keyword_today", $indata);
                    } else {
                        $pdo->add("sem_day_keyword", $indata);
                    }
                } else { // 新增
                    $pdo->add("sem_day_keyword_today", $indata);
                    if ($houer == "23") {
                        $pdo->add("sem_day_keyword", $indata);
                    }
                }
            }
        }
        return true;
    }

    /**
     * 获得搜索词的分小时数据
     *
     * @param unknown $pdo            
     * @param unknown $channel            
     * @param unknown $date            
     * @param unknown $houer            
     * @param unknown $accountparm            
     * @param unknown $bparm            
     * @param unknown $parm            
     * @param unknown $updateflag            
     * @return boolean
     */
    private function getSearWordData($pdo, $channel, $date, $houer, $accountparm, $bparm, $parm, $updateflag)
    {
        $bparm['report_type'] = 5;
        $baidu = new BaiDu();
        $baidu->initAccounts($accountparm);
        $baidu->setApiParm($bparm);
        $content = $baidu->getApiContent();
        $data = @$content->data;
        
        $tourl=isset($accountparm['site'])?$accountparm['site']:"";
        
        if (count($data) > 0 && is_array($data)) {
            $insertdata = array();
            
            foreach ($data as $v) {
                
                $datetime = $date . " " . $houer . ":00";
                $semtime = $date;
                $kpis = $v->kpis;
                
                $account = $v->queryInfo[0];
                $project = $v->queryInfo[1];
                $unit = $v->queryInfo[2];
                $keyword = $v->queryInfo[3];
                $search = $v->query;
                
                if ($parm['product_type'] == ProductTypeConts::XMM) {
                    $talbe_access_name = "p_qqaccess";
                    $talbe_salecount_name = "p_salecount";
                } else {
                    $talbe_access_name = "p_qqaccess_soft";
                    $talbe_salecount_name = "p_salecount_soft";
                }
                $channelname = BaseDatas::getChannelByKey($parm['channel']);
                $datasql = "select count(*) as num from " . $talbe_access_name . " 
                where keyword ='$search' and addtime >'$date 00:00:00' and addtime <='$date 23:59:59' 
                and channel='$channelname' and trim(REPLACE(REPLACE(into_url, CHAR(10), ''), CHAR(13), ''))='$tourl'";
                $total_qq = $pdo->getOne($datasql);
                $qq_total = isset($total_qq['num']) ? $total_qq['num'] : 0;
                
                $salesql = "select count(*) as num
                    from " . $talbe_access_name . " a
                    inner join $talbe_salecount_name s on s.qq=a.qq_num and s.addtime >'$date 00:00:00' and s.addtime <='$date 23:59:59'
                    where a.keyword ='$search' and a.addtime >'$date 00:00:00' and a.addtime <='$date 23:59:59' and a.channel='$channelname'
                    and trim(REPLACE(REPLACE(a.into_url, CHAR(10), ''), CHAR(13), ''))='$tourl'
                ";
                
                $total_sale = $pdo->getOne($salesql);
                $sale_total = isset($total_sale['num']) ? $total_sale['num'] : 0;
                
                $ak = $project . "_" . $unit . "_" . $keyword . "_" . $search;
                if (isset($insertdata[$ak])) {
                    $insertdata[$ak]['impression'] += $kpis[0];
                    $insertdata[$ak]['click'] += $kpis[1];
                } else {
                    $insertdata[$ak] = array(
                        'channel' => $channel,
                        'account' => $account,
                        'project' => $project,
                        'unit' => $unit,
                        'keyword' => $keyword,
                        'search' => $search,
                        'impression' => $kpis[0],
                        'click' => $kpis[1],
                        'qq_total' => $qq_total,
                        'sale_total' => $sale_total,
                        'sem_time' => $semtime
                    );
                }
            }
            foreach ($insertdata as $k => $v) {
                
                $qq_total = $v['qq_total'];
                $sale_total = $v['sale_total'];
                $impression = intval($v['impression']);
                $click = intval($v['click']);
                
                $qqrate = 0;
                if ($click > 0) {
                    $qqrate = $qq_total / $click;
                    $qqrate = sprintf("%0.2f", $qqrate);
                }
                
                $salerate = 0;
                if ($qq_total > 0) {
                    $salerate = $sale_total / $qq_total;
                    $salerate = sprintf("%0.2f", $salerate);
                }
                
                $v['qq_rate'] = $qqrate;
                $v['sale_rate'] = $salerate;
                
                if ($updateflag) { // 更新
                    if ($date == date("Y-m-d")) {
                        $pdo->add("sem_day_search_today", $v);
                    } else {
                        $pdo->add("sem_day_search", $v);
                    }
                } else { // 新增
                    $pdo->add("sem_day_search_today", $v);
                    if ($houer == "23") {
                        $pdo->add("sem_day_search", $v);
                    }
                }
            }
        }
        return true;
    }

    /**
     *
     * @param unknown $date            
     * @param number $days            
     * @return string
     */
    function addDays($date, $days = 1)
    {
        $now_date = date("Y-m-d", strtotime($date));
        $next_date = date("Y-m-d", strtotime($days . "day", strtotime($now_date)));
        return $next_date;
    }
}
?>