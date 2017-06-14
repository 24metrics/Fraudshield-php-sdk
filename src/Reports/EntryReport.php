<?php
namespace Fraudshield\Reports;


class EntryReport
{

    const END_POINT = "reports";

    protected $entryType;
    protected $entryId;

    public function __construct($entryType, $entryId)
    {
        $this->entryType = $entryType;
        $this->entryId = $entryId;
    }

    public function getPartialApiRequest()
    {
        $uri = self::END_POINT."/{$this->entryType}/{$this->entryId}?";

        return $uri;
    }

}
