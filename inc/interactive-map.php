<?php

/*
 * Open Development
 * Interactive Map
 */

class OpenDev_InteractiveMap
{
    public function __construct()
    {
        add_shortcode('odmap', array($this, 'shortcode'));
    }

    public function shortcode()
    {
        $layer_query = new WP_Query(array(
   'post_type' => 'map-layer',
   'posts_per_page' => -1,
  ));

        $layers = array();

        $categories = get_terms('layer-category');

        $parsed_cats = array();

        if ($layer_query->have_posts()) {
            while ($layer_query->have_posts()) {
                $layer_query->the_post();
                $layer = array();
                $layer['filtering'] = 'switch';
                $layer['hidden'] = 1;
                foreach ($categories as $key => $val) {
                    $cat = $categories[$key];
                    if (is_object_in_term(get_the_ID(), 'layer-category', $cat->term_id)) {
                        if (!isset($parsed_cats[$cat->term_id])) {
                            $parsed_cats[$cat->term_id] = array();
                        }
                        $parsed_cats[$cat->term_id][] = get_the_ID();
                        $parsed_cats[$cat->term_id]['order'] = $key;
                    }
                }

                if (function_exists('extended_jeo_get_layer')) {
                    $layer = array_merge($layer, extended_jeo_get_layer(get_the_ID()));
                } else {
                    $layer = array_merge($layer, jeo_get_layer(get_the_ID()));
                }

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
        if ($map['base_layer']) {
            array_unshift($map['layers'], array(
    'type' => 'tilelayer',
    'tile_url' => $map['base_layer']['url'],
   ));
        }

        ob_start();
        ?>
  <div class="interactive-map">
   <div class="map-container">
    <div id="map_interactive_map_0" class="map">
     <!-- <div class="layer-toggle active">
       <a class="active"></a>
     </div> -->
    </div>
   </div>
 </div>
   <div class="interactive-map-layers">
    <ul class="categories">
     <?php
      $categories = get_categories('taxonomy=layer-category');
        foreach ($categories as $category) {
            ?>

        <li draggable="true" data-category="<?php echo $category->cat_ID ?>" class="<?php echo 'cat-item cat-item-'.$category->cat_ID ?> cat-<?php echo $category->slug?>">
          <span class="category-color">&nbsp;</span>
          <a href="#"><?php echo $category->cat_name;
            ?></a>

        </li>
     <?php
        }
        ?>
    </ul>
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

    var $layer_toggles;
    var term_rel = <?php echo json_encode($parsed_cats);
        ?>;
    var jeo_map;
    jeo(jeo.parseConf(<?php echo json_encode($map);
        ?>));

    function enableAllLayers() {
     console.log("enableAllLayer");
     // this/e.target is the source node.

     $layer_toggles.each(function(){
      $(this).addClass('active');
      $(this).parent().find('.layer-status').addClass('active');
     });

     for(var key in term_rel) {
      $.each(term_rel[key], function(i, layerId) {
       jeo_map.filterLayers._enableLayer(layerId);
      });
     }

     jeo_map.filterLayers._update();

    }

    function disableAllLayers() {
     console.log("disableAllLayers");
     // this/e.target is the source node.

     $layer_toggles.each(function(){
      $(this).removeClass('active');
      $(this).parent().find('.layer-status').removeClass('active');
     });

     for(var key in term_rel) {
      $.each(term_rel[key], function(i, layerId) {
       jeo_map.filterLayers._disableLayer(layerId);
      });
     }

     jeo_map.filterLayers._update();

    }

    jeo.mapReady(function(map) {

       jeo_map = map;

       var $layers = $('.interactive-map-layers');
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

       $layer_toggles = $layers.find('.cat-layers h2');
       $layer_toggles.each(function(){
        var $layer_toggle = $(this);
        $layer_toggle.on('click', function() {
         map.filterLayers._switchLayer($(this).parent().data('layer'));
         if(map.filterLayers._getStatus($(this).parent().data('layer')).on) {
          $(this).addClass('active');
          $(this).parent().find('.layer-status').addClass('active');
         } else {
          $(this).removeClass('active');
          $(this).parent().find('.layer-status').removeClass('active');
         }
       })
       });

       ;

      $layers.find('.layer-item .toggles .toggle-text').on('click', function() {
       if($(this).html() == "More"){
        //$(this).addClass('active');
        $(this).parent().parent().find('.layer-excerpt').hide();
        $(this).parent().parent().find('.layer-content').show();
        $(this).html('Less');
       } else {
        //$(this).removeClass('active');
        $(this).parent().parent().find('.layer-excerpt').show();
        $(this).parent().parent().find('.layer-content').hide();
        $(this).html('More');
       }
     });

     $layers.find('.layer-item .toggles .toggle-legend').on('click', function() {
       if($(this).html() == "Show legend"){
        //$(this).addClass('active');
        $(this).parent().parent().find('.legend').show();
        $(this).html('Hide legend');
       } else {
        //$(this).removeClass('active');
        $(this).parent().parent().find('.legend').hide();
        $(this).html('Show legend');
       }
     });

     $layers.find('.layer-item .toggles .download-url').on('click', function() {
      window.open(
       $(this).attr('href'),
       '_blank' // <- This is what makes it open in a new window.
      );


     });

     /*var $category_toggles = $('.map .layer-toggle')
     $category_toggles.find('a').on('click', function() {
      if($(this).hasClass('active')){
       disableAllLayers();
       $(this).removeClass('active');
      } else {
       enableAllLayers();
       $(this).addClass('active');
      }
    });*/

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
