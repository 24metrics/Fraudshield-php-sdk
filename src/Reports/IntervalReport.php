<?php

namespace Fraudshield\Reports;

class IntervalReport extends Report
{
    use Pagination;

    protected $chartType;
    protected $timeInterval;

    const END_POINT = "reports/interval.json";
    const MAX_DATA_SOURCES = 5;
    const VALID_DATA_SOURCES = ['tracker_id', 'affiliate', 'partner', 'product', 'sub_id'];
    const VALID_FILTERS = [];

    /**
     * IntervalReport constructor.
     * @param string $dateStart
     * @param string $dateEnd
     * @param string $timezone
     */
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
        $this->initializeWithEmptyValues();

        $this->chartType = 'conversion_rate';
        $this->count = 25;
        $this->page = 1;
        $this->timeInterval = "week";
    }

    protected function prepareParameters()
    {
        $this->parameters['chart_type'] = $this->chartType;
        $this->parameters['time_interval'] = $this->timeInterval;

        $this->prepareDataSources(['tracker_id']); // this should be sent with every request
        $this->prepareSearch();
        $this->prepareTimeParameters();
        $this->preparePagination();
    }

}
