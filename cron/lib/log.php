<?php 
class Log{
    static public function write($msgtype,$msg){
        $filename="";
//         if($msgtype=='access'){
//             $filename="access_".date("Y-m-d").".log";
//         }else{
//             $filename="error_".date("Y-m-d").".log";
//         }

        $filename=$msgtype."_".date("Y-m-d").".log";
        $path=dirname(dirname(__FILE__));
        
        $file=$path."/log/".$filename;
        $file=str_replace("\\","/",$file);
        
        $handle=fopen($file,'a');
        $content="[".date("Y-m-d H:i:s")."]".$msg."\r\n";
        fwrite($handle,$content);
        fclose($handle);
    }
}
?>