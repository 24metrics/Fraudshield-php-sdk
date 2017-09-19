<?php

namespace Fraudshield\Reports;

class ConversionScrollReport extends ConversionReport
{
    const END_POINT = "reports/conversion_scroll.json";

    protected $usesPostRequest = true;

    public function setNextPageId($id)
    {
        $this->parameters['next_page_id'] = $id;

        return $this;
    }
}
