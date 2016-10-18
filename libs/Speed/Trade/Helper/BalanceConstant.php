<?php

namespace Speed\Trade\Helper;

class BalanceConstant {

    /**
     * FUND_CLASS
     */
    const FC_XIN_YONG_KA = 1;          //信用卡
    const FC_ZX_YINGHANG = 2;       //在线银行
    const FC_HUI_KUAN = 3;              //汇款
    const FC_XIANJING_YU_E = 4;        //现金余额

    /**
     * FUND_FROM
     */
    //POOL
    const FF_POOL_CHONG_ZHI = 11;           //充值
    const FF_POOL_TI_XIAN = 12;         //提现

    /**
     * ORDER
     */
    const FF_ORDER_FU_KUAN = 21;            //订单支付
    const FF_ORDER_ALL_HKUAN = 22;           //全额货款支付
    const FF_ORDER_YONG_JING = 23;               //货款佣金
    const FF_ORDER_CANCEL = 24;                 //订单取消退款
    const FF_ORDER_DD_BUCHA = 25;         //订单内单品缺货退款
    const FF_ORDER_WL_BUCHA = 26;           //物流重量核对补差
    const FF_ORDER_WULIU_FEI = 27;           //订单运费

    /**
     * JIUFEN
     */
    const FF_JIUFEN_HKUAN = 41;  //纠纷内未退款货款
    const FF_JIUFEN_TUIKUAN = 42; //纠纷退款 
    const FF_JIUFEN_WULIU_FEI = 43; //纠纷内退货物流费用
    const FF_JIUFEN_WULIU_NO_FEI = 44; //纠纷内退还物流费用

    /**
     * 费用
     */
    const FF_FEI_TI_XIAN = 71; //提现费用
    const FF_FEI_FANYI = 72;     //翻译费用

    /**
     * 其他
     */
    const FF_QT_BUY_SMB = 81;     //购买速贸币
    const FF_QT_BUCHA = 92;     //其他补差
    const FF_QT_ZHI_CHU = 93;     //其他支出
    const FF_QT_SHOU_RU = 94;     //其他收入
    const FF_QT_UNKNOWN = 99; // 其他为未知

    /**
     * FUND_USER
     */
    const USER_PLAT = 1;
    const USER_MAKER = 2;
    const USER_BUYER = 3;

    /**
     * FUND_CURRENCY
     */
    const CURRENCY_CNY = 1;
    const CURRENCY_JPY = 2;

    /**
     * 汇率转换类型
     */
    const RATE_CNY_TO_JPY = 1;
    const RATE_JPY_TO_CNY = 2;

    
    /**
     * 入帐类型
     */
    const BALANCE_IN = "in";
    const BALANCE_OUT = "out";

    /**
     * PLAT USER ID
     */
    const PLAT_USER_ID = 1;

    private static $cny = array(
        11 => "充值",
        12 => "提现",
        22 => "全额货款支付",
        23 => "货款佣金",
        41 => "纠纷内未退款货款",
        43 => "纠纷内退货物流费用",
        44 => "纠纷内退还物流费用",
        71 => "提现费用",
        72 => "翻译费用",
        81 => "购买速贸币",
        92 => "其他补差",
        93 => "其他支出",
        94 => "其他收入",
        99 => "其他",
    );
    private static $jpy = array(
        11 => "充值",
        12 => "提现",
        21 => "订单支付",
        22 => "全额货款支付",
        24 => "订单取消退款",
        25 => "订单内单品缺货退款",
        26 => "物流重量核对补差",
        27 => "订单运费",
        42 => "纠纷退款",
        71 => "提现费用",
        72 => "其他补差",
        93 => "其他支出",
        94 => "其他收入",
        99 => "其他",
    );
    private static $plat = array(
        11 => "充值",
        12 => "提现",
        21 => "订单支付",
        22 => "全额货款支付",
        24 => "订单取消退款",
        25 => "订单内单品缺货退款",
        26 => "物流重量核对补差",
        27 => "订单运费",
        42 => "纠纷退款",
        71 => "提现费用",
        72 => "其他补差",
        93 => "其他支出",
        94 => "其他收入",
        99 => "其他",
    );
    public static $class = array(
        1 => "信用卡", //信用卡
        2 => "在线银行", //在线银行
        3 => "线下汇款", //汇款
        4 => "现金余额", //现金余额
    );

    public static function getFundFromList($user_type) {
        $list = array();
        if ($user_type == self::USER_BUYER) {
            $list = self::$jpy;
        } elseif ($user_type == self::USER_MAKER) {
            $list = self::$cny;
        } elseif ($user_type == self::USER_PLAT) {
            $list = self::$plat;
        }

        return $list;
    }

    public static function getFundClassList() {
        return self::$class;
    }

    public static function getZhDescForFundFrom($fund_from) {
        return isset(self::$cny[$fund_from]) && self::$cny[$fund_from] ? self::$cny[$fund_from] : "";
    }

    public static function getJPDescForFundFrom($fund_from) {
        return isset(self::$jpy[$fund_from]) && self::$jpy[$fund_from] ? self::$jpy[$fund_from] : "";
    }

    public static function getZhDescForFundClass($fund_class) {
        return isset(self::$class[$fund_class]) && self::$class[$fund_class] ? self::$class[$fund_class] : "";
    }

}
