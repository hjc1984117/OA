<?php

namespace Models\Base;

$GLOBALS['/Models/Base/SqlOperator.php'] = 1;

class SqlOperator {

    const __default = self::Equals;
    const Equals = '=';
    //const EqualsOrNull = '<=>';
    const NotEquals = '<>';
    const GreaterEquals = '>=';
    const LessEquals = '<=';
    const GreaterThan = '>';
    const LessThan = '<';
    const Like = 'LIKE';
    const NotLike = 'NOT LIKE';
    const In = 'IN';
    const NotIn = 'NOT IN';
    const IsNull = 'IS NULL';
    const IsNotNull = 'IS NOT NULL';
    const IsNullOrEmpty = "IS NULL OR %s = ''";
    const IsNotNullAndEmpty = "IS NOT NULL AND %s <> ''";
    const Between = 'BETWEEN %s AND %s';
    const NotBetween = 'NOT BETWEEN %s AND %s';
    const Regexp = 'REGEXP';

}
