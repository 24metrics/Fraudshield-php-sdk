<?php
namespace Fraudshield\Reports;

use DateTime;
use Exception;

Abstract class Report
{
    protected $trackerId;
    protected $dateStart;
    protected $dateEnd;
    protected $timezone;
    protected $parameters;

    protected $dataSources;
    protected $filters;
    protected $extraFilters;

    abstract protected  function initializeDefaults();
    abstract protected function prepareParameters();

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

    protected function isValidDate($date)
    {

        $dt = DateTime::createFromFormat("Y-m-d", $date);

        if ($dt) {
            return true;
        }
        throw new Exception("Invalid Date", 1);
        
    }

   

    protected function initializeDefaultDate()
    {
        $today = date("Y-m-d");
        if ( ($this->dateStart == null) ||  ($this->dateEnd == null)) {
            $this->dateStart = $today;
            $this->dateEnd = $today;
        }

        if ($this->timezone == null) {
            $this->timezone = "UTC";
        }
    }

    public function __get($propertyName)
    {

        if($this->{$propertyName}) {
            return $this->{$propertyName};
        }
        return null;
    }

    public function getPartialApiRequest()
    {
        $this->prepareParameters();
        $query = '';
        if ($this->parameters) {
            $query = http_build_query($this->parameters);
        }
        $uri = static::END_POINT.'?'.$query;
        return $uri;
    }

    public function addDataSource($source)
    {
        if (! in_array($source, static::VALID_DATA_SOURCES) ) {
            throw new Exception("invalid data source", 1);
        }
        if (count($this->dataSources) < static::MAX_DATA_SOURCES) {
            $this->dataSources[] = $source;
            $this->extraFilters[] = $source;
        } else {
            throw new Exception("you cannot add more than ".static::MAX_DATA_SOURCES." data sources at a time", 1);
        }

        return $this;
    }

    public function addFilter($filterName, $value)
    {
        if (! in_array($filterName, $this->getValidFilters()) ) {
            throw new Exception("invalid filter", 1);
        }
        $this->filters[$filterName] = $value;

        return $this;
    }

    protected function getValidFilters()
    {
        return array_merge(static::VALID_FILTERS, $this->extraFilters);
    }

    protected function initializeWithEmptyValues()
    {
        $this->dataSources = [];
        $this->filters = [];
        $this->extraFilters= [];
    }

    protected function prepareTimeParameters()
    {
        $this->parameters['date_start'] = $this->dateStart;
        $this->parameters['date_end'] = $this->dateEnd;
        $this->parameters['timezone'] = $this->timezone;
    }
}