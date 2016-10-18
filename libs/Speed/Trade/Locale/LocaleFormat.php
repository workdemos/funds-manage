<?php
namespace Speed\Trade\Locale;
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of LocaleFormat
 *
 * @author max
 */
class LocaleFormat {
    static  function DateFromUnixTime($unix_time){
        if($unix_time >0){
            return  strftime("%Y-%m-%d %H:%M:%S", $unix_time);
        }
       
        return "";
    }
}
