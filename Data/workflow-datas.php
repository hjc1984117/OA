<?php

/**
 * 流程配置数据
 *
 * @author ChenHao
 * @copyright (c) 2015, 星密码集团
 * @version 2015/03/19
 */
$GLOBALS['/Data/workflow-datas.php'] = 1;

use Common\RoleType;

//行政处罚流程
function get_penalty_workflow($model) {
    $opts = array('selfOk', 'deptManagerOk', 'companyManagerOk', 'hrManagerOk', 'hrManagerDelete', 'stop');
    $step1_role_type = get_step1_role_type($model['dept1_id']);
    $steps = array(
        0 => array('text' => '待本人确认', 'style' => 'label label-success', 'opts' => array(
                RoleType::Employee => array('selfOk'),
                RoleType::HrManager => array('hrManagerDelete'),
                RoleType::DeptManager => array('hrManagerDelete'),
            )),
//        1 => array('text' => '待部门经理审核', 'style' => 'label label-primary', 'opts' => array(
//                $step1_role_type => array('deptManagerOk', 'stop')
//            )),
//        2 => array('text' => '待行政审核', 'style' => 'label label-primary', 'opts' => array(
//                RoleType::HrManager => array('hrManagerOk')
//            )),
        1 => array('text' => '完成', 'style' => 'label label-primary', 'opts' => array(
            ))
    );
    set_self_opts($model['userid'], $steps, $step1_role_type);
    return array('name' => 'penalty', 'opts' => $opts, 'steps' => $steps);
}

//补卡流程
function get_resign_workflow($model) {
    $opts = array('selfOk', 'selfDelete', 'selfEdit', 'deptManagerOk', 'hrManagerOk', 'stop');
    $step1_role_type = get_step1_role_type($model['dept1_id']);
    $steps = array(
        0 => array('text' => '待本人确认', 'style' => 'label label-success', 'opts' => array(
                RoleType::Employee => array('selfOk', 'selfDelete', 'selfEdit')
            )),
        1 => array('text' => '待部门经理审核', 'style' => 'label label-primary', 'opts' => array(
                $step1_role_type => array('deptManagerOk', 'stop')
            )),
        2 => array('text' => '待行政审核', 'style' => 'label label-primary', 'opts' => array(
                RoleType::HrManager => array('hrManagerOk', 'stop')
            )),
        3 => array('text' => '完成', 'style' => 'label label-primary', 'opts' => array(
                RoleType::HrManager => array('selfDelete')
            ))
    );
    set_self_opts($model['userid'], $steps, $step1_role_type);
    //总经理代财务部门主管
    if ($model['dept1_id'] == 4) {
        $steps[1]['opts'] = array(
            $step1_role_type => array('deptManagerOk', 'stop'),
            RoleType::CompanyManager1 => array('deptManagerOk', 'stop')
        );
    }
    return array('name' => 'resign', 'opts' => $opts, 'steps' => $steps);
}

