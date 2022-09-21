<?php

// Before of here is:
//   General amazon credential variables:
//     $general_id_amazon
//     $general_amazon_access_key_id
//     $general_amazon_secret_key
//   print eval(file_get_contents('${{ secrets.GSS_PROJECTS_ARRAY }}'));

$echo = '<pre>'."\n\n";
$echo .= 'Total start at '.date('Y-m-d H:i:s')."\n\n";
$total_start_time = microtime(true);
$total_requests = 0;

// Each project
foreach($projects as $domain => $project) {

  $resultProjectFile = 'cache/'.$domain.'.json';
  $project_requests = 0;

  // If not same day of the year
  // if (date('z') != date('z', filemtime($resultProjectFile))) {
  if (true) {

    $start_time = microtime(true);

    // Get proyect amazon data and queries like executable php
    print eval(file_get_contents($project));

    // Get JSON's each query and join in a unique JSON
    $products = array();
    foreach($queries as $i => $query) {
      $kw = preg_replace('/^(.*)~.*$/i', '$1', $query);
      $browseNode = preg_replace('/^.*~(.*)$/i', '$1', $query);
      $total_requests++;
      $project_requests++;
      try {
        $product = api_amazon($kw, $browseNode, $general_amazon_access_key_id, $general_amazon_secret_key, $general_id_amazon);
      } catch (Exception $e) {
        $echo .= '    Exception in '.$domain.' with ID '.$i.': '.$e->getMessage().' ['.$project_requests.'/'.$total_requests.' requests (project/total)]'."\n";
      }
      if (!$product) {
        $product = '{"Errors":true}';
      }
      array_push($products, '"'.$i.'":'.$product);
      // Pause of 1 second for amazon limit
      sleep(1);
    }
    $json = '{' . join(',', $products) . '}';

    // Create a domain.txt file with JSON content
    $cached = fopen($resultProjectFile, 'w');
    fwrite($cached, $json);
    fclose($cached);

    // Print time
    $end_time = microtime(true);
    $execution_time = intval($end_time - $start_time);
    $echo .=
      'Write '
      .$resultProjectFile
      .' in '
      .$execution_time
      .' sec ('
      .number_format(($execution_time/60), 2, ',', '')
      .' min) ['
      .$project_requests
      .' requests]'
      ."\n";

    upFiles('Write JSON for each project');

  } else {
    $echo .= 'Skip '.$resultProjectFile."\n";
  }

}

$total_end_time = microtime(true);
$total_execution_time = intval($total_end_time - $total_start_time);
$echo .= "\n";
$echo .= 'Total requests = '.$total_requests."\n";
$echo .= 'Total finish at '.date('Y-m-d H:i:s')."\n";
$echo .= 'Total execution time of script = '.$total_execution_time.' sec ('.number_format(($total_execution_time/60), 2, ',', '').' min)'."\n\n";
$echo .= '</pre>';

// Print custom logs
echo $echo;

// Write custom logs in txt file
$echoFile = fopen('logs/log-'.date('Y-m-d-H-i-s').'.txt', 'w');
fwrite($echoFile, $echo);
fclose($echoFile);

error_reporting(-1);
