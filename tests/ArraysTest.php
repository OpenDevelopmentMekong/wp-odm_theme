<?php

require_once dirname(dirname(__FILE__)) . '/inc/utils/arrays.php';

class ArraysTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        // init vars here
    }

    public function tearDown()
    {
        // undo stuff here
    }

    public function testDummy(){
      $this->assertTrue(true);
    }

		public function testArrayHaveCommonItems()
    {
      $array1 = array("Economy and Commerce", "Disaster and emergency response", "Extractive Industries");
			$array2 = array("Land", "Extractive Industries");
      $result = arrays_have_common_items($array1,$array2);
      $this->assertEquals($result,true);
    }

		public function testArrayHaveCommonItemsIgnoreCase()
    {
      $array1 = array("Economy and Commerce", "Disaster and emergency response", "Extractive Industries");
			$array2 = array("Land", "Extractive industries");
      $result = arrays_have_common_items($array1,$array2);
      $this->assertEquals($result,true);
    }

		public function testArrayHaveCommonItemsNegativeCase()
    {
			$array1 = array("Labor", "Illegal labor");
      $array2 = array("Agriculture and fishing", "Disaster and emergency response", "Environment and natural resources", "Extractive Industries", "Land");
      $result = arrays_have_common_items($array1,$array2);
      $this->assertEquals($result,false);
    }
}