//请假流程
function get_causeleave_workflow($model) {
    $opts = array('selfOk', 'selfDelete', 'selfEdit', 'deptManagerOk', 'companyManager1Ok', 'companyManager2Ok', 'hrManagerOk', 'managerStop');
    //行政、财务、软件、话务部
    $array1 = array(2, 4, 11, 14);
    //运营、竞价
    $array2 = array(8, 9);
    //售前、售后
    $array3 = array(6, 7);
    //法务
    $array4 = array(3);
    $role_type = get_role_type($model['userid']);
    $step1_role_type = get_step1_role_type($model['dept1_id']);
    $steps = array(
        0 => array('text' => '待本人确认', 'style' => 'label label-primary', 'opts' => array(
                RoleType::Employee => array('selfOk', 'selfDelete', 'selfEdit')
            )),
        1 => array('text' => '待部门主管审核', 'style' => 'label label-primary', 'opts' => array(
                RoleType::DeptManager1 => array('deptManagerOk', 'managerStop')
            )),
        2 => array('text' => '待部门经理审核', 'style' => 'label label-primary', 'opts' => array(
                $step1_role_type => array('deptManagerOk', 'managerStop')
            )),
        3 => array('text' => '待副总经理审核', 'style' => 'label label-primary', 'opts' => array(
                RoleType::CompanyManager2 => array('companyManager2Ok', 'managerStop')
            )),
        4 => array('text' => '待总经理审核', 'style' => 'label label-primary', 'opts' => array(
                RoleType::CompanyManager1 => array('companyManager1Ok', 'managerStop')
            )),
        5 => array('text' => '待行政审核', 'style' => 'label label-primary', 'opts' => array(
                RoleType::HrManager => array('hrManagerOk', 'managerStop')
            )),
        6 => array('text' => '完成', 'style' => 'label label-primary', 'opts' => array(
            ))
    );
    set_self_opts($model['userid'], $steps, $step1_role_type);
    $dept1_id = $model['dept1_id'];
    $hours = $model['hours'];
    if (in_array($dept1_id, $array1)) { //行政、财务、软件
        if (in_array($role_type, array(RoleType::DeptManager, RoleType::HrManager, RoleType::FinancialManager))) {
            unset($steps[2]);
            unset($steps[3]);
            //总经理代财务部门主管
            if ($dept1_id == 4) {
                $steps[1]['opts'] = array(
                    RoleType::DeptManager1 => array('deptManagerOk', 'managerStop'),
                    RoleType::CompanyManager1 => array('deptManagerOk', 'managerStop')
                );
            }
        } else {
            if ($hours <= 24 * 3) {
                unset($steps[3]);
                unset($steps[4]);
            } else {
                unset($steps[3]);
            }
        }
        //为软件部特殊处理
//        if ($dept1_id == 11) {
//            if (str_equals($role_type, RoleType::DeptManager1)) {
//                unset($steps[1]);
//            }
//        }
    } elseif (in_array($dept1_id, $array2)) { //运营、竞价
        if (str_equals($role_type, RoleType::DeptManager)) {
            unset($steps[2]);
        } else {
            if ($hours <= 24 * 3) {
                unset($steps[3]);
                unset($steps[4]);
            }
        }
    } elseif (in_array($dept1_id, $array3)) { //售前、售后
        if (str_equals($role_type, RoleType::DeptManager)) {
            unset($steps[2]);
        } elseif (str_equals($role_type, RoleType::DeptManager1) || str_equals($role_type, RoleType::DeptManager2)) {
            if ($hours <= 24 * 1) {
                unset($steps[3]);
                unset($steps[4]);
            }
        } else {
            if ($hours <= 24 * 3) {
                unset($steps[3]);
                unset($steps[4]);
            }
        }
    } elseif (in_array($dept1_id, $array4)) { //法务
        unset($steps[2]);
        unset($steps[3]);
    } else {
        if (str_equals($role_type, RoleType::DeptManager)) {
            unset($steps[2]);
            unset($steps[3]);
        } elseif (str_equals($role_type, RoleType::CompanyManager2)) {
            unset($steps[2]);
            unset($steps[3]);
        } elseif (str_equals($role_type, RoleType::CompanyManager1)) {
            unset($steps[2]);
            unset($steps[3]);
            unset($steps[4]);
        } else {
            if ($hours <= 24 * 1) {
                unset($steps[3]);
                unset($steps[4]);
            }
        }
    }
    //软件部需要部门主管审核流程，其他部门去掉
    if (!in_array($dept1_id, array(11))) {
        unset($steps[1]);
    }
    return array('name' => 'causeleave', 'opts' => $opts, 'steps' => $steps);
}

//调休流程
function get_adjustrest_workflow($model) {
    $model['hours'] = $model['rest_days'] * 24;
    $workflow_config_array = get_causeleave_workflow($model);
    return array('name' => 'adjustrest', 'opts' => $workflow_config_array['opts'], 'steps' => $workflow_config_array['steps']);
}

//费用报销流程
function get_expense_workflow($model) {
    $opts = array('selfOk', 'selfDelete', 'selfEdit', 'deptManagerOk', 'companyManager1Ok', 'companyManager2Ok', 'FinancialManagerOK', 'stop');
    $step1_role_type = get_step1_role_type($model['dept1_id']);
    $steps = array(
        0 => array('text' => '待本人确认', 'style' => 'label label-success', 'opts' => array(
                RoleType::Employee => array('selfOk', 'selfDelete', 'selfEdit'),
            )),
        1 => array('text' => '待部门经理审核', 'style' => 'label label-primary', 'opts' => array(
                $step1_role_type => array('deptManagerOk', 'stop')
            )),
        2 => array('text' => '待副总经理审核', 'style' => 'label label-primary', 'opts' => array(
                RoleType::CompanyManager2 => array('companyManager2Ok', 'stop')
            )),
        3 => array('text' => '待总经理审核', 'style' => 'label label-primary', 'opts' => array(
                RoleType::CompanyManager1 => array('companyManager1Ok', 'stop')
            )),
        4 => array('text' => '待财务审核', 'style' => 'label label-primary', 'opts' => array(
                RoleType::FinancialManager => array('FinancialManagerOK', 'stop')
            )),
        5 => array('text' => '完成', 'style' => 'label label-primary', 'opts' => array(
            ))
    );
    set_self_opts($model['userid'], $steps, $step1_role_type);
    return array('name' => 'expense', 'opts' => $opts, 'steps' => $steps);
}

