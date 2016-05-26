<?php

require_once dirname(dirname(__FILE__)) . '/inc/datastore_api.php';

class DatastoreApiTest extends PHPUnit_Framework_TestCase
{


    public function setUp()
    {
        // init vars here
    }

    public function tearDown()
    {
        // undo stuff here
    }

    public function testIncorrectCkanDomain()
    {
        $results = get_datastore_resource('incorrect_domain','some_resource_id');
        $this->assertEmpty($results);
    }

    public function testIncorrectResourceIdDomain()
    {
        $results = get_datastore_resource('https://data.opendevelopmentmekong.net','some_resource_id');
        $this->assertEmpty($results);
    }

    public function testCorrectConfig()
    {
        $results = get_datastore_resource('https://data.opendevelopmentmekong.net','3b817bce-9823-493b-8429-e5233ba3bd87');
        $this->assertFalse(empty($results));
    }
}
