<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Controller {

    /**
     * 
     *
     * 定义一个用于存放对象的静态变量
     */
    private static $instance;

    /**
     * 初始化类
     *
     * @return	void
     */
    public function __construct() {
        self::$instance = & $this;
        //根据已经就加载的类，全部实例化
        foreach (is_loaded() as $var => $class) {
            $this->$var = & load_class($class);
        }
        //启用一个加载类
        $this->load =& load_class('Loader', 'core');
    }

    // --------------------------------------------------------------------

    /**
     * 获取初始化类
     *
     * @static
     * @return	object
     */
    public static function &get_instance() {
        return self::$instance;
    }

}
