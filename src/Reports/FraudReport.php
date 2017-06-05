<?php

namespace Fraudshield\Reports;

use Exception;

class FraudReport extends Report
{

    const END_POINT = "reports/fraud.json";
    const MAX_DATA_SOURCES = 3;
    const VALID_DATA_SOURCES = ['sub_id', 'partner', 'affiliate', 'product', 'goal'];
    const VALID_FILTERS = ['min_conversions', 'min_rejection_rate', 'max_rejection_rate', 'min_goals_reached', 'max_goals_reached'];

    public function __construct($trackerId, $dateStart = null, $dateEnd = null, $timezone = null)
    {
        $this->trackerId = $trackerId;
        $this->setStartDate($dateStart);
        $this->setEndDate($dateEnd);
        $this->timezone = $timezone;
        $this->initializeDefaults();
    }

    protected function prepareParameters()
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
    
    protected function initializeDefaults()
    {
        $this->initializeDefaultDate();

        $this->dataSources = [];
        $this->filters = [];
        $this->extraFilters= [];
    }
}
