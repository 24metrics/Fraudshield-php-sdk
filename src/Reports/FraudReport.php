<?php

namespace Fraudshield\Reports;

use DateTime;
use Exception;

class FraudReport
{
    
    private $trackerId;
    private $dateStart;
    private $dateEnd;
    private $timezone;
    private $dataSources;
    private $filters;
    private $extraFilters;
    private $parameters;

    const END_POINT = "reports/fraud.json";
    const MAX_DATA_SOURCES = 3;
    const VALID_DATA_SOURCES = ['sub_id', 'partner', 'affiliate', 'product', 'goal'];
    const VALID_FILTERS = ['min_conversions', 'min_rejection_rate', 'max_rejection_rate', 'min_goals_reached', 'max_goals_reached'];
    private $apiBase = "https://fraudshield.local/api/v1/";

    public function __construct($trackerId, $dateStart = null, $dateEnd = null, $timezone = null)
    {
        $this->trackerId = $trackerId;
        $this->setStartDate($dateStart);
        $this->setEndDate($dateEnd);
        $this->timezone = $timezone;
        $this->initializeDefauts();
    }

    public function addDataSource($source)
    {
        if (! in_array($source, self::VALID_DATA_SOURCES) ) {
            throw new Exception("invalid data source", 1);
        }
        if (count($this->dataSources) < 3) {
            $this->dataSources[] = $source;
            $this->extraFilters[] = $source;
        } else {
            throw new Exception("you cannot add more than 3 data sources at a time", 1);
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

    public function setStartDate($date)
    {
        if ($date && $this->isValidDate($date)) {
            $this->dateStart = $date;
        }

        return $this;
        
    }

    public function setEndDate($date)
    {
        if ($date &&  $this->isValidDate($date)) {
            $this->dateEnd = $date;
        }

        return $this;
    }

    public function prepareParameters()
    {
        $this->parameters['tracker_id'] = $this->trackerId;        
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

    public function getPartialApiRequest()
    {
        $this->prepareParameters();
        $query = '';
        if ($this->parameters) {
            $query = http_build_query($this->parameters);
        }
        $uri = self::END_POINT.'?'.$query;
        return $uri;
    }
    

    private function initializeDefauts()
    {
        $today = date("Y-m-d");
        if ( ($this->dateStart == null) ||  ($this->dateEnd == null)) {
            $this->dateStart = $today;
            $this->dateEnd = $today;
        }

        if ($this->timezone == null) {
            $this->timezone = "UTC";
        }

        $this->dataSources = [];
        $this->filters = [];
        $this->extraFilters= [];
    }

    private function getValidFilters()
    {
        return array_merge(self::VALID_FILTERS, $this->extraFilters);
    }

    private function isValidDate($date)
    {

        $dt = DateTime::createFromFormat("Y-m-d", $date);

        if ($dt) {
            return true;
        }
        throw new Exception("Invalid Date", 1);
        
    }

    public function __get($propertyName)
    {
        if($this->{$propertyName}) {
            return $this->{$propertyName};
        }
        return null;
    }


}