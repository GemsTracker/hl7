<?php

namespace Gems\HL7\Segment;

use Gems\HL7\Test\TestAbstract;
use const TEST_DIR;

/**
 * Generated by PHPUnit_SkeletonGenerator on 2016-02-12 at 12:28:21.
 */
class EVNSegmentTest extends TestAbstract
{

    /**
     * @var EVNSegment
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        parent::setup();

        $file         = TEST_DIR . '/resources/orm.txt';
        $message      = $this->_getMessageFromFile($file);
        $evns         = $message->getSegmentsByName('EVN');
        $this->object = $evns[0];
    }

    /**
     * @covers Gems\HL7\Segment\EVNSegment::getEventTypeCode
     */
    public function testGetEventTypeCode()
    {
        $this->assertEquals('A08', $this->object->getEventTypeCode());
    }

    /**
     * @covers Gems\HL7\Segment\EVNSegment::getRecordedDateTimestamp
     */
    public function testGetRecordedDateTimestamp()
    {
        $this->assertEquals('20170103075310', $this->object->getRecordedDateTimestamp()->getDateTime());
    }

    /**
     * @covers Gems\HL7\Segment\EVNSegment::getPlannedDateTimestamp
     * @todo   Implement testGetRecordedDateTimeStamp()
     * /
      public function testGetPlannedDateTimestamp() {
      // Remove the following lines when you implement this test.
      $this->markTestIncomplete(
      'This test has not been implemented yet.'
      );
      }

      /**
     * @covers Gems\HL7\Segment\EVNSegment::getEventReasonCode
     * @todo   Implement testGetEventReasonCode().
     * /
      public function testGetEventReasonCode() {
      // Remove the following lines when you implement this test.
      $this->markTestIncomplete(
      'This test has not been implemented yet.'
      );
      }

      /**
     * @covers Gems\HL7\Segment\EVNSegment::getOperatorId
     * @todo   Implement testGetOperatorId().
     * /
      public function testGetOperatorId() {
      // Remove the following lines when you implement this test.
      $this->markTestIncomplete(
      'This test has not been implemented yet.'
      );
      }

      /**
     * @covers Gems\HL7\Segment\EVNSegment::getEventOccurredDateTimestamp
     */
    public function testGetEventOccurredDateTimestamp()
    {
        $this->assertEquals('20170103075309', $this->object->getEventOccurredDateTimestamp()->getDateTime());
    }

    /**
     * @covers Gems\HL7\Segment\EVNSegment::getEventFacility
     * @todo   Implement testGetEventFacility().
     * /
      public function testGetEventFacility() {
      // Remove the following lines when you implement this test.
      $this->markTestIncomplete(
      'This test has not been implemented yet.'
      );
      }
      // */
}
