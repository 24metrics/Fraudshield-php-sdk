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

    protected $usesPostRequest;

    abstract protected function initializeDefaults();

    abstract protected function prepareParameters();

    /**
     * @param string $date
     * @return Report $this
     */
    public function setStartDate($date)
    {
        if ($date && $this->isValidDate($date)) {
            $this->dateStart = $date;
        }

        return $this;
    }

    /**
     * @param string $date
     * @return Report $this
     */
    public function setEndDate($date)
    {
        if ($date && $this->isValidDate($date)) {
            $this->dateEnd = $date;
        }

        return $this;
    }

    /**
     * @param string $date
     * @return bool
     * @throws Exception
     */
    protected function isValidDate($date)
    {
        $dt = DateTime::createFromFormat("Y-m-d", $date);

        if ($dt->format("Y-m-d") == $date) {
            return true;
        }

        throw new Exception("Invalid Date: {$date}", 1);
    }

    protected function initializeDefaultDate()
    {
        $today = date("Y-m-d");
        if (($this->dateStart == null) || ($this->dateEnd == null)) {
            $this->dateStart = $today;
            $this->dateEnd = $today;
        }

        if ($this->timezone == null) {
            $this->timezone = "UTC";
        }
    }

    /**
     * @param string $propertyName
     * @return mixed
     */
    public function __get($propertyName)
    {
        if ($this->{$propertyName}) {
            return $this->{$propertyName};
        }
        return null;
    }

    /**
     * @return string
     */
    public function getPartialApiRequest()
    {
        $this->prepareParameters();
        $query = '';
        if ($this->parameters) {
            $query = http_build_query($this->parameters);
        }
        $uri = static::END_POINT . '?' . $query;

        return $uri;
    }

    /**
     * @param string $source
     * @return Report $this
     * @throws Exception
     */
    public function addDataSource($source)
    {
        if (!in_array($source, static::VALID_DATA_SOURCES)) {
            throw new Exception("invalid data source: {$source}", 1);
        }

        if (count($this->dataSources) < static::MAX_DATA_SOURCES) {
            $this->dataSources[] = $source;
            $this->extraFilters[] = $source;
        } else {
            throw new Exception(
                sprintf(
                    "You tried to add %s data sources. You can not add more than %s data sources at a time",
                    count($this->dataSources),
                    static::MAX_DATA_SOURCES
                )
                , 1
            );
        }

        return $this;
    }

    /**
     * @param string $filterName
     * @param mixed $value
     * @return Report $this
     * @throws Exception
     */
    public function addFilter($filterName, $value)
    {
        if (!in_array($filterName, $this->getValidFilters())) {
            throw new Exception("invalid filter: {$filterName}", 1);
        }
        $this->filters[$filterName] = $value;

        return $this;
    }

    /**
     * @return array
     */
    protected function getValidFilters()
    {
        return array_merge(static::VALID_FILTERS, $this->extraFilters);
    }

    protected function initializeWithEmptyValues()
    {
        $this->dataSources = [];
        $this->filters = [];
        $this->extraFilters = [];
    }

    protected function prepareTimeParameters()
    {
        $this->parameters['date_start'] = $this->dateStart;
        $this->parameters['date_end'] = $this->dateEnd;
        $this->parameters['timezone'] = $this->timezone;
    }

    protected function prepareDataSources($mandatory = [])
    {
        $group = $mandatory;
        foreach ($this->dataSources as $ds) {
            $group[] = $ds;
        }
        if (count($group) > 0) {
            $this->parameters['group'] = $group;
        }
    }

    protected function prepareSearch()
    {
        $search_fields = [];
        foreach ($this->filters as $filter => $value) {
            $search_fields[] = json_encode(["term" => $filter, "query" => $value]);
        }
        if (count($search_fields) > 0) {
            $this->parameters['search_fields'] = $search_fields;
        }
    }

    protected function prepareTracker()
    {
        $this->parameters['tracker_id'] = $this->trackerId;
    }

    protected function preparePagination()
    {
        $this->parameters['count'] = $this->count;
        $this->parameters['page'] = $this->page;
    }

    public function usesPostRequest()
    {

        return $this->usesPostRequest;
    }
}
