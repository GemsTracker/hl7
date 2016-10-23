<?php

namespace Gems\HL7\Segment;

use DateTime;
use Gems\HL7\Type\TS;
use PharmaIntelligence\HL7\Unserializer;
use PHPUnit_Framework_TestCase;

/**
 * Generated by PHPUnit_SkeletonGenerator on 2016-02-12 at 09:06:41.
 */
class SCHSegmentTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var \Gems\HL7\Segment\PIDSegment
     */
    protected $pid;

    /**
     * @var \Gems\HL7\Segment\SCHSegment
     */
    protected $sch;


    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp() {
        $file         = 'resources/siu-s14-1-msg.txt';
        $testHl7      = file_get_contents($file);
        $unserializer = new Unserializer();
        $map          = array(
            'PID' => 'Gems\HL7\Segment\PIDSegment',
            'SCH' => 'Gems\HL7\Segment\SCHSegment',
        );
        $message   = $unserializer->loadMessageFromString($testHl7, $map);
        $schs      = $message->getSegmentsByName('SCH');
        $this->sch = $schs[0];
        $pids      = $message->getSegmentsByName('PID');
        $this->pid = $pids[0];
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {

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
