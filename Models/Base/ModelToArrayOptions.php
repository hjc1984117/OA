<?php

/**
 * 返回输出选项
 *
 * @author ChenHao
 * @version 2015/1/10
 */

namespace Models\Base;

$GLOBALS['/Models/Base/ModelToArrayOptions.php'] = 1;

class ModelToArrayOptions {

    const All2Str = 'All2Str'; // 所有字段均以字符串输出
    const Str2Time = 'Str2Time'; // 将时间格式转为时间戳输出，如：原始数据为"2015-1-10 12:00:00"，使用此选项后，输出为"1420891200"
    const Time2Str = 'Time2Str'; // 将时间戳转为时间格式输出，与'Str2Time'相反
    const DefaultValue = 'DefaultValue'; // 输出字段的默认值
    const ValueOnly = 'ValueOnly'; // 数组元素去掉键，只输出值

}
