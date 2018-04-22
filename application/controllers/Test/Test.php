<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Test extends Controller {

    public function index() {
        $segments = $this->url->segments;
        $this->load->model('TestModel');
        $res = $this->TestModel->test();
        echo $res;
    }
    //引入文件，写个控制器，判断

}
