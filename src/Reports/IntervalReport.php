<?php

namespace Fraudshield\Reports;


use Exception;

class IntervalReport extends Report
{
    use Pagination;
    
    protected $chartType;
    protected $timeInterval;

    const END_POINT = "reports/interval.json";
    const MAX_DATA_SOURCES = 3;
    const VALID_DATA_SOURCES = ['tracker_id','affiliate', 'partner', 'product', 'sub_id'];
    const VALID_FILTERS = [];

    public function __construct($dateStart = null, $dateEnd = null, $timezone = null)
    {
        $this->setStartDate($dateStart);
        $this->setEndDate($dateEnd);
        $this->timezone = $timezone;
        $this->initializeDefaults();
    }

    protected function initializeDefaults()
    {
        $this->initializeDefaultDate();

        $this->chartType = 'conversion_rate';
        $this->count = 25;
        $this->page = 1;
        $this->timeInterval = "week";

        $this->dataSources = [];
        $this->filters = [];
        $this->extraFilters= [];

        return $this;
    }

    protected function prepareParameters()
    {
        $this->parameters['chart_type'] = $this->chartType;
        $this->parameters['count'] = $this->count;
        $this->parameters['page'] = $this->page;
        $this->parameters['time_interval'] = $this->timeInterval;

        $this->parameters['date_start'] = $this->dateStart;
        $this->parameters['date_end'] = $this->dateEnd;
        $this->parameters['timezone'] = $this->timezone;
    }

}
