<?php

require_once __DIR__ . '/../src/bootstrap.php';

if (!Speed\Trade\Helper\Auth::AllowRemote($_SERVER['REMOTE_ADDR'])) {
    echo json_encode(array("err" => 400, "msg" => "不能访问"));
    exit;
}

$action = isset($_REQUEST['service']) ? $_REQUEST['service'] : '';

$services = array("JPY.List.Fund", "CNY.List.Fund", "Balance.last", "Balance.Search", "Balance.Last.CNY", "FundFrom.List", "Pool.Search");

if (!in_array($action, $services)) {
    echo json_encode(array("err" => 401, "msg" => "不能访问"));
    exit;
}

$json = file_get_contents("php://input");

if ($action == 'JPY.List.Fund') {
    try {
        $post = json_decode($json, true);
        $user_id = isset($post['user_id']) ? intval($post['user_id']) : 0;
        $user_type = isset($post['user_type']) ? intval($post['user_type']) : 0;
        $page = isset($post['page']) && intval($post['page']) > 0 ? intval($post['page']) : 1;
        $numPerPage = isset($post['numPerPage']) && $post['numPerPage'] > 0 ? intval($post['numPerPage']) : 20;
        $data = Speed\Trade\Component\Admin::getInstance()->setEntityManager($entityManager)->getJPYListFund($user_id, $user_type, $page, $numPerPage);
        $result = array("err" => 200, "data" => $data);
    } catch (\Exception $err) {
        $result = array("err" => 500, "msg" => "failure");
    }
} elseif ($action == 'CNY.List.Fund') {
    try {
        $post = json_decode($json, true);
        $user_id = isset($post['conditions']['user_id']) ? intval($post['conditions']['user_id']) : 0;
        $user_type = isset($post['conditions']['user_type']) ? intval($post['conditions']['user_type']) : 0;
        $page = isset($post['page']) && intval($post['page']) > 0 ? intval($post['page']) : 1;
        $numPerPage = isset($post['numPerPage']) && $post['numPerPage'] > 0 ? intval($post['numPerPage']) : 20;
        $data = Speed\Trade\Component\Admin::getInstance()->setEntityManager($entityManager)->getCNYListFund($user_id, $user_type, $page, $numPerPage);
        $result = array("err" => 200, "data" => $data);
    } catch (\Exception $err) {
        $result = array("err" => 500, "msg" => "failure");
    }
} elseif ($action == 'Balance.last') {
    $post = json_decode($json, true);
    $user_id = $post['user_id'];
    $user_type = $post['user_type'];
    $currency = $post['currency'];

    $data = \Speed\Trade\Component\Balance::FactoryCurrency($currency)->setEntityManager($entityManager)->getLastBalance($user_id, $user_type);
    $result = array("err" => 200, "msg" => "", "data" => $data);
} elseif ($action == 'Balance.Search') {
    $post = json_decode($json, true);
    $conditions = $post['conditions'];
    if (!isset($conditions['user_id']) || !isset($conditions['user_type'])) {
        throw new Exception("请提供用户id，用户类型");
    }

    $currency = isset($conditions['currency']) ? $conditions['currency'] : \Speed\Trade\Component\Balance::getDefaultCurrency($conditions['user_type']);

    $page = isset($post['page']) && intval($post['page']) > 0 ? intval($post['page']) : 1;
    $numPerPage = isset($post['numPerPage']) && $post['numPerPage'] > 0 ? intval($post['numPerPage']) : 20;

    $funds = \Speed\Trade\Component\Balance::FactoryCurrency($currency)
            ->setEntityManager($entityManager)
            ->search($conditions, $page, $numPerPage);

    $result = $result = array("err" => 200, "data" => $funds);
} elseif ($action == "FundFrom.List") {
    $post = json_decode($json, true);
    $options = array();
    if (isset($post['user_type'])) {
        $options = Speed\Trade\Helper\BalanceConstant::getFundFromList($post['user_type']);
    }

    $result = array("err" => 200, "data" => $options);
} elseif ($action == "Pool.Search") {
    $post = json_decode($json, true);
    $conditions = $post['conditions'];
    $page = isset($post['page']) && intval($post['page']) > 0 ? intval($post['page']) : 1;
    $numPerPage = isset($post['numPerPage']) && $post['numPerPage'] > 0 ? intval($post['numPerPage']) : 20;
    $data = \Speed\Trade\Component\Pool::getInstance()->setEntityManager($entityManager)->search($conditions, $page, $numPerPage);
    $result = $result = array("err" => 200, "data" => $data);
}

echo json_encode($result);
exit;


