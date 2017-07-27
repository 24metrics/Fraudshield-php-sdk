<?php

namespace Fraudshield\Reports;

Trait Pagination
{
    protected $count;
    protected $page;

    /**
     * @param int $size
     * @return Pagination $this
     */
    public function setPageSize($size)
    {
        $this->count = $size;

        return $this;
    }

    /**
     * @param int $position
     * @return Pagination $this
     */
    public function setPageNumber($position)
    {
        $this->page = $position;

        return $this;
    }
}
