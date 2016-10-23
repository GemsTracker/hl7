<?php

/**
 *
 * @package    Gems
 * @subpackage HL7\Segment
 * @author     Matijs de Jong <mjong@magnafacta.nl>
 * @copyright  Copyright (c) 2016, Erasmus MC and MagnaFacta B.V.
 * @license    New BSD License
 */

namespace Gems\HL7\Segment;

use Gems\HL7\Segment;
use Gems\HL7\Type\CE;
use Gems\HL7\Type\PL;
use Gems\HL7\Type\TQ;
use Gems\HL7\Type\TS;
use Gems\HL7\Type\XAD;
use Gems\HL7\Type\XCN;

use PharmaIntelligence\HL7\Node\Field;

/**
 * SCH: Scheduling Activity Information
 *
 * The SCH segment contains general information about the scheduled appointment.
 *
 * See http://hl7-definition.caristix.com:9010
 *
 * SEQ	LENGTH	DT	OPT	RPT / #	TBL #	NAME
 * SCH.1	75	EI	C	1           Placer Appointment ID
 * SCH.2	75	EI	C	1           Filler Appointment ID
 * SCH.3	5	NM	C	1           Occurrence Number
 * SCH.4	22	EI	O	1           Placer Group Number
 * SCH.5	250	CE	O	1           Schedule ID
 * SCH.6	250	CE	R	1           Event Reason
 * SCH.7	250	CE	O	1	0276	Appointment Reason
 * SCH.8	250	CE	O	1	0277	Appointment Type
 * SCH.9	20	NM	O	1           Appointment Duration
 * SCH.10	250	CE	O	1           Appointment Duration Units
 * SCH.11	200	TQ	R	*           Appointment Timing Quantity
 * SCH.12	250	XCN	O	*           Placer Contact Person
 * SCH.13	250	XTN	O	1           Placer Contact Phone Number
 * SCH.14	250	XAD	O	*           Placer Contact Address
 * SCH.15	80	PL	O	1           Placer Contact Location
 * SCH.16	250	XCN	R	*           Filler Contact Person
 * SCH.17	250	XTN	O	1           Filler Contact Phone Number
 * SCH.18	250	XAD	O	*           Filler Contact Address
 * SCH.19	80	PL	O	1           Filler Contact Location
 * SCH.20	250	XCN	R	*           Entered By Person
 * SCH.21	250	XTN	O	*           Entered By Phone Number
 * SCH.22	80	PL	O	1           Entered by Location
 * SCH.23	75	EI	O	1           Parent Placer Appointment ID
 * SCH.24	75	EI	C	1           Parent Filler Appointment ID
 * SCH.25	250	CE	O	1	0278	Filler Status Code
 * SCH.26	22	EI	C	*           Placer Order Number
 * SCH.27	22	EI	C	*           Filler Order Number
 *
 * @package    Gems
 * @subpackage HL7\Segment
 * @copyright  Copyright (c) 2016 Erasmus MC and MagnaFacta BV
 * @license    New BSD License
 * @since      Class available since version 1.8.1 Oct 20, 2016 404661
 */
class SCHSegment extends Segment
{
    const IDENTIFIER = 'SCH';

    /**
     *
     * @var TQ
     */
    protected $_timingQuantity;

    /**
     *
     * @param string $segmentName
     */
    public function __construct($segmentName = self::IDENTIFIER)
    {
        parent::__construct($segmentName);
    }

    /**
     *
     * @param int $idx
     * @return CE
     */
    protected function _getCE($idx)
    {
        $items = $this->get($idx);

        if ($items instanceof Field) {
            return new CE($items->current());
        }
    }

    /**
     *
     * @param type $idx
     * @return XCN
     */
    protected function _getXCN($idx)
    {
        $items = $this->get($idx);

        return new XCN(reset($items));
    }

    /**
     * Load the timing quantity object and attempts to set the duration if empty
     */
    protected function _loadTimingQuantity()
    {
        $this->_timingQuantity = new TQ($this->get(11, 0));

        if (! $this->_timingQuantity->getDuration()) {
            $duration = $this->getAppointmentDuration() . $this->getAppointmentDurationUnit();
            if ($duration) {
                $this->_timingQuantity->setDuration($duration);
            }
        }
    }

    /**
     * E.g. 0007768281
     *
     * @return string
     */
    public function getPlacerAppointmentId()
    {
        return (string) $this->get(1);
    }

    /**
     *
     * @return string
     */
    public function getFillerAppointmentId()
    {
        return (string) $this->get(2);
    }

