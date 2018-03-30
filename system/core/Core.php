<?php

defined('BASEPATH') OR exit('No direct script access allowed');

//引入一个公共方法
require_once(BASEPATH . 'core/Common.php');
//引入一个加载各种模块的辅助类
require_once(BASEPATH . 'core/load.php');
//加载路由方法
require_once(BASEPATH . 'core/router.php');
//根据路由方法找到相应的文件，实例化类，完成文件

//call_user_func_array(array(&$CI, $method), $params);