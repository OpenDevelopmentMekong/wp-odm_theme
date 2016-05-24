<div class="container">
    <div class="row">
      <div class="twelve columns">
        <div id="profiles_map" class="profiles_map"></div>
      </div>
    </div>
    <div class="row">
      <div class="twelve columns">
        <div id="profile-map-id" class="hidden"><?php echo $filter_map_id; ?></div>
        <div class="profile-metadata">
          <?php  if ($profile["developer"]!="")
                    echo '<h2 class="h2_name">'.$profile["developer"].'</h2>';
                 else if ($profile["name"]!="")
                    echo '<h2 class="h2_name">'.$profile["name"].'</h2>';
                 else if ($profile["block"]!="")
                    echo '<h2 class="h2_name">'.$profile["block"].'</h2>';
         ?>
          <table id="profile" class="data-table">
            <tbody>
              <?php
              foreach ($DATASET_ATTRIBUTE as $key => $value):
                if($key !="reference"){
              ?>
              <tr>
              <td class="row-key"><?php _e( $DATASET_ATTRIBUTE[$key], "opendev" ); ?></td>
                <td><?php
                    $profile_val = str_replace("T00:00:00", "", $profile[$key]);
                    if(CURRENT_LANGUAGE =="km"){
                      if (is_numeric($profile_val)) {
                        $profile_value = convert_to_kh_number(str_replace(".00", "", number_format($profile_val, 2, '.', ',')));
                      }else {
                        $profile_value = $profile_val;
                      }
                    }else {
                      if (is_numeric($profile_val)) {
                        $profile_value = str_replace(".00", "", number_format($profile_val, 2, '.', ','));
                      }else {
                        $profile_value = $profile_val;
                      }
                    }

                    echo $profile_value == ""? __("Not found", "opendev"): str_replace(";", "<br/>", $profile_value);
                    
                    if(in_array($key, array("data_class", "adjustment_classification", "adjustment")))
                      data_classification_definition( $profile[$key]);
                ?>
                </td>
              </tr>
              <?php }
              endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="eight columns">
        <?php if (count($ammendements) > 0): ?>
          <div class="profile-metadata">
            <h2><?php _e("Amendments", "opendev"); ?></h2>
            <table id="tracking" class="data-table">
              <tbody>
                <thead>
                  <!--<tr>
                    <?php /* foreach ($DATASET_ATTRIBUTE_TRACKING as $key => $value): ?>
                      <td class="row-key"><?php _e( $DATASET_ATTRIBUTE_TRACKING[$key], 'opendev'); ?></td>
                    <?php endforeach; */ ?>
                  </tr>-->
                </thead>
                <?php
                $concession_or_developer = '';
                foreach ($ammendements as $key => $ammendement):
                  if (!IsNullOrEmptyString($ammendement["reference"])){
                    array_push($ref_docs_tracking,$ammendement["reference"]);
                  }
                  ?>
                  <tr>
                    <?php foreach ($DATASET_ATTRIBUTE_TRACKING as $key => $value): ?>
                      <?php if ($key == 'concession_or_developer'){
                              if ($ammendement[$key] == $concession_or_developer)
                                  echo "<td></td>";
                              else  {
                                  echo "<td><strong>".__($ammendement[$key], 'opendev')."</strong></td>";
                                  $concession_or_developer = $ammendement[$key];
                              }
                            }else{?>
                              <td>
                                <?php
                                if ($key == 'amendment_date'){
                                    if(CURRENT_LANGUAGE == "kh" || CURRENT_LANGUAGE == "km")
                                      echo convert_date_to_kh_date(date("d/m/Y", strtotime($ammendement[$key])), "/");
                                    else echo $ammendement[$key];
                                }else {
                                  if (isset($ammendement[$key])){
                                      echo $ammendement[$key];
                                    }
                                }
                                ?>
                              </td>
                            <?php } ?>
                    <?php endforeach; ?>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        <?php endif; ?>

        <?php
          $ref_docs_profile = explode(";", $profile["reference"]);
          $ref_docs = array_merge($ref_docs_profile,$ref_docs_tracking);
          if ($ref_docs): ?>
          <div class="profile-metadata">
            <h2><?php _e("Reference documents", "opendev"); ?></h2>
                <?php list_reference_documents($ref_docs)?>
          </div>
        </div>
      <?php endif; ?>
    </div>
  </div>
