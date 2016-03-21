<?php 
/**
 * roi统计
 * 
 * @author 科比
 *
 */
class Roi{
    private $_date="";
    
    
    
    public function init($date){
        $this->_date=$date;
    }
    /**
     * 获得报表数据
     * 
     * @param PdoMysql $pdo
     */
    public function getReportData($pdo){
        $date=$this->_date;
        $ksql="select k.*,a.product_type,a.site 
from sem_day_keyword k 
inner join sem_channel_account a on a.account=k.account
           where sem_time like '".$this->_date."%'";
      $keywords=$pdo->getAll($ksql);
      
      Log::write("task_system","[roi_sql_keywords]".$ksql);
      
      if(count($keywords)>0){
          foreach ($keywords as $k=>$v){
              if($v['product_type']=="soft"){
                  $qq_table="p_qqaccess_soft";
              }else{
                  $qq_table="p_qqaccess";
              }
              
              $channelname=BaseDatas::getChannelByKey($v['channel']);
              $sql="select s.channel,s.account,s.project,s.unit,s.keyword,group_concat(distinct s.search) as search,count(distinct q.qq_num) as qq_num,
count(distinct ss.qq) as sale_qq_num,sum(if(ss.today_flag=1,1,0)) as today_qq_num,
sum(ss.sale_money) as sale_money,sum(ss.second_money) as send_money
from sem_day_search s 
inner join sem_channel_account a on a.account=s.account
inner join ".$qq_table." q on q.keyword=s.search and q.channel='$channelname'  and q.addtime like '".$date."%'  
    and trim(REPLACE(REPLACE(q.into_url, CHAR(10), ''), CHAR(13), ''))='".$v['site']."'
left join sem_sale ss on ss.keywords=s.search and ss.product_type=a.product_type 
        and trim(REPLACE(REPLACE(ss.qq, CHAR(10), ''), CHAR(13), ''))=trim(REPLACE(REPLACE(q.qq_num, CHAR(10), ''), CHAR(13), '')) 
        and today_flag=1 and 
    ss.channel='".$v['channel']."'   and ss.sale_date like '".$date."%'
where s.channel='".$v['channel']."' and s.account='".$v['account']."' and s.project='".$v['project']."' and s.unit='".$v['unit']."' and s.keyword='".$v['keyword']."' and s.sem_time like '".$date."%'
group by s.channel,s.account,s.project,s.unit,s.keyword";
              $subdata=$pdo->getOne($sql);
              
              Log::write("task_system","[roi_sql_keywords_qq]".$sql);
              
//               echo $sql;exit;
              
              
              $cost=isset($v['cost'])?$v['cost']:0;
              
              $qq_num=isset($subdata['qq_num'])?intval($subdata['qq_num']):0;
              if($qq_num>0){
                  $qq_cost=$this->formatFloat($cost/$qq_num);
              }else{
                  $qq_cost=0;
              }
              
              $today_qq_num=isset($subdata['today_qq_num'])?intval($subdata['today_qq_num']):0;
              $sale_qq_num=isset($subdata['sale_qq_num'])?intval($subdata['sale_qq_num']):0;
              $sale_money=isset($subdata['sale_money'])?intval($subdata['sale_money']):0;
              $send_money=isset($subdata['send_money'])?intval($subdata['send_money']):0;
              
              $sale_qq_ratio=$sale_qq_num>0?$this->formatFloat($today_qq_num*100/$sale_qq_num):0;
              
              $sale_cost=$sale_qq_num>0?$this->formatFloat($cost/$sale_qq_num):0;
              $sale_profit=$sale_money-$cost;
              $front_roi=$cost >0 ?$this->formatFloat($sale_profit/$cost):0;
              $total_roi=$cost >0 ?$this->formatFloat(($sale_profit+$send_money)/$cost):0;
              
              
              
              $adddata=array(
                  'channel'=>$v['channel'],
                  'product_type'=>$v['product_type'],
                  'roi_date'=>$date,
                  'keywords'=>$v['keyword'],
                  'search_keywords'=>@$subdata['search'],
                  'sem_dy'=>$v['unit'],
                  'sem_plan'=>$v['project'],
                  'sem_account'=>$v['account'],
                  'sem_money'=>$v['cost'],
                  'qq_num'=>$qq_num,
                  'qq_cost'=>$qq_cost,
                  'today_qq_num'=>$today_qq_num,
                  'sale_qq_num'=>$sale_qq_num,
                  'sale_qq_ratio'=>$sale_qq_ratio,
                  'sale_money'=>$sale_money,
                  'sale_cost'=>$sale_cost,
                  'sale_profit'=>$sale_profit,
                  'front_roi'=>$front_roi,
                  'send_money'=>$send_money,
                  'total_roi'=>$total_roi
              );
    
              $pdo->add("sem_sale_roi", $adddata);
          }
      }
        
      return true;
    }
    /**
     * 格式化数据
     * 
     * @param unknown $data
     * @return string
     */
    private  function formatFloat($data){
        $data=sprintf("%.2f",$data);
        return $data;
    }
    
}
?>