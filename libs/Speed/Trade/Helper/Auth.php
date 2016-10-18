<?php
namespace Speed\Trade\Helper;

class Auth{
  public static function AllowRemote($ip){
    $white_list_ips = array('127.0.0.1','::1');
    
    return in_array($ip, $white_list_ips) ? true : false;
  }
}