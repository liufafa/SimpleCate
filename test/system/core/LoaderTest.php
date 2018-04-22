<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of LoaderTest
 *
 * @author Administrator
 */
class LoaderTest extends PHPUnit_Framework_TestCase {

    /**
     * @var \RemoteWebDriver
     */
    protected $object;

    public function setUp() {
        $ci = &get_instance();
        $this->object = $ci;
    }

    public function tearDown() {
        
    }

    public function testmodel() {

        $this->object->load->model('TestModel');
        $res = $this->object->TestModel->test();
        $this->assertEquals('success', $res);
    }

}
