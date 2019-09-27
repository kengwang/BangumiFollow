<?php
include_once 'config.php';
/**
 * Bangumi追番API
 * @author Kengwang
 * @copyright Kengwang
 */


class BGMFL
{
    static function curl($url, $cookie)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url); //地址
        //curl_setopt ($curl, CURLOPT_COOKIEJAR, $cookiefile);//文件式
        //curl_setopt ($curl, CURLOPT_COOKIEFILE, $cookiefile);
        curl_setopt($curl, CURLOPT_COOKIE, $cookie);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $str = curl_exec($curl);
        curl_close($curl);
        return $str;
    }
}
class bilibili
{
    private static function getUserInfo($arg)
    {
        //之后记得换成WordPress的函数啊,天啊
        $arr = array(
            'uid' => '341151171', //用户ID
            'cookie' => 'SESSDATA=*******打码233****' //cookie,假如说没有公开就要填写,只需要SESSDATA
        );
        if(isset($arr[$arg])) return $arr[$arg]; else return '';
    }
    public static function getFollowingList()
    {
        /**
         * 关于链接的注释
         * 首先说说已知的参数
         * type :          追逐类型 1为追番 2为追剧 [必须]
         * follow_status : 番剧类别 0为全部 1为想看 2为在看 3为看过
         * ps :            每一页的个数 相当于SQL的Limit
         * pn :            页码,承接上一个参数
         * vmid :          用户ID [必须]
         * 
         * 还有什么参数后期补充哦
         */
        $back = Functions::curl("https://api.bilibili.com/x/space/bangumi/follow/list?type=1&follow_status=0&vmid=" . Config::$bilibili['uid'], Config::$bilibili['cookie']);

        $array = json_decode($back, true);
        return $array;
    }
}
