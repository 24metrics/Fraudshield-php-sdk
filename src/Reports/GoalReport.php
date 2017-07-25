<?php

namespace Fraudshield\Reports;

class GoalReport extends Report
{
    use Pagination;

    const END_POINT = "reports/goal.json";
    const MAX_DATA_SOURCES = 2;
    const VALID_DATA_SOURCES = ['affiliate', 'partner'];
    const VALID_FILTERS = [
        'product',
        'goal_name',
        'min_conversions',
        'min_goals',
        'min_rejection_rate',
        'max_rejection_rate',
        'min_goals_reached',
        'max_goals_reached',
        'min_avg_goal_rate',
        'max_avg_goal_rate'
    ];

    /**
     * GoalReport constructor.
     * @param int $trackerId
     * @param string  $dateStart
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

        $this->count = 10;
        $this->page = 1;
    }

    /**
     * @return GoalReport $this
     */
    protected function prepareParameters()
    {
        $this->prepareTracker();
        $this->prepareDataSources(['product', 'goal_name']); // these two should be sent with every request
        $this->prepareSearch();
        $this->prepareTimeParameters();

        $this->parameters['sorting']['rejected'] = "desc";

        return $this;
    }

}
