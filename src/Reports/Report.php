<?php
namespace Fraudshield\Reports;

use DateTime;
use Exception;

Abstract class Report
{
    protected $trackerId;
    protected $dateStart;
    protected $dateEnd;
    protected $timezone;
    protected $parameters;

    public function setStartDate($date)
    {
        if ($date && $this->isValidDate($date)) {
            $this->dateStart = $date;
        }

        return $this;
        
    }

    public function setEndDate($date)
    {
        if ($date &&  $this->isValidDate($date)) {
            $this->dateEnd = $date;
        }

        return $this;
    }

    private function isValidDate($date)
    {

        $dt = DateTime::createFromFormat("Y-m-d", $date);

        if ($dt) {
            return true;
        }
        throw new Exception("Invalid Date", 1);
        
    }

    protected abstract function initializeDefaults();

    protected function initializeDefaultDate()
    {
        $today = date("Y-m-d");
        if ( ($this->dateStart == null) ||  ($this->dateEnd == null)) {
            $this->dateStart = $today;
            $this->dateEnd = $today;
        }

        if ($this->timezone == null) {
            $this->timezone = "UTC";
        }
    }

    public function __get($propertyName)
    {

        if($this->{$propertyName}) {
            return $this->{$propertyName};
        }
        return null;
    }

    public function getPartialApiRequest()
    {
        $this->prepareParameters();
        $query = '';
        if ($this->parameters) {
            $query = http_build_query($this->parameters);
        }
        $uri = static::END_POINT.'?'.$query;
        return $uri;
    }

    abstract protected function prepareParameters();
}