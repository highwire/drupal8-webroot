<?php

/**
 * Seed atomlite with payloads and insert test extract policies
 */


$options = getopt('', array("contact-point:" ,"keyspace:" ,"port::"));

if (empty($options['contact-point']) || empty($options['keyspace'])) {
  echo "Please pass --contact-point and --keyspace options";
  exit;
}

$contact_point = $options['contact-point'];
$keyspace = $options['keyspace'];
$port = $options['port'] ?? 9042;
$extract_policies_dir ='/extract-policies';

$cluster = Cassandra::cluster()
->withContactPoints($contact_point)
->withPort($port)
->build();
$session = $cluster->connect($keyspace);


$policy_id = '54035030-c053-11e5-bacb-43a133c6adb4';
$dh = opendir(__DIR__  . $extract_policies_dir);
while ($extract_policy_dir = readdir($dh)) {
  if ($extract_policy_dir == '.' || $extract_policy_dir == '..') {
    continue;
  }

  if (is_dir(__DIR__  . $extract_policies_dir . '/' . $extract_policy_dir)) {
    $dh2 = opendir(__DIR__  . $extract_policies_dir . '/' . $extract_policy_dir);
    if (!file_exists(__DIR__  . $extract_policies_dir . '/' . $extract_policy_dir . '/policy.xml')) {
      echo "Skipping $extract_policy_dir, no policy.xml found";
      continue;
    }

    $mime_type = 'application/vnd.hw.' . $extract_policy_dir . '+json';

    // Note this is not in the form that atomlite stores the data
    $extract_policy = str_replace("'", "''", file_get_contents(__DIR__  . $extract_policies_dir . '/' . $extract_policy_dir . '/policy.xml'));
    // Fill in some test policy data
    $cql = "INSERT INTO " . $keyspace . ".policy (id, format, mime_type, name, policy_id, signature, source, updated, url)
            VALUES (1f0025f0-e19c-11e6-8a56-9116fc548b6b, 'json/xml', ['" . $mime_type . "'], '" . $extract_policy_dir . "', '" . $extract_policy_dir . "', '1f33be45dea8cf802189d058da6e16f7', '$extract_policy', '2016-10-13 19:59:08+0000', 'http://staticfs.highwire.org/a4d-extract/policies/drupal-journal/definition.xml') IF NOT EXISTS";
    $session->execute(new Cassandra\SimpleStatement($cql));

    while ($file = readdir($dh2)) {
      if (is_dir(__DIR__  . $extract_policies_dir . '/' . $extract_policy_dir . '/' . $file)) {
        continue;
      }

      $path_info = pathinfo($file);
      if ($path_info['extension'] != 'json') {
        continue;
      }

      $raw_payload = file_get_contents(__DIR__  . $extract_policies_dir . '/' . $extract_policy_dir . '/' . $file);
      $item = json_decode($raw_payload, TRUE);
      $payload_signature = md5($raw_payload);
      $uri = $item['apath'];
      $app_edited = '2016-01-16 09:21:01+0000';
      $corpus = $item['jcode'];
      $extraction_date = '2016-01-16 09:21:01+0000';
      $updated_date = '2016-11-08 16:00:59+0000';
      $policy_date = '2016-11-08 16:00:59+0000';
      $raw_payload_cql_safe = str_replace("'", "''", $raw_payload);

      $cql = "INSERT INTO " . $keyspace . ".atom_lite
      (mime_type, corpus, uri, app_edited_date, extraction_date, payload, payload_signature, policy_date, policy_id, update_date)
      VALUES('$mime_type', '$corpus', '$uri', '$app_edited', '$extraction_date', '$raw_payload_cql_safe', '$payload_signature', '$policy_date', $policy_id, '$updated_date')
      IF NOT EXISTS";
      $session->execute(new Cassandra\SimpleStatement($cql));
    }
  }
}

$session->close();
