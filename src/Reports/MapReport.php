<?php

namespace Fraudshield\Reports;

class MapReport extends Report
{
    const END_POINT = "reports/map.json";
    const MAX_DATA_SOURCES = 6;
    const VALID_DATA_SOURCES = ['tracker_id', 'affiliate', 'partner', 'product', 'sub_id', 'country_code'];
    const VALID_FILTERS = ['country_code'];


    /**
     * MapReport constructor.
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

        $this->dataSources = ['country_code'];
    }

    /**
     * @return MapReport $this
     */
    protected function prepareParameters()
    {
        $this->prepareDataSources();
        $this->prepareSearch();
        $this->prepareTimeParameters();

        return $this;
    }
}
