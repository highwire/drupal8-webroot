<?php

$a4d_host = "a4d-mysql-dev-1.highwire.org";
$a4d_user = "journalview";
$a4d_pass = "highwire";
$policy_name = "drupal-43";
$policy_sig = "e4f9bb686881e89ad90fdccaf626ce53409e3b";
$atomx_hosts = ["freebird-dev01.highwire.org:9200"];

// Autoload
include_once('vendor/autoload.php');

use HighWire\Clients\AtomX\AtomX;

$atomx = new AtomX($policy_name, ['hosts' => $atomx_hosts]);

// Create connection
$conn = new mysqli($a4d_host, $a4d_user, $a4d_pass, "a4d");

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$sql = 'SELECT payload FROM resource_payload WHERE signature = "' . $policy_sig . '" LIMIT 100000';
$result = $conn->query($sql);

if ($result->num_rows > 0) {
  while($row = $result->fetch_assoc()) {
    $payload = gzinflate($row["payload"]);
    $item = json_decode($payload, TRUE);
    try {
      $atomx->indexItem($item);
    }
    catch (Exception $e) {
      $error = json_decode($e->getMessage(), TRUE);
      if ($error['error']['root_cause'][0]['type'] == 'index_not_found_exception') {
        $atomx->createIndex($item['jcode']);
        $atomx->indexItem($item);
      } 
   }
  }
}

$conn->close();
