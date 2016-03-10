<?php

namespace Gems\HL7\Segment;

use DateTime;
use Gems\HL7\Segment;
use Gems\HL7\Type\TS;

/**
 * Special: index should be -1 since we miss the first field setting the separator
 */
class MSHSegment extends Segment
{
    const IDENTIFIER = 'MSH';
        
    public function getSendingApplication() {
        return $this->children[1][0]->value;
    }
    
    public function getSendingFacility() {
        return $this->children[2][0]->value;
    }
    
    public function getReceivingApplication() {
        return $this->children[3][0]->value;
    }
    
    public function getReceivingFacility() {
        return $this->children[4][0]->value;
    }
    
    /**
     * 
     * @return TS
     */
    public function getDateTimeOfMessage() {
        return new TS($this->get(6,0));
    }
    
    public function getMessageType() {
        return $this->children[7][0];
    }
    
    public function getMessageControlId() {
        return $this->children[8][0];
    }
    
    public function getProcessingId() {
        return $this->children[9][0];
    }
    
    public function getVersionId() {
        return $this->children[10][0];
    }
    
    public function setSendingApplication($string) {
        $this->children[1][0]->value = $string;
    }
    
    public function setSendingFacility($string) {
        $this->children[2][0]->value = $string;
    }
    
    public function setReceivingApplication($string) {
        $this->children[3][0]->value = $string;
    }
    
    public function setReceivingFacility($string) {
        $this->children[4][0]->value = $string;
    }
    
    public function setDateTimeOfMessage(DateTime $dateTimeObj) {
        $this->children[5][0]->value = $dateTimeObj;
    }
    
    public function setMessageType($string) {
        $this->children[7][0] = $string;
    }
    
    public function setMessageControlId($string) {
        $this->children[8][0] = $string;
    }
    
    public function setProcessingId($string) {
        $this->children[9][0] = $string;
    }
    
    public function setVersionId($string) {
        $this->children[10][0] = $string;
    }
}