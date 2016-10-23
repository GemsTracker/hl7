<?php

/**
 *
 * @package    Gems
 * @subpackage HL7\Type
 * @author     Matijs de Jong <mjong@magnafacta.nl>
 * @copyright  Copyright (c) 2016, Erasmus MC and MagnaFacta B.V.
 * @license    New BSD License
 */

namespace Gems\HL7\Type;

use Gems\HL7\Type;
use PharmaIntelligence\HL7\Node\Field;
/**
 * TQ: Timing Quantity
 *
 * Describes when a service should be performed and how frequently.
 *
 * SEQ	LENGTH	DT	OPT	TBL #	NAME
 * TQ.1	267	CQ	O		Quantity
 * TQ.2	206	RI	O		Interval
 * TQ.3	6	ST	O		Duration
 * TQ.4	26	TS	O		Start Date/Time
 * TQ.5	26	TS	O		End Date/Time
 * TQ.6	6	ST	O		Priority
 * TQ.7	199	ST	O		Condition
 * TQ.8	200	TX	O		Text
 * TQ.9	1	ID	O	0472	Conjunction
 * TQ.10	110	OSD	O		Order Sequencing
 * TQ.11	483	CE	O		Occurrence Duration
 * TQ.12	4	NM	O		Total Occurrences
 *
 * See http://hl7-definition.caristix.com:9010
 *
 * @package    Gems
 * @subpackage HL7\Type
 * @copyright  Copyright (c) 2016, Erasmus MC and MagnaFacta B.V.
 * @license    New BSD License
 * @since      Class available since version 1.8.1 Oct 23, 2016 2:49:44 PM
 */
class TQ extends Type
{
    /**
     *
     * @var string
     */
    protected $_duration;

    /**
     *
     * @var TS
     */
    protected $_endTime;

    /**
     *
     * @return string
     */
    public function getQuantity()
    {
        return (string) $this->_get(1);
    }

    /**
     *
     * @return string
     */
    public function getInterval()
    {
        return (int) ((string) $this->_get(2));
    }

    /**
     *
     * @return string
     */
    public function getDuration()
    {
        if (! $this->_duration) {
            $this->_duration = (string) $this->_get(3);
        }

        return $this->_duration;
    }

    /**
     *
     * @return TS
     */
    public function getStartTime()
    {
        $start = $this->_get(4);
        if ($start) {
            return new TS($start);
        }
    }

    /**
     *
     * @return TS
     */
    public function getEndTime()
    {
        if ($this->_endTime) {
            return $this->_endTime;
        }

        $endTime = $this->_get(5);
        if (! $endTime) {
            $endTime = new Field();
            $endTime->setParent($this->content);
        }
        $this->_endTime = new TS($endTime);

        if ($this->_endTime->exists()) {
            return $this->_endTime;
        }

        // Either the end time is set or the duration
        $duration  = $this->getDuration();
        $startTime = $this->getStartTime();
        if (! ($duration && $startTime->exists())) {
            return $this->_endTime;
        }

        $newTime = clone $startTime->getObject();
        $newTime->add(new \DateInterval('PT' . $duration));

        $this->_endTime->setObject($newTime);

        return $this->_endTime;
    }

    /**
     *
     * @param string
     */
    public function setDuration($duration)
    {
        $this->_duration = $duration;
        $this->_endTime  = null;
    }
}
