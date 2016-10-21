<?php

/**
 *
 * @package    Gems
 * @subpackage HL7\Node
 * @author     Matijs de Jong <mjong@magnafacta.nl>
 * @copyright  Copyright (c) 2016 Erasmus MC and MagnaFacta BV
 * @license    No free license, do not copy
 */

namespace Gems\HL7\Node;

use Gems\HL7\Segment;
use PharmaIntelligence\HL7\Node\Message as PharmaMessage;

/**
 *
 *
 * @package    Gems
 * @subpackage HL7\Node
 * @copyright  Copyright (c) 2016 Erasmus MC and MagnaFacta BV
 * @license    Not licensed, do not copy
 * @since      Class available since version 1.8.1 Oct 20, 2016 404661
 */
class Message extends PharmaMessage
{
    /**
     *
     * @return \Gems\HL7\Segment\MSHSegment
     */
    public function getMshSegment()
    {
        return $this->getSegmentByName('MSH');
    }

    /**
     *
     * @return \Gems\HL7\Segment\PIDSegment
     */
    public function getPidSegment()
    {
        return $this->getSegmentByName('PID');
    }

    /**
     *
     * @param string $segmentName
     * @return \Gems\HL7\Segment
     */
    public function getSegmentByName($segmentName)
    {
        foreach ($this->children as $segment) {
           if (($segment instanceof Segment) && ($segment->getSegmentName() === $segmentName)) {
               return $segment;
           }
        }
    }
}
