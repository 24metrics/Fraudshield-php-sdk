<?php

use PHPUnit\Framework\TestCase;
use Fraudshield\Reports\EntryReport;

class EntryReportTest extends TestCase
{
    protected $er;

    protected function setUp()
    {
        $this->er = new EntryReport('conversion', 'FS_59412b282a0150.92227388');
    }

    /** @test */
    public function it_can_generate_report()
    {
        $this->assertInstanceOf(EntryReport::class, $this->er);
    }
}
