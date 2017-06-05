<?php

namespace Fraudshield\Reports;

class MapReport extends Report
{
    const END_POINT = "reports/map.json";
    const MAX_DATA_SOURCES = 4;
    const VALID_DATA_SOURCES = ['tracker_id', 'affiliate', 'partner', 'product', 'sub_id', 'country_code'];
    const VALID_FILTERS = ['country_code'];


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

        $this->dataSources = ['country_code'];
        $this->filters = [];
        $this->extraFilters= [];
    }

}