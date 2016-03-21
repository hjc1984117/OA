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

$nexttime = time() - 10800;                                                                                                                                                                                                                                                                                                                                                                                                                                                                                           
$datetime = date("Y-m-d", $nexttime);
$hour = date("H",$nexttime);


$tmpsql = "TRUNCATE `sem_day_search_today`;
    TRUNCATE `sem_day_keyword_today`;";
$pdo->execute($tmpsql);

foreach ($seoconfig as $k => $v) {
    $accounts = $pdo->getAll("select * from sem_channel_account where channel in (" . $v . ")");
    if (count($accounts) > 0) {
        foreach ($accounts as $ac) {
            $sem = new $k();
            $sem->execute($pdo, $datetime, $hour, $ac);
        }
    }
}
?>