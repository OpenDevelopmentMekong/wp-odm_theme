<?php
/*
Template Name: More on the land portal
*/
?>
<?php get_header(); ?>

<?php if(have_posts()) : the_post(); ?>

<div class="container">

    <li class="widget">
      <?php
      $current_country = odm_country_manager()->get_current_country();
      $country_codes = odm_country_manager()->get_country_codes()[$current_country];
      if ($current_country == "mekong"):
        $filter = 'VALUES ?country  { <http://data.landportal.info/geo/KHM> <http://data.landportal.info/geo/LAO> <http://data.landportal.info/geo/MMR> <http://data.landportal.info/geo/THA> <http://data.landportal.info/geo/VNM> }';
      else:
        $filter = 'VALUES ?country  { <http://data.landportal.info/geo/' . strtoupper($country_codes["iso3"]) . '>}';
      endif;
      $query = 'SELECT DISTINCT ?llr ?llrLabel WHERE { ?llr a dct:BibliographicResource ; dct:title ?llrLabel ; dct:spatial ?country . ' . $filter .' ?country rdfs:label ?countryLabel OPTIONAL { ?llr dct:issued ?date . } } ORDER BY DESC(?date) LIMIT 10';
      echo do_shortcode("[wpsparql_query_endpoint query='" . $query . "']");
      ?>
    </li>
  <?php endif; ?>

</div>

<?php get_footer(); ?>
