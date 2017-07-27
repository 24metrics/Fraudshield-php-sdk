<?php

use PHPUnit\Framework\TestCase;
use Fraudshield\Reports\GlobalReport;

class MapReportTest extends TestCase
{
    protected $mr;

    protected function setUp()
    {
        $this->mr = new GlobalReport();
    }

    /** @test */
    public function it_sets_start_end_date_to_today_if_empty()
    {
        $mr = new GlobalReport();
        $today = date("Y-m-d");
        $this->assertEquals($today, $mr->dateStart);
        $this->assertEquals($today, $mr->dateEnd);
    }

    /** @test */
    public function it_can_generate_report_with_tracker_id_and_start_date_and_end_date()
    {
        $start = "2017-05-01";
        $end = "2017-06-01";
        $mr = new GlobalReport($start, $end);

        $this->assertEquals($start, $mr->dateStart);
        $this->assertEquals($end, $mr->dateEnd);
    }

    /** @test */
    public function it_can_add_data_source()
    {
        $this->mr->addDataSource('sub_id');

        $this->assertContains('sub_id', $this->mr->dataSources);
    }

    /** @test */
    public function it_can_chaine_add_data_source()
    {
        $this->mr->addDataSource('sub_id')->addDataSource('partner');

        $this->assertContains('sub_id', $this->mr->dataSources);
        $this->assertContains('partner', $this->mr->dataSources);
    }

    /** @test
     * @expectedException Exception
     */
    public function it_throws_exception_when_adding_invalid_data_source()
    {
        $this->mr->addDataSource('some_invalid_data_source');
    }

    /** @test
     * @expectedException Exception
     */
    public function it_throws_exception_when_adding_more_than_allowed_number_data_source()
    {
        $this->mr
            ->addDataSource('sub_id')
            ->addDataSource('partner')
            ->addDataSource('affiliate')
            ->addDataSource('pikachu')
            ->addDataSource('quagsire')
            ->addDataSource('product');
    }

    /** @test
     */
    public function it_can_add_filters()
    {
        $this->mr->addFilter('tracker_id', 50);
        $this->assertArrayHasKey('tracker_id', $this->mr->filters);

    }

    /** @test
     * @expectedException Exception
     */
    public function it_throws_exception_when_adding_invalid_filter()
    {
        $this->mr->addFilter('invalid_filter', 50);
    }

    /** @test
     */
    public function it_can_add_data_source_as_filter()
    {
        $this->mr->addDataSource('sub_id');
        $this->mr->addFilter('sub_id', 500);

        $this->assertArrayHasKey('sub_id', $this->mr->filters);
    }

    /** @test
     * @expectedException Exception
     */
    public function it_throws_exception_when_using_invalid_date()
    {
        $start = "2017-May-01";
        $end = "2017-06-01";
        $fr = new GlobalReport($start, $end);
    }

}
