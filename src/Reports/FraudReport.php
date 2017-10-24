<?php

namespace Fraudshield\Reports;

class FraudReport extends Report
{
    const END_POINT = "reports/fraud.json";
    const MAX_DATA_SOURCES = 3;
    const VALID_DATA_SOURCES = ['sub_id', 'partner', 'affiliate', 'product'];
    const VALID_FILTERS = [
        'min_conversions',
        'min_rejection_rate',
        'max_rejection_rate',
        'min_goals_reached',
        'max_goals_reached'
    ];

    /**
     * FraudReport constructor.
     * @param int $trackerId
     * @param string $dateStart
     * @param string $dateEnd
     * @param string $timezone
     */
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
        $this->initializeWithEmptyValues();
    }

    /**
     * @return FraudReport $this
     */
    protected function prepareParameters()
    {
        $this->prepareTracker();
        $this->prepareDataSources();
        $this->prepareSearch();
        $this->prepareTimeParameters();

        return $this;
    }
}
