<?php

namespace Fraudshield\Reports;


class GoalReport extends Report
{
    protected $dataSources;
    protected $filters;
    protected $extraFilters;

    protected $count;
    protected $page;

    const END_POINT = "reports/goal.json";
    const MAX_DATA_SOURCES = 2;
    const VALID_DATA_SOURCES = ['affiliate', 'partner'];
    const VALID_FILTERS = ['product', 'goal_name', 'min_conversions', 'min_goals', 'min_rejection_rate', 'max_rejection_rate', 'min_goals_reached', 'max_goals_reached', 'min_avg_goal_rate', 'max_avg_goal_rate'];

    public function __construct($trackerId, $dateStart = null, $dateEnd = null, $timezone = null)
    {
        $this->trackerId = $trackerId;
        $this->setStartDate($dateStart);
        $this->setEndDate($dateEnd);
        $this->timezone = $timezone;
        $this->initializeDefaults();
    }

    protected function initializeDefaults()
    {
        $this->initializeDefaultDate();

        $this->dataSources = [];
        $this->filters = [];
        $this->extraFilters= [];

        $this->count = 10;
        $this->page = 1;
    }

    public function setPageSize($size)
    {
        $this->count = $size;

        return $this;
    }

    public function setPageNumber($position)
    {
        $this->page = $position;

        return $this;
    }

    protected function prepareParameters()
    {
        $this->parameters['tracker_id'] = $this->trackerId;        
        $group= ['product', 'goal_name']; // this two should be sent with every request
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
        
        $this->parameters['sorting']['rejected']="desc";

        return $this;   
    }

}