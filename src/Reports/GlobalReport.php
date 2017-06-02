<?php

namespace Fraudshield\Reports;

use Exception;

class GlobalReport extends Report
{
    protected $dataSources;
    protected $filters;
    protected $extraFilters;

    const END_POINT = "reports/global.json";
    const MAX_DATA_SOURCES = 4;
    const VALID_DATA_SOURCES = ['tracker_id', 'affiliate', 'partner', 'product', 'sub_id', 'country_code'];
    const VALID_FILTERS = ['tracker_id'];

    public function __construct($dateStart = null, $dateEnd = null, $timezone = null)
    {
        $this->setStartDate($dateStart);
        $this->setEndDate($dateEnd);
        $this->timezone = $timezone;
        $this->initializeDefaults();
    }

    protected function prepareParameters()
    {
        $group= [];
        foreach ($this->dataSources as $ds) {
            $group[] = $ds;
        }
        if (count($group) > 0) {
           $this->parameters['group'] = $group; 
        }

        $search_fields= [];
        foreach ($this->filters as $filter => $value) {
            $search_fields[] = json_encode(["term" => $filter, "query" => $value]);
        }
        if (count($search_fields) > 0) {
           $this->parameters['search_fields'] = $search_fields; 
        }

        $this->parameters['date_start'] = $this->dateStart;
        $this->parameters['date_end'] = $this->dateEnd;
        $this->parameters['timezone'] = $this->timezone;
        
        return $this;
    }

    protected function initializeDefaults()
    {
        $this->initializeDefaultDate();

        $this->dataSources = ['tracker_id'];
        $this->filters = [];
        $this->extraFilters= [];
    }

    public function addDataSource($source)
    {
        if (! in_array($source, self::VALID_DATA_SOURCES) ) {
            throw new Exception("invalid data source", 1);
        }
       
        if (count($this->dataSources) < self::MAX_DATA_SOURCES) {
            $this->dataSources[] = $source;
            $this->extraFilters[] = $source;
        } else {
            throw new Exception("you cannot add more than ".self::MAX_DATA_SOURCES." data sources at a time", 1);
        }

        return $this;
    }

    public function addFilter($filterName, $value)
    {
        if (! in_array($filterName, $this->getValidFilters()) ) {
            throw new Exception("invalid filter", 1);
        }
        if (! is_numeric($value)) {
            throw new Exception("invalid value for the filter", 1);
        }
        $this->filters[$filterName] = $value;

        return $this;
    }

    private function getValidFilters()
    {
        return array_merge(self::VALID_FILTERS, $this->extraFilters);
    }
}