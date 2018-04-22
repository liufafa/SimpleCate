<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 
 * 这里主要目的用于控制器中的类与model互同
 *
 */
class Model {

    /**
     * 初始化
     *
     * @return	void
     */
    public function __construct() {
        
    }

    // --------------------------------------------------------------------

    /**
     * 魔法方法
     *
     * 直接返回控制器中的类
     * @param	string	$key
     */
    public function __get($key) {
        //返回控制器超级类下面的子类
        return get_instance()->$key;
    }

}
