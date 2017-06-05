<?php
namespace Fraudshield\Reports;

class GlobalReport extends Report
{
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

    protected function initializeDefaults()
    {
        $this->initializeDefaultDate();
        $this->initializeWithEmptyValues();

        $this->dataSources = ['tracker_id'];
    }

    protected function prepareParameters()
    {
        $this->prepareDataSources();
        $this->prepareSearch();
        $this->prepareTimeParameters();
        
        return $this;
    }
}