//借款流程
function get_borrowmoney_workflow($model) {
    $opts = array('selfOk', 'selfDelete', 'selfEdit', 'deptManagerOk', 'companyManager1Ok', 'companyManager2Ok', 'FinancialManagerOK', 'stop');
    $step1_role_type = get_step1_role_type($model['dept1_id']);
    $steps = array(
        0 => array('text' => '待本人确认', 'style' => 'label label-success', 'opts' => array(
                RoleType::Employee => array('selfOk', 'selfDelete', 'selfEdit'),
            )),
        1 => array('text' => '待部门经理审核', 'style' => 'label label-primary', 'opts' => array(
                $step1_role_type => array('deptManagerOk', 'stop')
            )),
        2 => array('text' => '待副总经理审核', 'style' => 'label label-primary', 'opts' => array(
                RoleType::CompanyManager2 => array('companyManager2Ok', 'stop')
            )),
        3 => array('text' => '待总经理审核', 'style' => 'label label-primary', 'opts' => array(
                RoleType::CompanyManager1 => array('companyManager1Ok', 'stop')
            )),
        4 => array('text' => '待财务审核', 'style' => 'label label-primary', 'opts' => array(
                RoleType::FinancialManager => array('FinancialManagerOK', 'stop')
            )),
        5 => array('text' => '完成', 'style' => 'label label-primary', 'opts' => array(
            ))
    );
    set_self_opts($model['userid'], $steps, $step1_role_type);
    return array('name' => 'borrowmoney', 'opts' => $opts, 'steps' => $steps);
}

//领款流程
function get_drawmoney_workflow($model) {
    $opts = array('selfOk', 'selfDelete', 'selfEdit', 'deptManagerOk', 'companyManager1Ok', 'companyManager2Ok', 'FinancialManagerOK', 'stop');
    $step1_role_type = get_step1_role_type($model['dept1_id']);
    $steps = array(
        0 => array('text' => '待本人确认', 'style' => 'label label-success', 'opts' => array(
                RoleType::Employee => array('selfOk', 'selfDelete', 'selfEdit'),
            )),
        1 => array('text' => '待部门经理审核', 'style' => 'label label-primary', 'opts' => array(
                $step1_role_type => array('deptManagerOk', 'stop')
            )),
        2 => array('text' => '待副总经理审核', 'style' => 'label label-primary', 'opts' => array(
                RoleType::CompanyManager2 => array('companyManager2Ok', 'stop')
            )),
        3 => array('text' => '待总经理审核', 'style' => 'label label-primary', 'opts' => array(
                RoleType::CompanyManager1 => array('companyManager1Ok', 'stop')
            )),
        4 => array('text' => '待财务审核', 'style' => 'label label-primary', 'opts' => array(
                RoleType::FinancialManager => array('FinancialManagerOK', 'stop')
            )),
        5 => array('text' => '完成', 'style' => 'label label-primary', 'opts' => array(
            ))
    );
    set_self_opts($model['userid'], $steps, $step1_role_type);
    return array('name' => 'drawmoney', 'opts' => $opts, 'steps' => $steps);
}

//申购流程
function get_apply_buy($model) {
    $opts = array('selfEdit', 'selfDelete', 'selfOk', 'deptManagerOk', 'hrManagerOk', 'companyManagerOk', 'stop');
    $step1_role_type = get_step1_role_type($model['dept1_id']);
    $steps = array(
        0 => array('text' => '待本人确认', 'style' => 'label label-success', 'opts' => array(
                RoleType::Employee => array('selfEdit', 'selfDelete', 'selfOk')
            )),
        1 => array('text' => '待部门经理审核', 'style' => 'label label-success', 'opts' => array(
                $step1_role_type => array('deptManagerOk', 'stop')
            )),
        2 => array('text' => '待行政审核', 'style' => 'label label-primary', 'opts' => array(
                RoleType::HrManager => array('hrManagerOk', 'stop')
            )),
        3 => array('text' => '待总经理审核', 'style' => 'label label-primary', 'opts' => array(
                RoleType::CompanyManager1 => array('companyManagerOk', 'stop')
            )),
        4 => array('text' => '完成', 'style' => 'label label-primary', 'opts' => array(
            ))
    );
    set_self_opts($model['userid'], $steps, $step1_role_type);
    if ($model['price'] < 100) {
        unset($steps[4]);
    }
    return array('name' => 'apply', 'opts' => $opts, 'steps' => $steps);
}

function get_step1_role_type($dept1_id) {
    $step1_role_type = RoleType::DeptManager;
    if ($dept1_id == 2) $step1_role_type = RoleType::HrManager;
    if ($dept1_id == 4) $step1_role_type = RoleType::FinancialManager;
    return $step1_role_type;
}

function set_self_opts($userid, &$steps, $step1_role_type) {
    if (request_userid() == $userid) {
        $steps[0]['opts'] = array_merge($steps[0]['opts'], array($step1_role_type => array('selfOk', 'selfDelete', 'selfEdit')));
    }
}

/*
 * 
1	总经办
2	行政部
3	法务部
4	财务部
5	实物部
6	星密码售后
7	星密码售前
8	运营部
9	竞价部
10	市场部
11	软件部
12	网推公司
 * 
 */
