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

$date=isset($_GET['date'])?$_GET['date']:"";

Log::write("task_system","[date-start]".$date);

//-----清除数据--------------------------
$tmpsql = "delete from sem_sale_roi where roi_date='$date';
delete from sem_sale where sale_date='$date'";
$res=$pdo->execute($tmpsql);

if($res==0){
    //-------------------更新数据-------------------------------
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
    
    header("Content-type: text/html; charset=utf-8");
    echo "执行完成：【日期：".$date."】";
}else{
    header("Content-type: text/html; charset=utf-8");
    echo "删除数据错误:".$tmpsql;
}


?>