<?php

require_once dirname(dirname(__FILE__)) . '/inc/utils/urls.php';

class UrlsTest extends PHPUnit_Framework_TestCase
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

		public function testCategoryNameToSlug()
    {
      $name = "Economy and Commerce";
      $slug = category_name_to_slug($name);
      $this->assertEquals($slug,"economy-and-commerce");
    }
    
    public function testCategoryNameToSlug2()
    {
      $name = "Airports and air travel";
      $slug = category_name_to_slug($name);
      $this->assertEquals($slug,"airports-and-air-travel");
    }

}
