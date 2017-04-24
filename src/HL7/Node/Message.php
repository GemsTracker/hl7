<?php

/**
 *
 * @package    Gems
 * @subpackage HL7\Node
 * @author     Matijs de Jong <mjong@magnafacta.nl>
 * @copyright  Copyright (c) 2016 Erasmus MC and MagnaFacta BV
 * @license    New BSD License
 */

namespace Gems\HL7\Node;

use PharmaIntelligence\HL7\Node\Segment;
use PharmaIntelligence\HL7\Node\Message as PharmaMessage;

/**
 *
 *
 * @package    Gems
 * @subpackage HL7\Node
 * @copyright  Copyright (c) 2016 Erasmus MC and MagnaFacta BV
 * @license    New BSD License
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
     * @return \Gems\HL7\Segment\NTESegment
     */
    public function getNteSegment()
    {
        return $this->getSegmentByName('NTE');
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
     * @return \Gems\HL7\Segment\SCHSegment
     */
    public function getSchSegment()
    {
        return $this->getSegmentByName('SCH');
    }

    /**
     *
     * @param string $segmentName
     * @return \Gems\HL7\Segment
     */
    public function getSegmentByName($segmentName)
    {
        // echo '-- ' . $segmentName . "\n";
        foreach ($this->children as $segment) {
            // echo $segment->getSegmentName() . ' - ' . get_class($segment) . "\n";
            if (($segment instanceof Segment) && ($segment->getSegmentName() == $segmentName)) {
                return $segment;
            }
        }
    }
}
