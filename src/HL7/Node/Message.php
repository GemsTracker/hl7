<?php

/**
 *
 * @package    HL7
 * @subpackage Gems\HL7
 * @author     Matijs de Jong <mjong@magnafacta.nl>
 * @copyright  Copyright (c) 2016 hl7
 * @license    No free license, do not copy
 */

namespace Gems\HL7\Node;

use PharmaIntelligence\HL7\Node\Message as PharmaMessage;

/**
 *
 *
 * @package    HL7
 * @subpackage Gems\HL7
 * @copyright  Copyright (c) 2016 hl7
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
        $mshs = $this->getSegmentsByName('MSH');

        return reset($mshs);
    }
}
