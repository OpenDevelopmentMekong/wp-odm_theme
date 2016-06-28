<?php

function buildTopTopicNav($lang)
{
    $navigation_vocab = @file_get_contents(get_stylesheet_directory().'/odm-taxonomy/top_topics/top_topics_multilingual.json');
    if ($navigation_vocab){
      $json_a = json_decode($navigation_vocab, true);

      // get Top Topic Names
      foreach ($json_a as $key => $value) {
          echo '<ul>'.$value['titles'][$lang].'</ul>';
          // get entries
          foreach ($json_a[$key]['children'] as $child) {
              // make wp url from title
            $url = sanitize_title($child['name']);
              echo '<li><a href="topics/'.$url.'">'.$child['titles'][$lang].'</a></li>';
          }
      }
    }

}

function buildStyledTopTopicNav($lang)
{	if ($lang == "kh")
		$lang = "km";

	/*"class":"tooltip" or "multiline-menu-item"  class that can use in json*/
    $navigation_vocab = @file_get_contents(get_stylesheet_directory().'/odm-taxonomy/top_topics/top_topics_multilingual.json');
    if ($navigation_vocab === FALSE || is_null($navigation_vocab)){
      return;
    }
    $json_a = json_decode($navigation_vocab, true);

    // get Top Topic Names
    foreach ($json_a as $key => $value) {
        switch ($key) {
          case 0:
              $icon = 'icon_tree.png';
              $menu = 'menu_environment';
              break;
          case 1:
              $icon = 'icon_industry.png';
              $menu = 'menu_economy';
              break;
          case 2:
              $icon = 'icon_mensch.png';
              $menu = 'menu_people';
              break;
      }
        echo '<li class="first icon_menu '.$menu.'">';
        echo '<a href="#" target="_self">';
        $icon_url = get_stylesheet_directory_uri().'/img/'.$icon;
        echo '<img src="'.$icon_url.'" alt="Top Topic Icon for '.$menu.'">';
        echo '<span class="cNavState"></span></a>';

        echo '<ul class="level2 '.$menu.'">';
        echo '<li class="top-topic">'.$value['titles'][$lang].'</li>';
      // counter
       // get entries -->
       foreach ($json_a[$key]['children'] as $child) {
           $url = sanitize_title($child['name']);
           echo '<li class="'.COUNTRY_NAME.'-bgcolor '. $child['class'] .'"><a href="/topics/'.$url.'">'.$child['titles'][$lang].'</a></li>';
       }

        ?>
        <span class="border"></span>
      </ul><?php

    }
}

 ?>