    /**
     *
     * @return int
     */
    public function getOccurrenceNumber()
    {
        return (int) ((string) $this->get(3));
    }

    /**
     *
     * @return string
     */
    public function getPlacerGroupNumber()
    {
        return (string) $this->get(4);
    }

    /**
     * Schedule = Agenda
     *
     * E.g. S01786 / S02191
     *
     * @return string
     */
    public function getScheduleId()
    {
        return $this->_getCE(5)->getId();
    }

    /**
     * Schedule = Agenda
     *
     * E.g. KLINIEK 1B-1B / DERMATOLOGIE-HERDER-HOEKSTRA Heerenveen
     *
     * @return string
     */
    public function getScheduleName()
    {
        return $this->_getCE(5)->getText();
    }

    /**
     *
     * @return CE
     */
    public function getEventReason()
    {
        return $this->_getCE(6);
    }

    /**
     *
     * @return CE
     */
    public function getAppointmentReason()
    {
        return $this->_getCE(7);
    }

    /**
     * E.g. SPCON / DUDER
     *
     * @return atring
     */
    public function getAppointmentTypeId()
    {
        return $this->_getCE(8)->getId();
    }

    /**
     * E.g. spcon / Duplex derma
     *
     * @return atring
     */
    public function getAppointmentTypeText()
    {
        return $this->_getCE(8)->getText();
    }

    /**
     * E.g. 30 / 10
     *
     * @return int
     */
    public function getAppointmentDuration()
    {
        $duration = (string) $this->get(9);

        if ('M' == strtoupper($duration[0])) {
            return intval(substr($duration, 1));
        }
        return intval($duration);
    }


    /**
     * E.g. S, / M
     *
     * @return string
     */
    public function getAppointmentDurationUnit()
    {
        $unit = (string) $this->get(10);

        if ($unit) {
            return $unit[0];
        }

        $duration = (string) $this->get(9);

        return $duration[0];
    }

    /**
     * E.g. ^^^201602111532 / ^^^201602191435
     *
     * @return TS SCH|[1]0007768281|[2]|[3]|[4]|[5]S01786^KLINIEK 1B-1B|[6]|[7]|[8]SPCON^spcon|[9]M30|[10]|[11]^^^201602111532|[12]027881|[13]|[14]|[15]|[16]I0X|[17]|[18]|[19]|[20]000
     */
    public function getAppointmentStartDatetime()
    {
        if (! $this->_timingQuantity) {
            $this->_loadTimingQuantity();
        }

        return $this->_timingQuantity->getStartTime();
    }

    /**
     * E.g. ^^^201602111532 / ^^^201602191435
     *
     * @return TS SCH|[1]0007768281|[2]|[3]|[4]|[5]S01786^KLINIEK 1B-1B|[6]|[7]|[8]SPCON^spcon|[9]M30|[10]|[11]^^^201602111532|[12]027881|[13]|[14]|[15]|[16]I0X|[17]|[18]|[19]|[20]000
     */
    public function getAppointmentEndDatetime()
    {
        if (! $this->_timingQuantity) {
            $this->_loadTimingQuantity();
        }
        return $this->_timingQuantity->getEndTime();
    }

    /**
     * E.g. 027881 / 5261
     *
     * @return XCN
     */
    public function getContactPerson()
    {
        return $this->_getXCN(12);
    }

    /**
     *
     * @return XTN
     */
    public function getContactPhone()
    {
        return $this->_getXTN(13);
    }

    /**
     *
     * @return XAD
     */
    public function getContactAddress()
    {
        $item = $this->get(14, 0);

        if ($item) {
            return new XAD($item);
        }
    }

    /**
     *
     * @return PL SCH|[1]0007768281|[2]|[3]|[4]|[5]S01786^KLINIEK 1B-1B|[6]|[7]|[8]SPCON^spcon|[9]M30|[10]|[11]^^^201602111532|[12]027881|[13]|[14]|[15]|[16]I0X|[17]|[18]|[19]|[20]000
     */
    public function getContactLocation()
    {
        $item = $this->get(15, 0);

        if ($item) {
            return new PL($item);
        }
    }

    /**
     * E.g. I0X / BHF
     *
     * @return XCN
     */
    public function getFillerContact()
    {
        return $this->_getXCN(16);
    }

    /**
     *
     * @return PL
     */
    public function getFillerLocation()
    {
        $item = $this->get(19, 0);

        if ($item) {
            return new PL($item);
        }
    }

    /**
     * E.g. 000
     *
     * @return XCN
     */
    public function getEnteredBy()
    {
        return $this->_getXCN(20);
    }
}
