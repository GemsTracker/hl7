<?php

/**
 *
 * @package    Gems
 * @subpackage HL7\Segment
 * @author     Menno Dekker <menno.dekker@erasmusmc.nl>
 * @copyright  Copyright (c) 2016, Erasmus MC and MagnaFacta B.V.
 * @license    New BSD License
 */

namespace Gems\HL7\Segment;

use DateTime;
use Gems\HL7\Segment;
use Gems\HL7\Type\MSG;
use Gems\HL7\Type\TS;

/**
 * MSH segment
 *
 * Special: index should be -1 since we miss the first field setting the separator
 *
 * See http://hl7-definition.caristix.com:9010
 *
 * SEQ	LENGTH	DT	OPT	RPT / #	TBL #	NAME
 * MSH.1	1	ST	R	1		Field Separator
 * MSH.2	4	ST	R	1		Encoding Characters
 * MSH.3	227	HD	O	1	0361	Sending Application
 * MSH.4	227	HD	O	1	0362	Sending Facility
 * MSH.5	227	HD	O	1	0361	Receiving Application
 * MSH.6	227	HD	O	1	0362	Receiving Facility
 * MSH.7	26	TS	R	1		Date/Time Of Message
 * MSH.8	40	ST	O	1		Security
 * MSH.9	15	MSG	R	1		Message Type
 * MSH.10	20	ST	R	1		Message Control ID
 * MSH.11	3	PT	R	1		Processing ID
 * MSH.12	60	VID	R	1		Version ID
 * MSH.13	15	NM	O	1		Sequence Number
 * MSH.14	180	ST	O	1		Continuation Pointer
 * MSH.15	2	ID	O	1	0155	Accept Acknowledgment Type
 * MSH.16	2	ID	O	1	0155	Application Acknowledgment Type
 * MSH.17	3	ID	O	1	0399	Country Code
 * MSH.18	16	ID	O	*	0211	Character Set
 * MSH.19	250	CE	O	1		Principal Language Of Message
 * MSH.20	20	ID	O	1	0356	Alternate Character Set Handling Scheme
 * MSH.21	427	EI	O	*		Message Profile Identifier
 *
 * @package    Gems
 * @subpackage HL7\Segment
 * @copyright  Copyright (c) 2016 Erasmus MC and MagnaFacta BV
 * @license    New BSD License
 * @since      Class available since version 1.8.1 Oct 20, 2016 404661
 */
class MSHSegment extends Segment
{
    const IDENTIFIER = 'MSH';
    
    public function getCharacterset() {
        if (count($this->children) > 16) {
            return $this->children[16][0]->value;
        } 
        
        // Fallback, try to detect encoding
        return \mb_detect_encoding($this->__toString(), "auto");
    }

    public function getSendingApplication() {
        return $this->children[1][0]->value;
    }

    public function getSendingFacility()
    {
        return $this->children[2][0]->value;
    }

    /**
     *
     * @return string
     */
    public function getSendingOrganizationId()
    {
        $orgData = $this->getSendingApplication();
        if (is_countable($orgData) && count($orgData) > 1) {
            return (string) $orgData[2];
        }
        return (string) $orgData;
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

    /**
     * 
     * @return MSG
     */
    public function getMessageType() {
        return new MSG($this->children[7][0]);
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