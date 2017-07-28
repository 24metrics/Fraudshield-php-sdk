<?php

namespace Fraudshield\Reports;

class ConversionReport extends Report
{
    use Pagination;

    const END_POINT = "reports/conversion.json";
    const VALID_FILTERS = [
        'http_click_referer',
        'country_code',
        'remote_address',
        'product',
        'affiliate',
        'partner',
        'sub_id',
        'status',
        'rejected_reason.simple_reason',
        'rejected_reason.advanced_reason',
        'min_score',
        'max_score'
    ];

    /**
     * ConversionReport constructor.
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
        $this->count = 50;
        $this->page = 1;
    }

    protected function prepareParameters()
    {
        $this->parameters['click'] = false;
        $this->parameters['goal'] = false;

        $this->prepareTracker();
        $this->prepareSearch();
        $this->prepareTimeParameters();
        $this->preparePagination();
    }
}
