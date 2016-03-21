<?php 
interface HouerInter{
    /**
     * 
     * @param unknown $pdo   数据库连接
     * @param unknown $date  日期
     * @param unknown $houer  小时
     * @param unknown $parm  参数
     */
    public function execute($pdo,$date,$houer,$parm=array());
}
?>