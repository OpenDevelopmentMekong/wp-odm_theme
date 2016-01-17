<?php

  // CONFIG
  $CKAN_DOMAIN = "https://data.opendevelopmentmekong.net";
  $ELC_RESOURCE_IDS = array(
    "en" => array(
      "metadata" => "3b817bce-9823-493b-8429-e5233ba3bd87",
      "tracking" => "8cc0c651-8131-404e-bbce-7fe6af728f89"
    ),
    "km" => array(
      "metadata" => "a9abd771-40e9-4393-829d-2c1bc588a9a8",
      "tracking" => "7f02292b-e228-4152-86a6-cd5fce929262"
    )
  );

  $ELC_DOWNLOAD_URLS = array(
    "en" => array(
      "csv" => "https://data.opendevelopmentmekong.net/dataset/1cd6c00a-867b-4e90-887b-f47a55fb87a3/resource/3b817bce-9823-493b-8429-e5233ba3bd87/download/elcen.csv",
      "shp" => "https://data.opendevelopmentmekong.net/dataset/1cd6c00a-867b-4e90-887b-f47a55fb87a3/resource/0aa6bd59-7ed9-4d45-bbc4-3393f3976ef4/download/ELCcompletepolygon.zip",
      "geojson" => "https://data.opendevelopmentmekong.net/dataset/1cd6c00a-867b-4e90-887b-f47a55fb87a3/resource/cc59c9bc-4c7a-45a8-b7dd-92741cf2c9c4/download/ELCcompletepolygon.geojson",
      "geojson" => "https://data.opendevelopmentmekong.net/dataset/1cd6c00a-867b-4e90-887b-f47a55fb87a3/resource/e69ff0c3-efb0-404d-a632-8624eb097bdd/download/ELCcompletepoint.geojson",
      "kml" => "https://data.opendevelopmentmekong.net/dataset/1cd6c00a-867b-4e90-887b-f47a55fb87a3/resource/9da83b9c-3e58-4d7f-a08b-dc6a256049ce/download/ELCcompletepolygon.kml",
      "kml" => "https://data.opendevelopmentmekong.net/dataset/1cd6c00a-867b-4e90-887b-f47a55fb87a3/resource/3cdc74c4-398e-4fcb-886e-7b18326fb9b4/download/ELCcompletepoint.kml"
    ),
    "km" => array(
      "csv" => "https://data.opendevelopmentmekong.net/dataset/1cd6c00a-867b-4e90-887b-f47a55fb87a3/resource/a9abd771-40e9-4393-829d-2c1bc588a9a8/download/elckm.csv",
      "shp" => "https://data.opendevelopmentmekong.net/dataset/1cd6c00a-867b-4e90-887b-f47a55fb87a3/resource/0aa6bd59-7ed9-4d45-bbc4-3393f3976ef4/download/ELCcompletepolygon.zip",
      "geojson" => "https://data.opendevelopmentmekong.net/dataset/1cd6c00a-867b-4e90-887b-f47a55fb87a3/resource/cc59c9bc-4c7a-45a8-b7dd-92741cf2c9c4/download/ELCcompletepolygon.geojson",
      "geojson" => "https://data.opendevelopmentmekong.net/dataset/1cd6c00a-867b-4e90-887b-f47a55fb87a3/resource/e69ff0c3-efb0-404d-a632-8624eb097bdd/download/ELCcompletepoint.geojson",
      "kml" => "https://data.opendevelopmentmekong.net/dataset/1cd6c00a-867b-4e90-887b-f47a55fb87a3/resource/9da83b9c-3e58-4d7f-a08b-dc6a256049ce/download/ELCcompletepolygon.kml",
      "kml" => "https://data.opendevelopmentmekong.net/dataset/1cd6c00a-867b-4e90-887b-f47a55fb87a3/resource/3cdc74c4-398e-4fcb-886e-7b18326fb9b4/download/ELCcompletepoint.kml"
    )
  );

  $ELC_METADATA = array(
    "developer" => "Developer",
    "adjustment" => "Adjustment classification",
    "dev_nation" => "Developer country",
    "dev_addres" => "Developer address",
    "intended_p" => "Intended investment",
    "inv_intent" => "Intended crop or project",
    "contract_d" => "Contract term (year)",
    "original_s" => "Granted land area (hectare)",
    "size_refer" => "Source of land size",
    "contractin" => "Contract authority",
    "director" => "Director",
    "director_n" => "Director nationality",
    "contract_0" => "Contract date",
    "sub_decree" => "Sub-decree reclassifying land use",
    "province" => "Province/Capital city",
    "district" => "Granted land area (hectare)/Khan(s)",
    "commune" => "Commune/Sangkat",
    "municipali" => "Town/Municipality",
    "land_conv" => "Previous land use",
    "land_utili" => "Developer land use plan",
    "eia_status" => "IEIA/EIA status"
  );

  $ELC_TRACKING = array(
    "amendment_text" => "Amendment text",
    "change_type" => "Change type",
    "concession_or_developer" => "Amendment object",
    "amendment_date" => "Amendment date",
    "description" => "Description",
  );

?>
