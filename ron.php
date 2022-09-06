<?php

/*

echo date('Y-m-d H:i:s');
echo '<br>';

$start_time = microtime(true);
sleep(3);
$end_time = microtime(true);
$execution_time = intval($end_time - $start_time);
echo 'Execution time of script = '.$execution_time.' sec ('.($execution_time/60).' min)';

--

$holi = fopen('cache/hole.txt', 'w');
$content = '¡Como molo!';
fwrite($holi, $content);
fclose($holi);

echo readfile('cache/hole.txt');

--

*/

$kw = 'reebok nano 9';
$browseNode = '2008023031';

echo api_amazon($kw, $browseNode, $general_amazon_access_key_id, $general_amazon_secret_key, $general_id_amazon);
