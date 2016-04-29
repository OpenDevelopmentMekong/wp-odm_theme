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
          <h2><?php echo $profile["developer"]; ?></h2>
          <table id="profile" class="data-table">
            <tbody>
              <?php
              foreach ($DATASET_ATTRIBUTE as $key => $value): ?>
              <tr>
              <td class="row-key"><?php _e( $DATASET_ATTRIBUTE[$key], "opendev" ); ?></td>
                <td><?php
                    echo $profile[$key] == ""? __("Not found", "opendev"): str_replace(";", "<br/>", $profile[$key]);
                    if(in_array($key, array("data_class", "adjustment_classification", "adjustment")))
                      data_classification_definition( $profile[$key]);
                ?>
                </td>
              </tr>
              <?php endforeach; ?>
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
            <table id="reference" class="data-table">
              <tbody>
                <?php
                  foreach ($ref_docs as $key => $ref_doc):
                    $ref_doc_metadata = get_datasets_filter(CKAN_DOMAIN,"extras_odm_reference_document",$ref_doc);
                    if (count($ref_doc_metadata) > 0):
                      foreach ($ref_doc_metadata as $key => $metadata):
                  ?>
                  <tr>
                    <td class="row-key">
                      <a target="_blank" href="<?php echo CKAN_DOMAIN . "/dataset/" . $metadata["name"] ?>"><?php echo getMultilingualValueOrFallback($metadata['title_translated'],$lang) ?></a></br>
                      <div class="ref_date">
                        <?php if ($metadata["type"]=="laws_record" && !(IsNullOrEmptyString($metadata["odm_promulgation_date"]))): ?>
                          <?php   if(CURRENT_LANGUAGE == "kh" || CURRENT_LANGUAGE == "km")
                                      echo convert_date_to_kh_date(date("d/m/Y", strtotime($metadata["odm_promulgation_date"])), "/");
                                  else echo "(" . $metadata["odm_promulgation_date"] . ")" ?>
                        <?php elseif ($metadata["type"]=="library_records" && !(IsNullOrEmptyString($metadata["odm_publication_date"]))):  ?>
                          <?php   if(CURRENT_LANGUAGE == "kh" || CURRENT_LANGUAGE == "km")
                                      echo convert_date_to_kh_date(date("d/m/Y", strtotime($metadata["odm_publication_date"])), "/");
                                  else echo "(" . $metadata["odm_publication_date"] . ")" ?>
                        <?php endif; ?>
                      </div>
                    </td>
                    <td><?php echo getMultilingualValueOrFallback($metadata['notes_translated'],$lang); ?></td>
                  </tr>
                  <?php
                     endforeach;
                    endif;
                 endforeach;
                ?>
              </tbody>
            </table>
          </div>
        </div>
      <?php endif; ?>
    </div>
  </div>
