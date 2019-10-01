# BangumiFollow
一个WordPress和Typecho的追番页面插件
## 说明
目前只写好了一个[API](apis.php) 以及针对与mkblog主题的一个[模板](Wordpress/TemplateVersion/template-bangumi.php)

插件版暂时没有想法,如何适配那么多的主题,所以我尽力把API注释的很详细方便大家对接

## 使用方法

### 修改信息和Cookie

首先在api.php将第32行左右的信息改成你自己的.

uid:用户id 可以进入[space.bilibili.com](https://space.bilibili.com) 哔哩哔哩会重定向到*https://space.bilibili.com/xxxxx*,后面的那一串数字就是你的uid.

cookie:你的bilibili Cookie中的SESSDATA,请注意保护这一条Cookie!

获取方法:

方法1:使用EditThisCookie等插件查看

![uUTGPH.png](https://s2.ax1x.com/2019/10/01/uUTGPH.png)

方法2: 使用F12控制台

[![uUTqQ1.png](https://s2.ax1x.com/2019/10/01/uUTqQ1.png)](https://imgchr.com/i/uUTqQ1)

1. 选中Network

2. 按F5刷新页面
3. 找到你的用户ID那一条记录,选中

![uUTLsx.png](https://s2.ax1x.com/2019/10/01/uUTLsx.png)

4. 在右侧往下看,点击 Cookie 在里面找到 *SESSDATA* ,他的value内容就是我们要获取的内容了.

FireFox大同小异.

5. 我们要的Cookie就是*SESSDATA=内容*,将其替换到api.php里面的那个Cookie

### 引用API

首先引用API

```php
include_once 'apis.php';
```

然后获取列表

```php
$bgmlist=bilibili::getFormatList();
```

这个的格式就是一个多维数组,可以用foreach去除单独的每一个番剧信息,然后获取内容.

- ssid    : 唯一番剧ID 
- name    : 番剧名称 
- des     : 描述简介
- link    : 链接
- status  : 番剧状态 0为正在播 1为将开 2为完结
- followst: 追番状态(自动判断) 0为未看,1为在看,2为看完 
- basket  : 在B站定的状态,0为想看 1为在看 2为看完
- all     : 总集数 未开为0
- watched : 已观看集数 PV为0
- nowraw  : 已观看集数和时间 (Bilibili直接显示)
- progress: 特色功能,追番进度 n%
- img     : 图片 
- coin    : 硬币
- score   : 分数 未开/未评分番剧是0.0 
- new     : 最新集(array)={title:名字 ep:集数 finish:是否完结撒花}

## 报错和特殊情况

###　Cookie问题

假如Cookie错误只会返回一个番剧,名称为

* Cookie填写错误:Cookie错误

可能是由于Cookie过期等原因,需要重新获取一遍Cookie来让其生效

* Cookie填写错误:Cookie与用户名不匹配

你的Cookie与uid不匹配,可能输错了uid



