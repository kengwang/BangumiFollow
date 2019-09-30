<?php
/*
Plugin Name: BangumiFollow 追番界面
Plugin URI: https://blog.tysv.top/archives/377
Description:  一个简约而不简单的追番页面
Version: 1.0b
Author: Kengwang
Author URI: http://blog.tysv.top
*/
if(is_file('apis.php')) include 'apis.php'; else die("Plugin \"BangumiFollow\" lost some of its file, Please try to reinstall");
//一些钩子
if (function_exists("BgmFL\\WordPressFun"));

register_activation_hook();
register_deactivation_hook();


?>
