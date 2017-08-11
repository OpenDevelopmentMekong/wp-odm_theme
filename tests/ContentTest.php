<?php

require_once dirname(dirname(__FILE__)) . '/inc/utils/content.php';

class ContentTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        // init vars here
    }

    public function tearDown()
    {
        // undo stuff here
    }

		public function testLimitStringLengthNoNeed()
    {
      $string = "Economy and Commerce";
      $limited = shorten_string_words($string);
      $this->assertEquals($limited,"Economy and Commerce");
    }

    public function testLimitStringLengthLimitedEnglishDefaultLimit()
    {
      $string = "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.";
      $limited = shorten_string_words($string);
      $this->assertEquals($limited,"Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a");
    }

		public function testLimitStringLengthLimitedEnglishSpecifiedLimit()
    {
      $string = "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.";
      $limited = shorten_string_words($string,12);
      $this->assertEquals($limited,"Lorem Ipsum is simply dummy text of the printing and typesetting industry.");
    }

		public function testLimitStringLengthLimitedKhmerSpecifiedLimit()
    {
      $string = "លោកនាយក​រដ្ឋ​មន្ត្រី ហ៊ុន សែន បាន​វិល​ត្រឡប់​មក​ដល់​ប្រទេស​កម្ពុជា​វិញ កាល​ពី​រសៀល​ថ្ងៃ​ម្សិលមិញ ជា​មួយ​នឹង​សុខភាព​យ៉ាង​ល្អ​ប្រសើរ បន្ទាប់​ពី​លោក​បាន​​ចេញ​ទៅ​ព្យាបាល​ជំងឺ​ជា​បន្ទាន់​ នៅ​ប្រទេស​សិង្ហ​បុរី​ កាល​ពី​រសៀល​ថ្ងៃ​ទី";
      $limited = shorten_string_words($string,5,"km");
      $this->assertEquals($limited,"លោកនាយក​រដ្ឋ​មន្ត្រី ហ៊ុន សែន បាន​វិល​ត្រឡប់​មក​ដល់​ប្រទេស​កម្ពុជា​វិញ កាល​ពី​រសៀល​ថ្ងៃ​ម្សិលមិញ");
    }

}
