<?php
namespace BgmFL;
include_once 'config.php';
/**
 * Bangumi追番API
 * @author Kengwang
 * @copyright Kengwang
 */



class Functions{
    static function curl($url,$cookie){
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);//地址
        //curl_setopt ($curl, CURLOPT_COOKIEJAR, $cookiefile);//文件式
        //curl_setopt ($curl, CURLOPT_COOKIEFILE, $cookiefile);
        curl_setopt($curl, CURLOPT_COOKIE, $cookie);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $str = curl_exec($curl);
        curl_close($curl);
        return $str;
    }
}

class bilibili{
    public static function GetFollowingList(){
        $back=Functions::curl("https://api.bilibili.com/x/space/bangumi/follow/list?type=1&follow_status=0&pn=1&ps=15&vmid=".Config::$bilibili['uid'],Config::$bilibili['cookie']);
        $array=json_decode($back,true);
        return $array;
    }
}