# Fraudshield PHP SDK
Welcome to Fraudshield PHP SDK. This repository contains Fraudshield's PHP SDK and samples for [REST API](http://developers.24metrics.com/).

## SDK Documentation
Our [API Documentation page](http://developers.24metrics.com/) includes all the documentation related to this PHP SDK

## Installation
`composer require "fraudshield/fraudshield-php"`

## Usage

### Conversion Report
```php
<?php
require_once __DIR__ . '/vendor/autoload.php';

use Fraudshield\Fraudshield;
use Fraudshield\Reports\ConversionReport;

$fs = new Fraudshield(USER_ID, API_TOKEN);

// create a conversion report
$report = new ConversionReport(186,'2017-06-05', '2017-06-05'); 

// dump the report content
var_dump($fs->getReport($report));
```

### Fraud Report
```php
<?php
require_once __DIR__ . '/vendor/autoload.php';

use Fraudshield\Fraudshield;
use Fraudshield\Reports\FraudReport;

$fs = new Fraudshield(USER_ID, API_TOKEN);

// create a conversion report
$report = new FraudReport(186,'2017-06-05', '2017-06-05');

// add data sources
$report
    ->addDataSource('sub_id')
    ->addDataSource('partner')
    ->addDataSource('affiliate')
    ->addDataSource('product')
    ->addDataSource('goal');

// add filter
$report
    ->addFilter('min_conversions', 1)
    ->addFilter('min_rejection_rate', 1)
    ->addFilter('max_rejection_rate', 50)
    ->addFilter('min_goals_reached', 50)
    ->addFilter('max_goals_reached', 50);

// dump the report content
var_dump($fs->getReport($report));
```

## License
MIT
