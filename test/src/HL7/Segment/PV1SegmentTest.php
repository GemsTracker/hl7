<?php

namespace Gems\HL7\Segment;

use Gems\HL7\Test\TestAbstract;
use const TEST_DIR;

/**
 * Generated by PHPUnit_SkeletonGenerator on 2016-02-25 at 08:38:48.
 */
class PV1SegmentTest extends TestAbstract
{

    /**
     * @var PV1Segment
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        parent::setUp();

        $file         = TEST_DIR . '/resources/orm.txt';
        $message      = $this->_getMessageFromFile($file);
        $pv1s         = $message->getSegmentsByName('PV1');
        $this->object = $pv1s[0];
    }

    /**
     * @covers Gems\HL7\Segment\PV1Segment::getSetId
     */
    public function testGetSetId()
    {
        $this->assertEquals(1, $this->object->getSetId());
    }

    /**
     * @covers Gems\HL7\Segment\PV1Segment::getPatientClass
     */
    public function testGetPatientClass()
    {
        $this->assertEquals('I', $this->object->getPatientClass());
    }

    /**
     * @covers Gems\HL7\Segment\PV1Segment::getPatientLocation()
     */
    public function testGetPatientLocation()
    {
        $location = $this->object->getPatientLocation();
        $expected = "4ZOB^SK4166^01";
        $this->assertEquals($expected, (string) $this->object->getPatientLocation());
    }

    /**
     * @covers Gems\HL7\Segment\PV1Segment::getAdmissionType()
     */
    public function testGetAdmissionType()
    {
        $this->assertEquals('R', $this->object->getAdmissionType());
    }

}
