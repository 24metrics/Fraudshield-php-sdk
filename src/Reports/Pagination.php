<?php
namespace Fraudshield\Reports;

Trait Pagination
{
    protected $count;
    protected $page;
    
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
}