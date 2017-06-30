# Fraudshield PHP SDK
Welcome to Fraudshield PHP SDK. This repository contains Fraudshield's PHP SDK and samples for [REST API](http://developers.24metrics.com/).

## Installation
`composer require "fraudshield/fraudshield"`

## Usage
```
<?php
use Fraudshield\Reports\ConversionReport;
use Fraudshield\Fraudshield;

$fs = new Fraudshield(USER_ID, API_TOKEN);

// create a conversion report
$report = ConversionReport(81,'2017-06-05', '2017-06-05');
// add data sources
$report->addDataSource('affiliate')->addDataSource('partner');
// add a filter
$report->addFilter('min_conversions', 50);

// adding a data source and filter by it
$report->addDataSource('sub_id')->addFilter('sub_id', 500); 

// dump the report content
var_dump($fs->getReport($report));
```

## SDK Documentation
Our [API Documentation page](http://developers.24metrics.com/) includes all the documentation related to this PHP SDK

## License
MIT
