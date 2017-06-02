<?php

use PHPUnit\Framework\TestCase;
use Fraudshield\Reports\ConversionReport;

class ConversionReportTest extends TestCase
{
    protected $cr;

    protected function setUp()
    {
        $this->cr = new ConversionReport(1);
    }

    /** @test */
    public function it_can_generate_report_with_just_tracker_id()
    {
        $this->assertInstanceOf(ConversionReport::class, $this->cr);
    }

    /** @test */
    public function it_sets_start_end_date_to_today_if_empty()
    {
        $cr = new ConversionReport(1);
        $today = date("Y-m-d");
        $this->assertEquals($today, $cr->dateStart);
        $this->assertEquals($today, $cr->dateEnd);
    }

    /** @test */
    public function it_can_set_page_size()
    {
        $this->cr->setPageSize(100);
        $this->assertEquals(100, $this->cr->count);
    }

    /** @test */
    public function it_can_set_page_number()
    {
        $this->cr->setPageNumber(5);
        $this->assertEquals(5, $this->cr->page);
    }

    /** @test */
    public function it_can_set_sorting()
    {
        $this->cr->setSorting('id', 'asc');
        $this->assertEquals('asc', $this->cr->sorting['id']);
    }
}