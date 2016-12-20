<?php

require_once dirname(dirname(__FILE__)) . '/inc/utils/config.php';

class ConfigUtilsTest extends PHPUnit_Framework_TestCase
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

		public function testParseMappingPairs()
    {
      $mapping_raw = "key1 => value1\r\nkey2 => value2";
      $array = parse_mapping_pairs($mapping_raw);
      $this->assertEquals(count($array),2);
      $this->assertEquals($array["key1"],"value1");
    }

		public function testParseMappingPairs_null()
    {
      $mapping_raw = "key1 => value1\r\nkey2";
      $array = parse_mapping_pairs($mapping_raw);
      $this->assertNull($array);
    }

}
