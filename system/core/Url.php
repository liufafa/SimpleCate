<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 获取处理url
 * 形成url数组
 * 
 */
class Url {

    /**
     * 最后有效url数组
     * 
     */
    public $rsegments = array();

    /**
     * 当前url链接
     *
     * @var	string
     */
    public $uri_string = '';

    /**
     *
     * 全部url数组
     */
    public $segments = array();

    /**
     * 初始化
     * @param type $routing
     */
    public function __construct() {
        //获取url
        $uri = $this->_get_url();
        $this->_set_uri_string($uri);
    }

    /**
     * 获取url
     * @return string
     */
    protected function _get_url() {
        if (!isset($_SERVER['REQUEST_URI'], $_SERVER['SCRIPT_NAME'])) {
            return '';
        }
        // parse_url() 返回请求连接的各部分数据 
        // 例如：http://www.test.com///Test///test/index?cd=1&b=2///Test///test/index?cd=1&b=2 返回的数据：Array ( [scheme] => http [host] => dummy [path] => ///Test///test/index [query] => cd=1&b=2 ) 
        //路径和查询字段及冒号后面跟一个数字 例外
        $uri = parse_url('http://dummy' . $_SERVER['REQUEST_URI']);
     
        $query = isset($uri['query']) ? $uri['query'] : '';
        $uri = isset($uri['path']) ? $uri['path'] : '';

        //这部分可以确保服务器在查询字符串中URI(Nginx)正确(?)
        //如果url出现这样的情况(例如：/?/11?11),调整查询字符串和$_GET为一个数组.(这里主要是处理url)
        if (trim($uri, '/') === '' && strncmp($query, '/', 1) === 0) {
            $query = explode('?', $query, 2);
            $uri = $query[0];
            $_SERVER['QUERY_STRING'] = isset($query[1]) ? $query[1] : '';
        } else {
            $_SERVER['QUERY_STRING'] = $query;
        }
        //修改$_GET
        parse_str($_SERVER['QUERY_STRING'], $_GET);

        if ($uri === '/' OR $uri === '') {
            return '/';
        }

        // 做最后清理返回结果
        return $this->_remove_relative_directory($uri);
    }

    /**
     * 
     * @param type $uri
     * @return type
     */
    protected function _remove_relative_directory($uri) {
        $uris = array();
        $url_array = explode('/', $uri);
        foreach ($url_array as $key => $tok) {
            if ((!empty($tok) OR $tok === '0') && $tok !== '..') {
                $uris[] = $tok;
            }
        }
        return implode('/', $uris);
    }

    /**
     * 保存url字符到数组segments
     * @param type $str
     */
    protected function _set_uri_string($str) {
        // 过滤控制字符和斜杠
        $this->uri_string = trim(remove_invisible_characters($str, FALSE), '/');

        if ($this->uri_string !== '') {
            //删除url后缀

            $this->segments[0] = NULL;
            // 将url连接转为数组

            foreach (explode('/', trim($this->uri_string, '/')) as $val) {
                // 判断url数值是否合法
                $this->filter_uri($val);
                if ($val !== '') {
                    $this->segments[] = $val;
                }
            }
            unset($this->segments[0]);
        }
    }

    /**
     * url过滤
     * @param type $str
     */
    public function filter_uri(&$str) {
        //只要字符串里面的存在非[a-z 0-9~%.:_\- ] 的就非法
        if (!empty($str) && !preg_match('/^[a-z 0-9~%.:_\- ]+$/iu', $str)) {
            exit('The URI you submitted has disallowed characters.');
        }
    }

}
