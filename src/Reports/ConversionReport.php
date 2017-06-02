<?php

namespace Fraudshield\Reports;

use DateTime;
use Exception;

class ConversionReport extends Report
{
    protected $count;
    protected $page;

    const END_POINT = "reports/conversion.json";

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
        $this->count = 50;
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
        $this->parameters['click'] = false;
        $this->parameters['goal'] = false;
        $this->parameters['count'] = $this->count;
        $this->parameters['page'] = $this->page;

        $this->parameters['date_start'] = $this->dateStart;
        $this->parameters['date_end'] = $this->dateEnd;
        $this->parameters['timezone'] = $this->timezone;
    }

}