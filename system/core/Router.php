<?php

/**
 * 
 * 根据url数组，判断文件路径
 * 将访问的类及方法保存在属性里面
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Router {

    /**
     * 获取路口中的方法
     * 
     */
    public $method = 'index';

    /**
     * 获取路口中的类
     * 
     */
    public $class = 'Home';

    /**
     * 路径
     * 
     */
    public $directory;

    /**
     * 初始化
     * @param type $routing
     */
    public function __construct() {
        $this->uri = & load_class('Url', 'core');
        $this->_set_request(array_values($this->uri->segments));
    }

    /**
     * 根据url数组设置类与方法
     * @param type $segments
     * @return type
     */
    protected function _set_request($segments = array()) {
        $segments = $this->_validate_request($segments);
        if (empty($segments)) {
            return;
        }

        $this->set_class($segments[0]);
        if (isset($segments[1])) {
            $this->set_method($segments[1]);
        } else {
            $segments[1] = 'index';
        }
        $this->uri->rsegments = $segments;
    }

    /**
     * 根据url判断是否为类或者路径
     * 如果是路径则添加到路径中
     * 否则添加到$segments 数组中，用于设置类与方法
     * @retunn array
     */
    protected function _validate_request($segments) {
        $c = count($segments);
        $directory_override = isset($this->directory);
        while ($c-- > 0) {
            $test = $this->directory . ucfirst($segments[0]);
            //如果文件不存在，并且是路径，就添加到相对路径中
            if (!file_exists(APPPATH . 'controllers/' . $test . '.php') && $directory_override === FALSE && is_dir(APPPATH . 'controllers/' . $this->directory . $segments[0])) {
                $this->set_directory(array_shift($segments), TRUE);
                continue;
            }
            return $segments;
        }

        return $segments;
    }

    /**
     * 设置类数组
     *
     * @return	void
     */
    public function set_class($class) {
        $this->class = str_replace(array('/', '.'), '', $class);
    }

    // --------------------------------------------------------------------

    /**
     *
     * 获取类属性
     * @return	string
     */
    public function fetch_class() {
        return $this->class;
    }

    // --------------------------------------------------------------------

    /**
     * 设置方法属性
     *
     * @return	void
     */
    public function set_method($method) {
        $this->method = $method;
    }

    // --------------------------------------------------------------------

    /**
     * 获取方法属性
     *
     * @return	string
     */
    public function fetch_method() {
        return $this->method;
    }

    // --------------------------------------------------------------------

    /**
     * 设置路径
     *
     * @return	void
     */
    public function set_directory($dir, $append = FALSE) {
        //如果判断路径是否已经存在，添加到路径
        if ($append !== TRUE OR empty($this->directory)) {
            $this->directory = str_replace('.', '', trim($dir, '/')) . '/';
        } else {
            $this->directory .= str_replace('.', '', trim($dir, '/')) . '/';
        }
    }

}
