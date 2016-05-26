<?php

function get_datastore_resources_filter($ckan_domain,$resource_id,$key,$value){
  $datastore_url = $ckan_domain . "/api/3/action/datastore_search?resource_id=" . $resource_id . "&limit=1000&filters={\"" . $key . "\":\"" . $value . "\"}";
  $json = @file_get_contents($datastore_url);
  if ($json === FALSE) return [];
  $profiles = json_decode($json, true) ?: [];
  return $profiles["result"]["records"];
}

function get_datastore_resource($ckan_domain,$resource_id){
  $datastore_url = $ckan_domain . "/api/3/action/datastore_search?resource_id=" . $resource_id . "&limit=1000";
  $json = @file_get_contents($datastore_url);
  if ($json === FALSE) return [];
  $profiles = json_decode($json, true) ?: [];
  return $profiles["result"]["records"];
}

?>
