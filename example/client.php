<?php

require_once __DIR__ . '/../vendor/autoload.php';

$session = new \Ytake\TrinoClient\ClientSession('http://localhost:8080/', 'acme');
$client = new \Ytake\TrinoClient\StatementClient($session, 'SELECT * FROM acme.acme.acme');
// execute http request
$client->execute();
// next call uri
$client->advance();
// request cancel
$client->cancelLeafStage();
$client->advance();
$client->close();
