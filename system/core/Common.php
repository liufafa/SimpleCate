<?php

/**
 * 
 * 全局的核心公共函数
 */
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 读取类文件
 * 
 */
function &load_class($class, $directory = 'libraries', $param = NULL) {
    static $_classes = array();

    //判断是类是否存在，存在即返回
    if (isset($_classes[$class])) {
        return $_classes[$class];
    }

    $findClass = FALSE;
    // 根据类名称，查找相应的类文件是否存在
    if (file_exists(BASEPATH . $directory . '/' . $class . '.php')) {
        //判断类是否存在，存在即引入

        if (class_exists($class, FALSE) === FALSE) {
            require_once(BASEPATH . $directory . '/' . $class . '.php');
        }
        $findClass = TRUE;
    }
    // 文件引入失败就报错
    if ($findClass === FALSE) {

        echo 'Unable to locate the specified class: ' . $class . '.php';
        exit(5);
    }

    is_loaded($class);
    $_classes[$class] = isset($param) ? new $class($param) : new $class();
    return $_classes[$class];
}

/**
 * 获取load_class加载的全部类
 * 
 */
function &is_loaded($class = '') {
    static $_is_loaded = array();

    if ($class !== '') {
        $_is_loaded[strtolower($class)] = $class;
    }

    return $_is_loaded;
}
/**
 * 删除不可见字符
 * @param type $str
 * @param type $url_encoded
 * @return type
 */
function remove_invisible_characters($str, $url_encoded = TRUE) {
    $non_displayables = array();

    // every control character except newline (dec 10),
    // carriage return (dec 13) and horizontal tab (dec 09)
    if ($url_encoded) {
        $non_displayables[] = '/%0[0-8bcef]/i'; // url encoded 00-08, 11, 12, 14, 15
        $non_displayables[] = '/%1[0-9a-f]/i'; // url encoded 16-31
        $non_displayables[] = '/%7f/i'; // url encoded 127
    }

    $non_displayables[] = '/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]+/S'; // 00-08, 11, 12, 14-31, 127

    do {
        $str = preg_replace($non_displayables, '', $str, -1, $count);
    } while ($count);

    return $str;
}
