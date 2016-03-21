<?php 
/**
 * 交易统计
 * 
 * @author 科比
 *
 */
class Sale{
    private $_date="";
    private $_producttype="";
    private $_table_platform="p_platform";
    private $_table_physica ="p_physica";
    private $_table_decoration="p_decoration";
    private $_table_fills_second="p_fills_second";
    private $_table_salecount="p_salecount";
    private $_table_cashback="p_cashback";
    private $_table_fills="p_fills";
    private $_table_upgrade="p_upgrade";
    private $_table_refund="p_refund";
    private $_table_qqaccess="p_qqaccess";
    
    
    
    public function init($date,$product_type){
        $this->_date=$date;
        $this->_producttype=$product_type;
        switch ($product_type){
            case ProductTypeConts::XMM:
                $this->_table_platform="p_platform";
                $this->_table_physica ="p_physica";
                $this->_table_decoration="p_decoration";
                $this->_table_fills_second="p_fills_second";
                $this->_table_salecount="p_salecount";
                $this->_table_cashback="p_cashback";
                $this->_table_fills="p_fills";
                $this->_table_upgrade="p_upgrade";
                $this->_table_refund="p_refund";
                $this->_table_qqaccess="p_qqaccess";
                break;
            case ProductTypeConts::SOFT:
                $this->_table_platform="p_platform_soft";
                $this->_table_physica ="p_physica_soft";
                $this->_table_decoration="p_decoration_soft";
                $this->_table_fills_second="p_fills_second_soft";
                $this->_table_salecount="p_salecount_soft";
                $this->_table_cashback="p_cashback_soft";
                $this->_table_fills="p_fills_soft";
                $this->_table_upgrade="p_upgrade_soft";
                $this->_table_refund="p_refund_soft";
                $this->_table_qqaccess="p_qqaccess_soft";
                break;
        }
    }
    /**
     * 获得报表数据
     * 
     * @param PdoMysql $pdo
     */
    public function getReportData($pdo){
        $today=$this->_date;
        $next=date("Y-m-d",strtotime("+1 day",strtotime($today)));
        $starttime=$today." 00:00:00";
        $endtime=$next." 00:00:00";
        $result=array();
        
        //流量业绩统计
        $sql=sprintf(SqlConts::sqlTempPlatform,$this->_table_platform,$starttime,$endtime);
        Log::write("task_system","[sale_sql_platform]".$sql);
        $data=$pdo->getAll($sql);
        if(isset($data[0])){
            foreach ($data as $v){
                $result[$v['qq']]['qq']=$v['qq'];
                $result[$v['qq']]['platform_money']=$v['total'];
            }
        }
        
        //实物业绩统计
        $sql=sprintf(SqlConts::sqlTempPhysica,$this->_table_physica,$starttime,$endtime);
        Log::write("task_system","[sale_sql_Physica]".$sql);
        $data=$pdo->getAll($sql);
        if(isset($data[0])){
            foreach ($data as $v){
                $result[$v['qq']]['qq']=$v['qq'];
                $result[$v['qq']]['physica_money']=$v['total'];
            }
        }
        
        //装修业绩统计
        $sql=sprintf(SqlConts::sqlTempDecoration,$this->_table_decoration,$starttime,$endtime);
        Log::write("task_system","[sale_sql_Decoration]".$sql);
        $data=$pdo->getAll($sql);
        if(isset($data[0])){
            foreach ($data as $v){
                $result[$v['qq']]['qq']=$v['qq'];
                $result[$v['qq']]['decoration_money']=$v['total'];
            }
        }
        
        
        //二次补欠款金额统计
        $sql=sprintf(SqlConts::sqlTempFillsSecond,$this->_table_fills_second,$starttime,$endtime);
        Log::write("task_system","[sale_sql_FillsSecond]".$sql);
        $data=$pdo->getAll($sql);
        if(isset($data[0])){
            foreach ($data as $v){
                $result[$v['qq']]['qq']=$v['qq'];
                $result[$v['qq']]['fills_second_money']=$v['total'];
            }
        }
        
        
        //销售统计
        $sql=sprintf(SqlConts::sqlTempSaleCount,$this->_table_salecount,$starttime,$endtime);
        Log::write("task_system","[sale_sql_SaleCount]".$sql);
        $data=$pdo->getAll($sql);
        if(isset($data[0])){
            foreach ($data as $v){
                $result[$v['qq']]['qq']=$v['qq'];
                $result[$v['qq']]['sale_count_money']=$v['total'];
                $result[$v['qq']]['salecount_date']=$v['addtime'];
            }
        }
        
        
        //返现记录统计
        $sql=sprintf(SqlConts::sqlTempCashback,$this->_table_cashback,$this->_table_salecount,$starttime,$endtime);
        Log::write("task_system","[sale_sql_Cashback]".$sql);
        $data=$pdo->getAll($sql);
        if(isset($data[0])){
            foreach ($data as $v){
                $result[$v['qq']]['qq']=$v['qq'];
                $result[$v['qq']]['cashback_money']=$v['total'];
            }
        }
        
        //补欠款记录统计
        $sql=sprintf(SqlConts::sqlTempFills,$this->_table_fills,$this->_table_salecount,$starttime,$endtime);
        Log::write("task_system","[sale_sql_Fills]".$sql);
        $data=$pdo->getAll($sql);
        if(isset($data[0])){
            foreach ($data as $v){
                $result[$v['qq']]['qq']=$v['qq'];
                $result[$v['qq']]['fills_money']=$v['total'];
            }
        }
        
        //升级统计
        $sql=sprintf(SqlConts::sqlTempUpgrade,$this->_table_upgrade,$this->_table_salecount,$starttime,$endtime);
        Log::write("task_system","[sale_sql_Upgrade]".$sql);
        $data=$pdo->getAll($sql);
        if(isset($data[0])){
            foreach ($data as $v){
                $result[$v['qq']]['qq']=$v['qq'];
                $result[$v['qq']]['upgrade_money']=$v['total'];
            }
        }
        
        //退款统计
        $sql=sprintf(SqlConts::sqlTempRefund,$this->_table_refund,$this->_table_salecount,$starttime,$endtime);
        Log::write("task_system","[sale_sql_Refund]".$sql);
        $data=$pdo->getAll($sql);
        if(isset($data[0])){
            foreach ($data as $v){
                $result[$v['qq']]['qq']=$v['qq'];
                $result[$v['qq']]['refund_money']=$v['total'];
            }
        }
        
        
        if(count($result)>0){
            foreach ($result as $k=>$v){
                $result[$k]['sale_date']=$this->_date;
                $result[$k]['product_type']=$this->_producttype;
                $result[$k]['today_flag']=0;
          
               
                $sql=sprintf(SqlConts::sqlTempQQaccess,$this->_table_qqaccess,$k);
                $data=$pdo->getAll($sql);
               
                $result[$k]['today_flag']=0;
                if(isset($data[0])){
                    $channel=$data[0]['channel'];
                    $adddate=$data[0]['addtime'];
                    
                    $channel=BaseDatas::getChannelByName($channel);
                    $keyword=$data[0]['keyword'];
                    $result[$k]['channel']=$channel;
                    $result[$k]['keywords']=$keyword;
                    
                    if($adddate==@$v['salecount_date']){//判断是否及时
                        $result[$k]['today_flag']=1;
                    }
                }else{
                    $result[$k]['channel']="";
                    $result[$k]['keywords']="";
                }
                
                unset($result[$k]['salecount_date']);//清除判断是否及时的数据
                
                $result[$k]['sale_money']=@$v['sale_count_money']*1 + @$v['fills_money']*1 + @$v['upgrade_money']*1 - @$v['cashback_money']*1  - @$v['refund_money']*1;
                $result[$k]['second_money']=@$v['platform_money']*1 + @$v['physica_money']*1 + @$v['decoration_money']*1 + @$v['fills_second_money']*1;
            }
        }
        
      return $result;
    }
    
}
?>