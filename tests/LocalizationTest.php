<?php

require_once dirname(dirname(__FILE__)) . '/inc/utils/localization.php';

class LocalizationTest extends PHPUnit_Framework_TestCase
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

		public function testGetMultilingualValueOrFallback_es()
    {
      $multilingual = array("en" => "hello","de" => "hallo", "es" => "hola");
      $value = getMultilingualValueOrFallback($multilingual,"es","fallback");
      $this->assertEquals($value,"hola");
    }

		public function testGetMultilingualValueOrFallback_fallback()
    {
      $multilingual = array("en" => "hello","de" => "hallo", "es" => "hola");
      $value = getMultilingualValueOrFallback($multilingual,"fr","fallback");
      $this->assertEquals($value,"fallback");
    }
}
