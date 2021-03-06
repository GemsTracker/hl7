<?php

namespace Gems\HL7\Segment;

use Gems\HL7\Test\TestAbstract;
use const TEST_DIR;

/**
 * Generated by PHPUnit_SkeletonGenerator on 2016-02-12 at 09:06:41.
 */
class SCHSegmentTest extends TestAbstract
{

    /**
     * @var PIDSegment
     */
    protected $pid;

    /**
     * @var SCHSegment
     */
    protected $sch;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        parent::setUp();

        $file      = TEST_DIR . '/resources/siu-s14-1-msg.txt';
        $message   = $this->_getMessageFromFile($file);
        $schs      = $message->getSegmentsByName('SCH');
        $this->sch = $schs[0];
        $pids      = $message->getSegmentsByName('PID');
        $this->pid = $pids[0];
    }

    public function testPlacerAppointmentId()
    {
        $this->assertEquals('0007768281', $this->sch->getPlacerAppointmentId());
    }

    public function testStartTime()
    {
        $this->assertEquals('2016-02-11T15:32:00+01:00', $this->sch->getAppointmentStartDatetime()->getFormatted('c'));
    }

    public function testEndTime()
    {
        $this->assertEquals('2016-02-11T16:02:00+01:00', $this->sch->getAppointmentEndDatetime()->getFormatted('c'));
    }

}
