<?php

use PHPUnit\Framework\TestCase;
use Fraudshield\Reports\FraudReport;

class FraudReportTest extends TestCase
{
    protected $fr;

    protected function setUp()
    {
        $this->fr = new FraudReport(1);
    }

    /** @test */
    public function it_can_generate_report_with_just_tracker_id()
    {
        $this->assertInstanceOf(FraudReport::class, $this->fr);
    }

    /** @test */
    public function it_sets_start_end_date_to_today_if_empty()
    {
        $fr = new FraudReport(1);
        $today = date("Y-m-d");

        $this->assertEquals($today, $fr->dateStart);
        $this->assertEquals($today, $fr->dateEnd);
    }

    /** @test */
    public function it_can_generate_report_with_tracker_id_and_start_date_and_end_date()
    {
        $start = "2017-05-01";
        $end = "2017-06-01";
        $fr = new FraudReport(1, $start, $end);

        $this->assertEquals(1, $fr->trackerId);
        $this->assertEquals($start, $fr->dateStart);
        $this->assertEquals($end, $fr->dateEnd);
    }

    /** @test */
    public function it_can_add_data_source()
    {
        $this->fr->addDataSource('sub_id');
        $this->assertContains('sub_id', $this->fr->dataSources);
    }

    /** @test */
    public function it_can_chaine_add_data_source()
    {
        $this->fr->addDataSource('sub_id')->addDataSource('partner');
        $this->assertContains('sub_id', $this->fr->dataSources);
        $this->assertContains('partner', $this->fr->dataSources);
    }

    /** @test
     * @expectedException Exception
     */
    public function it_throws_exception_when_adding_invalid_data_source()
    {
        $this->fr->addDataSource('some_invalid_data_source');
    }

    /** @test
     * @expectedException Exception
     */
    public function it_throws_exception_when_adding_more_than_allowed_number_data_source()
    {
        $this->fr
            ->addDataSource('Hendrik')
            ->addDataSource('was')
            ->addDataSource('here')
            ->addDataSource('¯\_(ツ)_/¯')
            ->addDataSource('you')
            ->addDataSource('only')
            ->addDataSource('live')
            ->addDataSource('once');
    }

    /** @test
     */
    public function it_can_add_filters()
    {
        $this->fr->addFilter('min_conversions', 50);
        $this->assertArrayHasKey('min_conversions', $this->fr->filters);
    }

    /** @test
     */
    public function it_can_chaine_add_filters()
    {
        $this->fr->addFilter('min_conversions', 50)->addFilter('min_rejection_rate', 10);
        $this->assertArrayHasKey('min_conversions', $this->fr->filters);
        $this->assertArrayHasKey('min_rejection_rate', $this->fr->filters);
    }

    /** @test
     * @expectedException Exception
     */
    public function it_throws_exception_when_adding_invalid_filter()
    {
        $this->fr->addFilter('invalid_filter', 50);
    }

    /** @test
     */
    public function it_can_add_data_source_as_filter()
    {
        $this->fr->addDataSource('sub_id');
        $this->fr->addFilter('sub_id', 500);
        $this->assertArrayHasKey('sub_id', $this->fr->filters);
    }

    /** @test
     * @expectedException Exception
     */
    public function it_throws_exception_when_using_invalid_date()
    {
        $start = "2017-May-01";
        $end = "2017-06-01";
        $fr = new FraudReport(1, $start, $end);
    }

}
