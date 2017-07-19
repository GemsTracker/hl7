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
use Gems\HL7\Segment\MSHSegment;
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
        /* @var $msh MSHSegment */
        $msh         = array_shift($mshs);
        
        // To copy the segment, we create a newMSH segment, and add a new field with a repetition that hold the string value from the incoming MSH
        $class = get_class($msh);
        $mshResponse = new $class('MSH');
        foreach($msh as $field) {
            $class = get_class($field);            
            $newField = new $class();            
            $mshResponse->append($newField);
            $class = get_class($field->children[0]);
            $newRepetition = new $class($field->__toString());
            $newField->append($newRepetition);
        }

        $msgType = $msh->getMessageType();

        $mshResponse->setMessageType('ACK^' . $msgType->getTriggerEvent()); // Return second part
        
        // Flip sending and receiving application/facility
        $receivedApplication = (string) $msh->getSendingApplication();
        $receivedFacility    = (string) $msh->getSendingFacility();
        $sendingApplication  = (string) $msh->getReceivingApplication();
        $sendingFacility     = (string) $msh->getReceivingFacility();
        $mshResponse->setReceivingApplication($receivedApplication);
        $mshResponse->setReceivingFacility($receivedFacility);
        $mshResponse->setSendingApplication($sendingApplication);
        $mshResponse->setSendingFacility($sendingFacility);

        // Add the MSA segment
        $ackSegment = new MSASegment();
        $this->append($mshResponse);
        $this->append($ackSegment);
        $ackSegment->setAcknowledgementCode($responseCode);
        $ackSegment->setMessageControlId((string) $msh->getMessageControlId());
    }

}