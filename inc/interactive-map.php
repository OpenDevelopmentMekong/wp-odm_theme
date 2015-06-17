<?php

/*
 * Open Development
 * Interactive Map
 */

class OpenDev_InteractiveMap {

 function __construct() {

  add_shortcode('odmap', array($this, 'shortcode'));

 }

 function shortcode() {

  $layer_query = new WP_Query(array(
   'post_type' => 'map-layer',
   'posts_per_page' -1
  ));

  $layers = array();

  $categories = get_terms('layer-category');

  $parsed_cats = array();

  if($layer_query->have_posts()) {
   while($layer_query->have_posts()) {
    $layer_query->the_post();
    $layer = array();
    $layer['filtering'] = 'switch';
    $layer['hidden'] = 1;
    foreach($categories as $key=>$val) {
    $cat = $categories[$key];
     if(is_object_in_term(get_the_ID(), 'layer-category', $cat->term_id)) {
      if(!isset($parsed_cats[$cat->term_id]))
       $parsed_cats[$cat->term_id] = array();
      $parsed_cats[$cat->term_id][] = get_the_ID();
      $parsed_cats[$cat->term_id]['order'] = $key;
     }
    }
    $layer = array_merge($layer, jeo_get_layer(get_the_ID()));
    $layers[] = $layer;
    wp_reset_postdata();
   }
  }

  $map = opendev_get_interactive_map_data();
  $map['dataReady'] = true;
  $map['postID'] = 'interactive_map';
  $map['layers'] = $layers;
  $map['count'] = 0;
  $map['title'] = __('Interactive Map', 'opendev');
  if($map['base_layer']) {
   array_unshift($map['layers'], array(
    'type' => 'tilelayer',
    'tile_url' => $map['base_layer']['url']
   ));
  }

  ob_start();
  ?>
  <div class="interactive-map">
   <div class="map-container">
    <div id="map_interactive_map_0" class="map"></div>

   </div>
   <div class="interactive-map-layers">
    <ul class="categories">
     <?php
      $categories = get_categories('taxonomy=layer-category');
      foreach ($categories as $category){ ?>
       <li draggable="true" data-category="<?php echo $category->cat_ID ?>" class="<?php echo "cat-item cat-item-" . $category->cat_ID ?>"><a href="#"><?php echo $category->cat_name; ?></a></li>
     <?php } ?>
    </ul>
   </div>
  </div>
  <script type="text/javascript">

   (function($) {

    var ENABLE_DND = false;
    var dragSrcEl = null;
    var category_items = document.querySelectorAll('.cat-item');

    function swapLayerCategory(cat1_ID,cat2_ID) {
      var temp_order = term_rel[cat1_ID]['order'];
      term_rel[cat1_ID]['order'] = term_rel[cat2_ID]['order'];
      term_rel[cat2_ID]['order'] = temp_order;
      jeo_map.filterLayers._update();
    }

    function handleDragStart(e) {
      //console.log("handleDragStart");
     // Target (this) element is the source node.
     this.style.cursor = 'move';
     dragSrcEl = this;

     e.dataTransfer.effectAllowed = 'move';
     e.dataTransfer.setData('text/html', this.innerHTML);
    }

    function handleDragOver(e) {
      //console.log("handleDragOver");
     if (e.preventDefault) {
       e.preventDefault(); // Necessary. Allows us to drop.
     }

     e.dataTransfer.dropEffect = 'move';  // See the section on the DataTransfer object.

     return false;
    }

    function handleDragEnter(e) {
      //console.log("handleDragEnter");
     // this / e.target is the current hover target.
     this.classList.add('over');
    }

    function handleDragLeave(e) {
      //console.log("handleDragLeave");
     this.classList.remove('over');
    }

    function handleDrop(e) {
     //console.log("handleDrop");

     if (e.stopImmediatePropagation) {
       e.stopImmediatePropagation(); // Stops some browsers from redirecting.
     }

     // Don't do anything if dropping the same item we're dragging.
     if (dragSrcEl != this) {
       // Set the source item's HTML to the HTML of the item we dropped on.
       dragSrcEl.innerHTML = this.innerHTML;
       this.innerHTML = e.dataTransfer.getData('text/html');
       swapLayerCategory($(this).data('category'),$(dragSrcEl).data('category'));
     }

     return false;
    }

    function handleDragEnd(e) {
     console.log("handleDragEnd");
     // this/e.target is the source node.

     [].forEach.call(category_items, function (item) {
       item.classList.remove('over');
     });
    }

    var term_rel = <?php echo json_encode($parsed_cats); ?>;
    var jeo_map;
    jeo(jeo.parseConf(<?php echo json_encode($map); ?>));

    jeo.mapReady(function(map) {

       jeo_map = map;

       var $layers = $('.interactive-map .interactive-map-layers');
       if(map.postID == 'interactive_map') {
        //map.$.find('.jeo-filter-layers').appendTo($layers);
        for(var key in term_rel) {
         var $item = $layers.find('.cat-item-' + key);
         $item.find(' > a').after($('<ul class="cat-layers switch-layers" />'));
         $.each(term_rel[key], function(i, layerId) {
          var $layer = map.$.find('[data-layer="' + layerId + '"]');
          $layer.appendTo($item.find('.cat-layers'));
         });
        }
        //$('.jeo-filter-layers').hide();
       }

       $layers.find('.categories ul').hide();

       $layers.find('.categories li a').on('click', function() {
        if($(this).hasClass('active')) {
         $(this).removeClass('active');
         $(this).parent().find('ul').hide();
        } else {
         $(this).addClass('active');
         $(this).parent().find('> ul').show();
        }
        return false;
       });

       $layers.find('.cat-layers li').on('click', function() {
        map.filterLayers._switchLayer($(this).data('layer'));
        if(map.filterLayers._getStatus($(this).data('layer')).on) {
         $(this).addClass('active');
        } else {
         $(this).removeClass('active');
        }

     });

     if (ENABLE_DND){
       [].forEach.call(category_items, function(item) {
        item.addEventListener('dragstart', handleDragStart, false);
        item.addEventListener('dragenter', handleDragEnter, false);
        item.addEventListener('dragover', handleDragOver, false);
        item.addEventListener('dragleave', handleDragLeave, false);
        item.addEventListener('drop', handleDrop, false);
        item.addEventListener('dragend', handleDragEnd, false);
       });
     }

    });

   })(jQuery);
  </script>
  <?php
  $html = ob_get_clean();
  return $html;
 }

}

new OpenDev_InteractiveMap();
