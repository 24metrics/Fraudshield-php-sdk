<?php

use PHPUnit\Framework\TestCase;
use Fraudshield\Reports\IntervalReport;

class IntervalReportTest extends TestCase
{
    protected $ir;

    protected function setUp()
    {
        $this->ir = new IntervalReport();
    }

    /** @test */
    public function it_can_generate_report()
    {
        $this->assertInstanceOf(IntervalReport::class, $this->ir);
    }

    /** @test */
    public function it_sets_start_end_date_to_today_if_empty()
    {
        $fr = new IntervalReport();
        $today = date("Y-m-d");

        $this->assertEquals($today, $fr->dateStart);
        $this->assertEquals($today, $fr->dateEnd);
    }

    /** @test */
    public function it_can_generate_report_with_start_end_date()
    {
        $start = "2017-05-01";
        $end = "2017-06-01";
        $ir = new IntervalReport($start, $end);

        $this->assertInstanceOf(IntervalReport::class, $ir);
    }

    /** @test */
    public function it_can_chaine_add_data_source()
    {
        $this->ir->addDataSource('sub_id')->addDataSource('partner');

        $this->assertContains('sub_id', $this->ir->dataSources);
        $this->assertContains('partner', $this->ir->dataSources);
    }

    /** @test
     * @expectedException Exception
     */
    public function it_throws_exception_when_adding_invalid_data_source()
    {
        $this->ir->addDataSource('some_invalid_data_source');
    }

    /** @test
     * @expectedException Exception
     */
    public function it_throws_exception_when_adding_more_than_allowed_number_data_source()
    {
        $this->ir
            ->addDataSource('sub_id')
            ->addDataSource('partner')
            ->addDataSource('affiliate')
            ->addDataSource('affiliate')
            ->addDataSource('affiliate')
            ->addDataSource('product');
    }

    /** @test
     */
    public function it_can_add_data_source_as_filter()
    {
        $this->ir->addDataSource('sub_id');
        $this->ir->addFilter('sub_id', 500);

        $this->assertArrayHasKey('sub_id', $this->ir->filters);
    }

    /** @test
     */
    public function it_can_add_filters()
    {
        $this->ir->addDataSource('sub_id'); // the filters array is empty by default, so we need to add a filter (via adding a source first)
        $this->ir->addFilter('sub_id', 1);

        $this->assertArrayHasKey('sub_id', $this->ir->filters);
    }

    /** @test
     * @expectedException Exception
     */
    public function it_throws_exception_when_adding_invalid_filter()
    {
        $this->ir->addFilter('invalid_filter', 50);
    }

    /** @test
     * @expectedException Exception
     */
    public function it_throws_exception_when_using_invalid_date()
    {
        $start = "2017-May-01";
        $end = "2017-06-01";
        $fr = new IntervalReport($start, $end);
    }

}
