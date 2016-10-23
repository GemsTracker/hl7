<?php

/**
 *
 * @package    Gems
 * @subpackage HL7\Message
 * @author     Menno Dekker <menno.dekker@erasmusmc.nl>
 * @copyright  Copyright (c) 2016 Erasmus MC and MagnaFacta BV
 * @license    New BSD License
 */

namespace Gems\HL7\Message;

use Gems\HL7\Segment\MSASegment;
use PharmaIntelligence\HL7\Node\Message;

/**
 * ACK: General acknowledgment message
 *
 * See http://hl7-definition.caristix.com:9010
 *
 * SEQ	OPT	RPT / #	GROUP	NAME
 * 1	R	1		MSH - Message Header
 * 2	R	1		MSA - Message Acknowledgment
 * 3	O	1		ERR - Error
 *
 * @package    Gems
 * @subpackage HL7\Message
 * @author     Menno Dekker <menno.dekker@erasmusmc.nl>
 * @copyright  Copyright (c) 2016 Erasmus MC and MagnaFacta BV
 * @since      Class available since version 1.8.1 Oct 20, 2016 404661
 */
class ACK extends Message {

    /**
     * Simple ACK generator
     *
     * @param type $incomingMessage to extract the MSH
     */
    public function __construct(Message $incomingMessage, $responseCode = "AA") {
        $this->escapeSequences['cursor_return'] = chr(13);  // Fix incorrect setting;

        $mshs        = $incomingMessage->getSegmentsByName('MSH');
        /* @var $msh Segment\MSHSegment */
        $msh         = array_shift($mshs);
        $mshResponse = clone $msh;

        $msgType = $msh->getMessageType();

        $mshResponse->setMessageType('ACK^' . $msgType[1]); // Return second part
        $receivedApplication = $msh->getSendingApplication();
        $receivedFacility    = $msh->getSendingFacility();
        $sendingApplication  = $msh->getReceivingApplication();
        $sendingFacility     = $msh->getReceivingFacility();
        $mshResponse->setReceivingApplication($receivedApplication);
        $mshResponse->setReceivingFacility($receivedFacility);
        $mshResponse->setSendingApplication($sendingApplication);
        $mshResponse->setSendingFacility($sendingFacility);

        $ackSegment = new MSASegment();
        $this->append($mshResponse);
        $this->append($ackSegment);
        $ackSegment->setAcknowledgementCode($responseCode);
        $ackSegment->setMessageControlId((string) $msh->getMessageControlId());
    }

}