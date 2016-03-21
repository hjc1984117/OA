<?php

/**
 * 常量
 *
 * @author ChenHao
 * @version 2015/3/19
 */

namespace Common;

$GLOBALS['/common/RoleType.php'] = 1;

class RoleType {

    const __default = self::Employee;
    const Employee = 'Employee'; //0.普通员工
    const CompanyManager0 = 'CompanyManager0'; //1.董事长
    const CompanyManager1 = 'CompanyManager1'; //2.总经理
    const CompanyManager2 = 'CompanyManager2'; //3.副总经理
    const HrManager = 'HrManager'; //4.人事经理
    const FinancialManager = 'FinancialManager'; //5.财务经理
    const DeptManager = 'DeptManager'; //6.部门经理
    const HrManager1 = 'HrManager1'; //7.人事经理(行政专员)
    const HrManager2 = 'HrManager2'; //8.人事经理(行政前台)
    const FinancialManager1 = 'FinancialManager1'; //9.财务经理1
    const FinancialManager2 = 'FinancialManager2'; //10.财务经理2
    const DeptManager1 = 'DeptManager1'; //11.部门主管
    const DeptManager2 = 'DeptManager2'; //12.部门副主管
    const DeptManager3 = 'DeptManager3'; //13.部门角色（备用）

}
