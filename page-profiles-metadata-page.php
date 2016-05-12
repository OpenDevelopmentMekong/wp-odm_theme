<div class="container">
    <div class="row no-margin-top">
      <div class="twelve columns profiles_page profile-metadata">
        <h2 class="align-left h2_name"><?php _e("Metadata:", "opendev"); the_title() ?></h2>
        <div class="clear"></div>
        <?php
        /*$showing_fields = array(
                          "title_translated" => "Title",
                          "notes_translated" => "Description",
                          "odm_source" => "Source(s)",
                          "odm_date_created" => "Date of data",
                          "odm_completeness" => "Completeness",
                          "license_id" => "License"
                      );*/
        get_metadata_info_of_dataset_by_id(CKAN_DOMAIN,$metadata_dataset, 0, $showing_fields);
       ?>
      </div>
    </div>
</div>
