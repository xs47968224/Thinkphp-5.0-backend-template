基于 ThinkPHP 5.0 RC4 创建快速开发模板


===============
 + 1.本地最好vhost和host配置一下，数据库名请创建成thinkphp,否则自己改一下database.php文件
 + 2.前台首页我没配置，所以请直接访问admin模块
 + 3.后台访问: 项目名/admin/login
 + 4.因为有权限验证，所以也可以是：项目名/admin/

---------------

 + 后台帐号: admin
 + 后台密码: kevin

---------------


版本 1.1.3 （2016-08-14）
 + 1. 后台编缉文章内容输入框整合ckeditor ckfinder
 + 2. 注：整个后台模块基础模块到这里基本上该有的都有了，接下来除了一些特殊情况，不会再添加新的功能或模块，应该最多就花点时间修复一些bug
 + 3. 路由写法你要是觉得写的不好，可以自己改，也欢迎大家共享更好的代码，858785716@qq.com
 + 4. 打个小广告：基于这套模版快速做了个博客(当然也是我自己的博客)： (http://returnc.com)，不过此站上前台代码不供享了，可以自己做自己喜欢的站。谢谢大家，就酱

版本 1.1.2 （2016-08-11）
 + 1.恢复 application 目录名，因为根目中think文件也定义了APP_PATH, 但官方学习手册中没说，按照手册改application目录名会造成php think命令无法使用，故恢复目录名，当然还可以修改think文件中的APP_PATH定义，但是TP5 RC4一直在更新，所以不推荐修改，以免自己挖坑

版本 1.1.1 （2016-08-10）
 + 1.修改后台首页几个假数据调用
 + 2.增加文章列表搜索条件

---------------

版本 1.1（2016-08-9）
 + 1.后台新增文章模块，由于管理员模块还是有点特殊性，有些代码示例不到位，所以新增一个文章模块
 + 2.对custom-field.php进行补充及完善
 + 3.修复了datepicker选择框错位的问题
 + 4.TODO：ckeditor,ckfinder 将下个版本补充，今天七夕，陪女朋友吃晚饭~

---------------

版本 1.0 （2016-07-26）
 + 1.已完成后台模板样式
 + 2.后台管理员模块
 + 3.后台权限管理
 + 4.表单字段view模板（只需在控制器中写好字段数组，view中直接include custom-field.html,参考administrator模块）

---------------


关于ThinkPHP 5.0 RC4
===============

[![Downloads](https://img.shields.io/github/downloads/top-think/think/total.svg)](https://github.com/top-think/think/releases)
[![Releases](https://img.shields.io/github/release/top-think/think.svg)](https://github.com/top-think/think/releases/latest)
[![Releases Downloads](https://img.shields.io/github/downloads/top-think/think/latest/total.svg)](https://github.com/top-think/think/releases/latest)
[![Packagist Status](https://img.shields.io/packagist/v/top-think/think.svg)](https://packagist.org/packages/topthink/think)
[![Packagist Downloads](https://img.shields.io/packagist/dt/top-think/think.svg)](https://packagist.org/packages/topthink/think)

