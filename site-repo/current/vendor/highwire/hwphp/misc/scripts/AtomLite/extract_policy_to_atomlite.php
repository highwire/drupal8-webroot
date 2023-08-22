<?php

use GetOptionKit\OptionCollection;
use GetOptionKit\OptionParser;
use HighWire\Clients\AtomLite\AtomLite;
use HighWire\Services\A4DExtract\A4DExtract;

// Autoload
include_once('vendor/autoload.php');

$specs = new OptionCollection;
$specs->add('a4d-extract-url?')->isa('url');
$specs->add('atomlite-hosts?')->isa('string');

$parser = new OptionParser($specs);
$options = $parser->parse($argv);
$args = $options->getArguments();

if (empty($args)) {
  print "extract_policy_to_atomlite: Fetches extract policy from a4d-extract and inserts it into atomlite\n";
  print "usage:   php misc/scripts/AtomLite/extract_policy_to_atomlite.php <policy-name>\n";
  print "example: php misc/scripts/AtomLite/extract_policy_to_atomlite.php freebird-journal\n";
  print "options:\n";
  print "  --a4d-extract-url=http://a4d-extract.highwire.org\n";
  print "  --atomlite-hosts-=atomlite-db-dev-01.highwire.org,atomlite-db-dev-02.highwire.org,atomlite-db-dev-03.highwire.org\n";
  exit(1);
}

// Set-up A4D-Extract
if (!empty($options->get('a4d-extract-url'))) {
  $a4dextract = new A4DExtract(['baseUrl' => $options->get('a4d-extract-url')]);
}
else {
  $a4dextract = new A4DExtract();
}

// Set-up AtomLite
if (!empty($options->get('atomlite-hosts'))) {
  $contactpoints = explode($options->get('atomlite-hosts'));
}
else {
  $contactpoints = ["atomlite-db-dev-01.highwire.org", "atomlite-db-dev-02.highwire.org", "atomlite-db-dev-03.highwire.org"];
}
$atomlite = new AtomLite('atomlite', $contactpoints);

$policy_name = $args[0];

$policy = $a4dextract->getPolicy(['policy' => $policy_name]);

$result = $atomlite->query("SELECT id from policy WHERE policy_id = ?", ['arguments' => [$policy_name]]);

if ($result->count() == 0) {
  $args = [
    "xml/json", // format
    $policy_name, // name
    $policy_name, // policy_id
    md5($policy->out()), // signature
    $policy->out(),  // source
    "http://staticfs.highwire.org/a4d-extract/policies/" . $policy_name . "/definition.xml", // url
  ];

  $result = $atomlite->query("INSERT INTO policy (id, format, name, policy_id, signature, source, updated, url) VALUES (now(), ?, ?, ?, ?, ?, dateof(now()), ?)", ['arguments' => $args, 'consistency' => Cassandra::CONSISTENCY_ONE]);
}
else {
  $row = $result->first();
  $id = $row['id'];
  $result = $atomlite->query("UPDATE policy set source = ? WHERE id = ?", ['arguments' => [$policy->out(), $id]]);
}
