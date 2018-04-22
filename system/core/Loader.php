<?php

/**
 * 加载函数类
 * 这里用于项目引入各种辅助函数
 */
defined('BASEPATH') OR exit('No direct script access allowed');

//
class Loader {

    /**
     * 已经加载的全部model
     * @var type 
     */
    protected $_ci_models = array();

    /**
     * 加载model文件
     * 
     */
    public function model($model, $name = '', $db_conn = FALSE) {
        if (empty($model)) {
            return $this;
        } elseif (is_array($model)) {
            foreach ($model as $key => $value) {
                is_int($key) ? $this->model($value, '', $db_conn) : $this->model($key, $value, $db_conn);
            }

            return $this;
        }

        $path = '';
        //strrpos — 计算指定字符串在目标字符串中最后一次出现的位置
        //判断该模型是否在子文件夹下，如果是解析文件名和路径。
        if (($last_slash = strrpos($model, '/')) !== FALSE) {
            // 斜杠前面是路径
            $path = substr($model, 0, ++$last_slash);
            // model在后面
            $model = substr($model, $last_slash);
        }

        if (empty($name)) {
            $name = $model;
        }
        //model如果已经初始化就直接返回对象
        if (in_array($name, $this->_ci_models, TRUE)) {
            return $this;
        }

        //判断是否使用数据库模组
        if ($db_conn !== FALSE && !class_exists('CI_DB', FALSE)) {
            if ($db_conn === TRUE) {
                $db_conn = '';
            }

            $this->database($db_conn, FALSE, TRUE);
        }
        //引入核心model类（方面model中调用超级类中的其他对象）
        if (!class_exists('Model', FALSE)) {
            require_once(BASEPATH . 'core' . DIRECTORY_SEPARATOR . 'Model.php');
        }
        //引入逻辑中要引入的model类
        $model = ucfirst($model);
        if (!class_exists($model, FALSE)) {

            if (file_exists(APPPATH . 'models/' . $path . $model . '.php')) {
                require_once(APPPATH . 'models/' . $path . $model . '.php');
            }

            if (!class_exists($model, FALSE)) {
                throw new RuntimeException(APPPATH . "models/" . $path . $model . ".php exists, but doesn't declare class " . $model);
            }
         
        }
        $CI =& get_instance();
        $this->_ci_models[] = $name;
        print_r($name);
        $CI->$name = new $model();
        return $this;
    }
    /**
     * 数据库模组（待完成）
     * @param type $params
     * @param type $return
     * @param type $query_builder
     * @return boolean|$this
     */
    public function database($params = '', $return = FALSE, $query_builder = NULL) {
        // Grab the super object
        $CI = & get_instance();

        // 判断是加载数据类库?
        if ($return === FALSE && $query_builder === NULL && isset($CI->db) && is_object($CI->db) && !empty($CI->db->conn_id)) {
            return FALSE;
        }

        require_once(BASEPATH . 'database/DB.php');

        if ($return === TRUE) {
            return DB($params, $query_builder);
        }

        // Initialize the db variable. Needed to prevent
        // reference errors with some configurations
        $CI->db = '';

        // Load the DB class
        $CI->db = & DB($params, $query_builder);
        return $this;
    }

    /**
     * 加载辅助函数
     */
    public function libraries() {
        
    }

}
