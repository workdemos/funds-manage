<?php

require_once __DIR__ . '/../src/bootstrap.php';
use Speed\Trade\Helper\BalanceConstant;

$data = array("user_id"=>10082,
                       "user_type"=>  BalanceConstant::USER_BUYER,
                       "amount"=>90,
                       "order_id"=>10245,
                       "currency"=>  BalanceConstant::CURRENCY_JPY,
                      "rate"=>17.8,
                      "type"=>  BalanceConstant::BALANCE_OUT,
                       "status"=>"doing",
                      "balanced"=>1);

$data_string = json_encode($data);


$ch = curl_init('http://localhost/fund/web/api.php?service=pool.add');
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
  'Content-Type: application/json',
  'Content-Lenght:' . strlen($data_string)
));


$res = curl_exec($ch);

var_dump($res);
