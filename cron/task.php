<?php 
date_default_timezone_set('Asia/Shanghai'); //设置中国时区
$docroot=__DIR__;
$docroot=str_replace("\\","/", $docroot);
$config=include($docroot.'/config.php');
include_once $docroot.'/common/db.php';
include_once $docroot.'/common/conts.php';
include_once $docroot.'/common/basedata.php';
include_once $docroot.'/system/sale.php';
include_once $docroot.'/system/roi.php';
include_once $docroot.'/sem/seminter.php';
include_once $docroot.'/sem/baidu.php';
include_once $docroot.'/lib/log.php';

$pdo=new PdoMysql($config['dbconfig']);
$date=date("Y-m-d", strtotime("-1day", strtotime(date("Y-m-d"))));
// $date="2016-03-13";

Log::write("task_system","[date-start]".$date);

$sale=new Sale();
$sale->init($date, ProductTypeConts::XMM);
$xmmdata=$sale->getReportData($pdo);
$sale->init($date, ProductTypeConts::SOFT);
$softdata=$sale->getReportData($pdo);
if(count($xmmdata)>0){
    foreach ($xmmdata as $v){
        $inflag=$pdo->add("sem_sale", $v);
    }
}
if(count($softdata)>0){
    foreach ($softdata as $v){
        $inflag=$pdo->add("sem_sale", $v);
    }
}

$roi=new Roi();
$roi->init($date);
$roi->getReportData($pdo);
Log::write("task_system","[date-end]".$date);
?>