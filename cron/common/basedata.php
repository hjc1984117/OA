<?php 
/**
 * 基本数据
 * @author 科比
 *
 */
class BaseDatas{
    
    private static $_channels=array(
        'baidu_pc'=>'百度(PC)',
        'baidu_sp'=>'百度(移动)',
        '360'=>'360',
        'sougou'=>'搜狗'
    );
    /**
     * 获得名称
     * @param string $key
     */
    public static function getChannelByKey($key=""){
        return @self::$_channels[$key];
    }
    /**
     * 获得所有的渠道
     * 
     * @return multitype:string
     */
    public static function getAllChannels(){
        return self::$_channels;
    }
    /**
     * 根据名字获得编码
     * @param unknown $name
     * @return string
     */
    public static function getChannelByName($name){
        foreach (self::$_channels as $k=>$v){
            if($v==$name){
                return $k;
            }
        }
        return "";
    }
}
?>