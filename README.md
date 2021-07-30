# Ytake\TrinoClient

[![Build Status](http://img.shields.io/travis/ebyhr/trino-php-client/master.svg?style=flat-square)](https://travis-ci.org/ebyhr/trino-php-client)

## Install

*required >= PHP 7.0*

```bash
$ composer require ytake/trino-php-client
```

## Usage

### Standard

```php
<?php

$client = new \Ytake\TrinoClient\StatementClient(
    new \Ytake\TrinoClient\ClientSession('http://localhost:8080/', 'acme'),
    'SELECT * FROM acme.acme.acme'
);
// execute http request
$client->execute();
// next call uri
$client->advance();

/** @var \Ytake\TrinoClient\QueryResult $result */
// current result
$result = $client->current();

// request cancel
$client->cancelLeafStage();
```

### bulk operations

```php
<?php

$client = new \Ytake\TrinoClient\StatementClient(
    new \Ytake\TrinoClient\ClientSession('http://localhost:8080/', 'acme'),
    'SELECT * FROM acme.acme.acme'
);
$resultSession = new \Ytake\TrinoClient\ResultsSession($client);
// yield results instead of returning them. Recommended.
$result = $resultSession->execute()->yieldResults();

// array
$result = $resultSession->execute()->getResults();
```

## Fetch Styles

### FixData Object

```php
<?php

$client = new \Ytake\TrinoClient\StatementClient(
    new \Ytake\TrinoClient\ClientSession('http://localhost:8080/', 'acme'),
    'SELECT * FROM acme.acme.acme'
);
$resultSession = new \Ytake\TrinoClient\ResultsSession($client);
$result = $resultSession->execute()->yieldResults();
/** @var \Ytake\TrinoClient\QueryResult $row */
foreach ($result as $row) {
    foreach ($row->yieldData() as $yieldRow) {
        if ($yieldRow instanceof \Ytake\TrinoClient\FixData) {
            var_dump($yieldRow->offsetGet('column_name'), $yieldRow['column_name']);
        }
    }
}
```

### Array Keys

```php
<?php

$client = new \Ytake\TrinoClient\StatementClient(
    new \Ytake\TrinoClient\ClientSession('http://localhost:8080/', 'acme'),
    'SELECT * FROM acme.acme.acme'
);
$resultSession = new \Ytake\TrinoClient\ResultsSession($client);
$result = $resultSession->execute()->yieldResults();
/** @var \Ytake\TrinoClient\QueryResult $row */
foreach ($result as $row) {
    /** @var array $item */
    foreach ($row->yieldDataArray() as $item) {
        if (!is_null($item)) {
            var_dump($item);
        }
    }
}
```

### Mapping Class

```php
<?php

class Testing
{
    private $_key;

    private $_value;
}

$client = new \Ytake\TrinoClient\StatementClient(
    new \Ytake\TrinoClient\ClientSession('http://localhost:8080/', 'acme'),
    'SELECT * FROM acme.acme.acme'
);
$resultSession = new \Ytake\TrinoClient\ResultsSession($client);
$result = $resultSession->execute()->yieldResults();
/** @var \Ytake\TrinoClient\QueryResult $row */
foreach ($result as $row) {
    foreach($row->yieldObject(Testing::class) as $object) {
        if ($object instanceof Testing) {
            var_dump($object);
        }
    }
}
```

