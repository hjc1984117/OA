<?php
include_once $docroot . '/lib/baidu/sms_service_ReportService.php';

/**
 * 渠道统计代码
 *
 * @author 科比
 *        
 */
class BaiDu extends sms_service_ReportService
{

    private $_account = array(
        'token' => '',
        'username' => '',
        'password' => '',
        'target' => ''
    );

    private $_parm = array(
        'report_type' => '1', // 报表报告类型 1:账户 2:计划 3:单元 4:关键词 5:搜索词
        'time_type' => '5', // 时间报告类型 7：小时 5：分天 4：分周 3：分月
        'start_date' => '', // 开始时间
        'end_date' => ''// 结束时间
    );

    public function initAccounts($parm = array())
    {
        $this->_account = $parm;
    }

    public function setApiParm($parm = array())
    {
        $this->_parm = $parm;
    }

    public function getApiContent()
    {
        $account = $this->_account;
        $parm = $this->_parm;
        
        $newheader = new AuthHeader();
        $newheader->setUsername($account['username']);
        $newheader->setPassword($account['password']);
        $newheader->setToken($account['token']);
        if (! empty($account['target'])) {
            $newheader->setTarget($account['target']);
        }
        $this->setAuthHeader($newheader);
        
        $type = new ReportRequestType();
        $type->setStartDate($parm['start_date'] . " 00:00:00");
        $type->setEndDate($parm['end_date'] . " 23:59:59");
        $type->setUnitOfTime($parm['time_type']);
        $type->setDevice(0);
        if ($parm['report_type'] == 5) {
            $type->setNumber(5000);
        }else{
            $type->setNumber(10000);
        }
        
        //$type->setNumber(5);
        
        if ($parm['report_type'] == 5) { // 搜索词
            $type->setPerformanceData(array(
                "impression",
                "click"
            ));
            $type->setLevelOfDetails(12);
            $type->setReportType(6);
            $type->setStatRange(3);
            $type->setUnitOfTime(5);
            
            $request = new GetRealTimeQueryDataRequest();
            $request->setRealTimeQueryRequestType($type);
            $response = $this->getRealTimeQueryData($request);
        } else {
            switch ($parm['report_type']) {
                case 1:
                    $type->setPerformanceData(array(
                        "impression",
                        "click",
                        "cost",
                        "cpc",
                        "ctr"
                    ));
                    $type->setLevelOfDetails(2);
                    $type->setReportType(2);
                    $type->setStatRange(2);
                    break;
                case 2:
                    $type->setPerformanceData(array(
                        "impression",
                        "click",
                        "cost",
                        "cpc",
                        "ctr"
                    ));
                    $type->setLevelOfDetails(3);
                    $type->setReportType(10);
                    $type->setStatRange(3);
                    break;
                case 3:
                    $type->setPerformanceData(array(
                        "impression",
                        "click",
                        "cost",
                        "cpc",
                        "ctr"
                    ));
                    $type->setLevelOfDetails(5);
                    $type->setReportType(11);
                    $type->setStatRange(5);
                    break;
                case 4:
                    //按小时取数据的时候，无法获得平均排名
                    if ($parm['time_type'] == 7) {
                        $type->setPerformanceData(array(
                            "impression",
                            "click",
                            "cost",
                            "cpc",
                            "ctr"
                        ));
                    } else {
                        $type->setPerformanceData(array(
                            "impression",
                            "click",
                            "cost",
                            "cpc",
                            "ctr",
                            "position"
                        ));
                    }
                    
                    $type->setLevelOfDetails(11);
                    $type->setReportType(14);
                    $type->setStatRange(11);
                    break;
                default:
                    break;
            }
            
            $request = new GetRealTimeDataRequest();
            $request->setRealTimeRequestType($type);
            $response = $this->getRealTimeData($request);
           
            $resheader=$this->getJsonHeader();
            if($resheader->desc !="success"){
                Log::write("error", $this->getJsonStr());
            }
            // $head=$this->getJsonHeader();
            // echo "status:".json_encode($head)."\n";
        }
        
        return $response;
    }

    /**
     * 获取body的内容
     *
     * @return string
     */
    private function getRequestBody()
    {
        $parm = $this->_parm;
        $res = '';
        
        if ($parm['report_type'] * 1 < 5) {
            $body = '"realTimeRequestTypes": {
            "performanceData": [
                "impression",
                "click",
                "cost",
                "cpc",
                "ctr"
            ],
            "levelOfDetails": %s,
            "startDate": "' . $parm['start_date'] . '",
            "endDate": "' . $parm['end_date'] . '",
            "reportType": %s,
            "statRange": %s,
            "unitOfTime": ' . $parm['time_type'] . ',
            "device": 0,
            "number":10
        }';
            switch ($parm['report_type']) {
                case 1:
                    $res = sprintf($body, 2, 2, 2); // 账户
                    break;
                case 2:
                    $res = sprintf($body, 3, 10, 3); // 计划
                    break;
                case 3:
                    $res = sprintf($body, 5, 11, 5); // 单元
                    break;
                case 4:
                    $res = sprintf($body, 2, 14, 2); // 关键字
                    break;
            }
        } else {
            $res = '"realTimeQueryRequestType": {
            "performanceData": [
                "impression",
                "click"
            ],
            "levelOfDetails": 7,
            "startDate": "' . $parm['start_date'] . '",
            "endDate": "' . $parm['end_date'] . '",
            "reportType": 6,
            "statRange": 3,
            "unitOfTime": 5,
            "device": 0,
            "number":10
        }';
        }
        
        return $res;
    }
}
?>