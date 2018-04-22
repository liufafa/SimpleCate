<?php

class Home extends Controller{
    
    public function  index(){
      $this->load->model('TestModel');
      $res=$this->TestModel->test();
      echo $res;
    }
    
}
