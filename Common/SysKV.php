<?php

use Models\S_KV;
use Models\Base\SqlOperator;

class SysKV {

    public static function getValueByKey($key) {
        $KV = new S_KV();
        $KV->set_where_and(S_KV::$field_key, SqlOperator::Equals, $key);
        $db = create_pdo();
        $result = $KV->load($db, $KV);
        if (!$result[0]) return array('code' => 0, 'msg' => '获取键值对失败,或者键值对不存在', 'key' => '', 'value' => '');
        $model = $KV->to_array();
        return array('code' => 1, 'msg' => '获取键值对成功', 'key' => $model['key'], 'value' => $model['value']);
    }

    public static function setKeyValue($key, $value) {
        $KV = new S_KV();
        $KV->set_key($key);
        $KV->set_value($value);
        $db = create_pdo();
        $result = $KV->insert($db);
        if (!$result[0]) return array('code' => 0, 'msg' => '添加键值对失败,或添加的键值对已存在', 'key' => '', 'value' => '');
        return array('code' => 1, 'msg' => '添加键值对成功', 'key' => $key, 'value' => $value);
    }

    public static function updateValueByKey($key, $value) {
        $KV = new S_KV();
        $KV->set_value($value);
        $KV->set_where_and(S_KV::$field_key, SqlOperator::Equals, $key);
        $db = create_pdo();
        $result = $KV->update($db);
        if (!$result[0]) return array('code' => 0, 'msg' => '修改键值对失败,或者键值对不存在', 'key' => '', 'value' => '');
        $model = $KV->to_array();
        return array('code' => 1, 'msg' => '修改键值对成功', 'key' => $model['key'], 'value' => $model['value']);
    }

}
