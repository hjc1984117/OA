<?php
date_default_timezone_set('Asia/Shanghai'); //设置中国时区
$docroot=__DIR__;
$docroot=str_replace("\\","/", $docroot);

$config = include ($docroot.'/config.php');
include_once $docroot.'/common/db.php';
include_once $docroot.'/common/conts.php';
include_once $docroot.'/common/basedata.php';
include_once $docroot.'/sem/seminter.php';
include_once $docroot.'/sem/baidu.php';
include_once $docroot.'/hours/hourinter.php';
include_once $docroot.'/hours/baidu.php';
include_once $docroot.'/lib/log.php';

$pdo = new PdoMysql($config['dbconfig']);

$seoconfig = $config['seoconfig'];

$date=isset($_GET['date'])?$_GET['date']:"";
$houer=isset($_GET['houer'])?$_GET['houer']:"";


if(!empty($date) && !empty($houer)){
    //-----清除数据--------------------------
    $tmpsql = "delete from sem_houer_account where sem_time='$date $houer:00:00';
    delete from sem_houer_keyword where sem_time='$date $houer:00:00';
    delete from sem_houer_project where sem_time='$date $houer:00:00';
    delete from sem_houer_unit where sem_time='$date $houer:00:00';
    ";
   
    if($date==date("Y-m-d")){
        $tmpsql.="TRUNCATE `sem_day_search_today`;
            TRUNCATE `sem_day_keyword_today`;
            ";
    }else{
        $tmpsql.="delete from sem_day_keyword where sem_time='$date 00:00:00';
            delete from sem_day_search where sem_time='$date 00:00:00';
            ";
    }
   $res= $pdo->execute($tmpsql);
   
   if($res==0){
       //---重新更新数据-----------------------------
       foreach ($seoconfig as $k => $v) {
           $accounts = $pdo->getAll("select * from sem_channel_account where channel in (" . $v . ")");
           if (count($accounts) > 0) {
               foreach ($accounts as $ac) {
                   $sem = new $k();
                   $sem->execute($pdo, $date, $houer, $ac,true);
               }
           }
       }
       header("Content-type: text/html; charset=utf-8");
       echo "执行完成：【日期：".$date."】【小时：".$houer."】";
   }else{
       header("Content-type: text/html; charset=utf-8");
       echo "删除失败：".$tmpsql;
       exit;
   }
}else{
    header("Content-type: text/html; charset=utf-8");
    echo "日期或者小时信息不正确";
}


?>