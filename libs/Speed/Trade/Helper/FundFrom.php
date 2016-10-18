<?php

namespace Speed\Trade\Helper;

class FundFrom {

    //充值
    const CHONG_ZHI = "CZ";
    //订单补差
    const DD_BUCHA = "DDBC";
    //物流补差
    const WL_BUCHA = "WLBC";
    //退款
    const TUI_KUAN = "TK";
    //货款
    const HUO_KUAN = "HK";
    //佣金
    const YONG_JING = "YJ";
    //付款
    const FU_KUAN = "FK";
    //提现
    const TI_XIAN = "TX";
    //购买速贸币
    const BUY_SMB = "GMSMB";
    //其他补差
    const QT_BUCHA = "QTBC";
    //其他支出
    const QT_ZHI_CHU = "QTZC";

    public static function getDescription($from) {
        $data = array(
            'CZ' => '充值',
            'DDBC' => '订单补差',
            'WLBC' => '物流补差',
            'TK' => '退款',
            'HK' => '货款',
            'YJ' => '佣金',
            'FK' => '提现',
            'TX' => '充值',
            'GMSMB' => '购买速贸币',
            'QTBC' => '其他补差',
            'QTZC' => '其他支出',
        );

        if (!in_array($from, $data)) {
            throw new \Exception("FUND_FROM: $from 不存在");
        }

        return $data[$from];
    }

}
