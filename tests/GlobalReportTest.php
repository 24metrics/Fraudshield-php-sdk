<?php

use PHPUnit\Framework\TestCase;
use Fraudshield\Reports\GlobalReport;

class GlobalReportTest extends TestCase
{
    protected $gr;

    protected function setUp()
    {
        $this->gr = new GlobalReport(1);
    }

    /** @test */
    public function it_can_generate_report_with_just_tracker_id()
    {
        $this->assertInstanceOf(GlobalReport::class, $this->gr);
    }

    /** @test */
    public function it_sets_start_end_date_to_today_if_empty()
    {
        $gr = new GlobalReport(1);
        $today = date("Y-m-d");
        $this->assertEquals($today, $gr->dateStart);
        $this->assertEquals($today, $gr->dateEnd);
    }

    /** @test */
    public function it_can_generate_report_with_tracker_id_and_start_date_and_end_date()
    {
        $start = "2017-05-01";
        $end ="2017-06-01";
        $gr = new GlobalReport(1, $start, $end);
        $this->assertEquals(1, $gr->trackerId);
        $this->assertEquals($start, $gr->dateStart);
        $this->assertEquals($end, $gr->dateEnd);
    }

    /** @test */
    public function it_can_add_data_source()
    {
        $this->gr->addDataSource('sub_id');
        $this->assertContains('sub_id', $this->gr->dataSources);
    }

    /** @test */
    public function it_can_chaine_add_data_source()
    {
        $this->gr->addDataSource('sub_id')->addDataSource('partner');
        $this->assertContains('sub_id', $this->gr->dataSources);
        $this->assertContains('partner', $this->gr->dataSources);
    }

    /** @test 
    * @expectedException Exception
    */
    public function it_throws_exception_when_adding_invalid_data_source()
    {
        $this->gr->addDataSource('some_invalid_data_source');
    }

    /** @test 
    * @expectedException Exception
    */
    public function it_throws_exception_when_adding_more_than_allowed_number_data_source()
    {
        $this->gr->addDataSource('sub_id')->addDataSource('partner')->addDataSource('affiliate')->addDataSource('product');
    }

    /** @test 
    */
    public function it_can_add_filters()
    {
        $this->gr->addFilter('tracker_id', 50);
        $this->assertArrayHasKey('tracker_id', $this->gr->filters);

    }

    /** @test 
    * @expectedException Exception
    */
    public function it_throws_exception_when_adding_invalid_filter()
    {
        $this->gr->addFilter('invalid_filter', 50);
    }

    /** @test 
    */
    public function it_can_add_data_source_as_filter()
    {
        $this->gr->addDataSource('sub_id');
        $this->gr->addFilter('sub_id', 500);        
        $this->assertArrayHasKey('sub_id', $this->gr->filters);
    }

    /** @test 
    * @expectedException Exception
    */
    public function it_throws_exception_when_using_invalid_date()
    {
        $start = "2017-May-01";
        $end ="2017-06-01";
        $fr = new FraudReport(1, $start, $end);
    }


}