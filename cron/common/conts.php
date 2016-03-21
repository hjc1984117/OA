<?php 
/**
 * 常量
 * 
 * @author 科比
 *
 */
class SqlConts{
    const sqlTempPlatform ="select p.qq,sum(p.money) as total from %s p where p.add_time >= '%s' and p.add_time < '%s' group by p.qq";//二次销售-流量业绩统计
    
    const sqlTempPhysica = "select p.qq,sum(p.agent_price) as total 
from %s p 
where p.add_time >= '%s' and p.add_time < '%s' 
group by p.qq";//二次销售-实物业绩统计
    
    const sqlTempDecoration = "select p.qq,sum(p.decoration_price) as total 
from %s p 
where p.add_time >= '%s' and p.add_time < '%s' 
group by p.qq";//二次销售-装修业绩统计
    
    const sqlTempFillsSecond = "select p.qq,sum(p.fill_sum) as total 
from %s p 
where p.add_time >= '%s' and p.add_time < '%s' 
group by p.qq";//二次销售-补欠款统计
    
    const sqlTempSaleCount = "select p.qq,sum(p.money) as total,p.channel,substring(p.addtime,1,10) as addtime
from %s p
where p.addtime >= '%s' and p.addtime < '%s'
group by p.qq";//销售统计
    
    const sqlTempCashback = "select s.qq,sum(p.cashback) as total 
from %s p 
inner join %s s on s.id=p.s_id
where  p.date >= '%s' and p.date < '%s' 
group by s.qq";//返现统计
    
    
    const sqlTempFills = "select s.qq,sum(p.fill_sum) as total 
from %s p 
inner join %s s on s.id=p.sale_id
where p.add_time >= '%s' and p.add_time < '%s' 
group by s.qq";//补欠款统计
    
    
    const sqlTempUpgrade = "select s.qq,sum(p.upgrade_sum) as total 
from %s p 
inner join %s s on s.id=p.sale_id
where p.add_time >= '%s' and p.add_time < '%s' 
group by s.qq";//升级统计
    
    const sqlTempRefund = "select s.qq,sum(p.money) as total 
from %s p 
inner join %s s on s.id=p.s_id
where p.date >= '%s' and p.date < '%s' 
group by s.qq";//退款记录统计
    
    
    const sqlTempQQaccess = "select p.qq_num,p.keyword,p.channel,substring(p.addtime,1,10) as addtime
from %s p
where trim(REPLACE(REPLACE(p.qq_num, CHAR(10), ''), CHAR(13), ''))='%s' and presales_id >0 limit 0,1";//退款记录统计
}

/**
 * 产品类型
 * @author 科比
 *
 */
class ProductTypeConts{
    const XMM="xmm";//星密码
    const SOFT="soft";//店宝宝
}
?>