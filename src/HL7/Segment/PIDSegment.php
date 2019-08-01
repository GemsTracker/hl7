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

use Gems\HL7\Segment;
use Gems\HL7\Type\CX;
use Gems\HL7\Type\TS;
use Gems\HL7\Type\XAD;
use Gems\HL7\Type\XPN;
use Gems\HL7\Type\XTN;

/**
 * PID segment
 *
 * See http://hl7-definition.caristix.com:9010
 *
 * SEQ	LEN	DT	OPT	RP/#	TBL#	ITEM#	ELEMENT NAME
 * 1	4	SI	O               00104	Set ID - PID
 * 2	20	CX	B               00105	Patient ID
 * 3	250	CX	R	Y           00106	Patient Identifier List
 * 4	20	CX	B	Y           00107	Alternate Patient ID - PID
 * 5	250	XPN	R	Y           00108	Patient Name
 * 6	250	XPN	O	Y           00109	Mother’s Maiden Name
 * 7	26	TS	O               00110	Date/Time of Birth
 * 8	1	IS	O       0001    00111	Administrative Sex
 * 9	250	XPN	B	Y           00112	Patient Alias
 * 10	250	CE	O	Y   0005    00113	Race
 * 11	250	XAD	O	Y           00114	Patient Address
 * 12	4	IS	B		0289	00115	County Code
 * 13	250	XTN	O	Y           00116	Phone Number - Home
 * 14	250	XTN	O	Y           00117	Phone Number - Business
 * 15	250	CE	O		0296	00118	Primary Language
 * 16	250	CE	O		0002    00119	Marital Status
 * 17	250	CE	O		0006    00120	Religion
 * 18	250	CX	O               00121	Patient Account Number
 * 19	16	ST	B               00122	SSN Number - Patient
 * 20	25	DLN	O               00123	Driver's License Number - Patient
 * 21	250	CX	O	Y           00124	Mother's Identifier
 * 22	250	CE	O	Y	0189    00125	Ethnic Group
 * 23	250	ST	O               00126	Birth Place
 * 24	1	ID	O		0136	00127	Multiple Birth Indicator
 * 25	2	NM	O               00128	Birth Order
 * 26	250	CE	O	Y	0171	00129	Citizenship
 * 27	250	CE	O		0172	00130	Veterans Military Status
 * 28	250	CE	B		0212	00739	Nationality
 * 29	26	TS	O               00740	Patient Death Date and Time
 * 30	1	ID	O		0136	00741	Patient Death Indicator
 * 31	1	ID	O		0136	01535	Identity Unknown Indicator
 * 32	20	IS	O	Y	0445    01536	Identity Reliability Code
 * 33	26	TS	O               01537	Last Update Date/Time
 * 34	40	HD	O               01538	Last Update Facility
 * 35	250	CE	C		0446    01539	Species Code
 * 36	250	CE	C		0447    01540	Breed Code
 * 37	80	ST	O			0   1541	Strain
 * 38	250	CE	O	2	0429    01542	Production Class Code
 *
 * @package    Gems
 * @subpackage HL7\Segment
 * @copyright  Copyright (c) 2016 Erasmus MC and MagnaFacta BV
 * @license    New BSD License
 * @since      Class available since version 1.8.1 Oct 20, 2016 404661
 */
class PIDSegment extends Segment {

    const IDENTIFIER = 'PID';

    public function __construct($segmentName = self::IDENTIFIER) {
        parent::__construct($segmentName);
    }

    /**
     *
     * @param type $idx
     * @return array of XPN
     */
    protected function _getXPN($idx)
    {
        $result = array();
        if($items = $this->get($idx)) {
            foreach ($items as $item) {
                $result[] = new XPN($item);
            }
        }

        return $result;
    }

    /**
     *
     * @param type $idx
     * @return XTN[]
     */
    protected function _getXTN($idx)
    {
        $result = array();
        if($items = $this->get($idx)) {
            foreach ($items as $item) {
                $result[] = new XTN($item);
            }
        }

        return $result;
    }

    /**
     * Iternal helper function
     *
     * @see XTN
     *
     * @param XTN[] $items Array of XTNs
     * @param string $code
     * @param string $type
     * @return XTN|null
     */
    protected function _getXTNByType($items, $code, $type)
    {
        // If no specific item asked, just return the first
        if (is_null($code) && is_null($type)) {
            return reset($items);
        }

        foreach($items as $xtn)
        {
            if ((is_null($code) || $xtn->getUseCode() == $code) && (is_null($type) || $xtn->getEquipmentType() == $type)) {
                return $xtn;
            }
        }

        return null;
    }

    /**
     *
     * @return CX[]
     */
    public function getAlternatePatientID() {
        $result = array();
        foreach ($this->get(4) as $item) {
            $result[] = new CX($item);
        }

        return $result;
    }

    /**
     *
     * @return TS
     */
    public function getBirthDateTime()
    {
        return new TS($this->get(7,0));
    }

    /**
     * Death indicator
     *
     * VALUE	LABEL
     * Y        Yes
     * N        No
     *
     * @return TS
     */
    public function getDeathIndicator()
    {
        return (string) $this->get(30,0);
    }

    /**
     *
     * @return TS
     */
    public function getDeathDateTime()
    {
        return new TS($this->get(29,0));
    }

    /**
     *
     * @return string
     */
    public function getEmailAddress()
    {
        // Get all 'phones'
        $items = array_merge($this->getPhonehomeList() + $this->getPhoneBusinessList());
        foreach ($items as $phone) {
            if ($phone instanceof XTN) {
                $email = $phone->getEmailAddress();

                if ($email) {
                    return $email;
                }
            }
        }

        return null;
    }

    /**
     *
     * @return TS
     */
    public function getLastUpdateDateTime()
    {
        return new TS($this->get(33,0));
    }

    /**
     *
     * @return ZAD
     */
    public function getMailingAddress()
    {
        $item = $this->get(11, 0);

        if ($item) {
            return new XAD($item);
        }
    }

    /**
     *
     * @return XPN
     */
    public function getMotherMaidenName()
    {
        return $this->_getXPN(6);
    }

    /**
     * Get the patient id for a specific authority
     *
     * @param string $authority Authority used for patient id
     * @param string $typecode  Typecode used for patient id
     * @return CX or null
     */
    public function getPatientCxFor($authority, $typecode = null)
    {
        if (! $authority) {
            return null;
        }
        foreach ($this->getPatientIdentifierList() as $cx) {
            if ($cx instanceof CX) {
                if ($cx->getAssigningAuthority() == $authority) {
                    // If typecode is not null and it does not match, skip to next record
                    if (!is_null($typecode) && $cx->getIdentifierTypeCode() !== $typecode) {
                        continue;
                    }

                    return $cx;
                }
            }
        }
    }

    /**
     *
     * @return CX
     */
    public function getPatientId() {
        return new CX($this->get(2,0));
    }

    /**
     *
     * @return array [CX]
     */
    public function getPatientIdentifierList() {
        $result = array();
        foreach ($this->get(3) as $item) {
            $result[] = new CX($item);
        }

        return $result;
    }

    /**
     * Get patient identifier by providing an Identifier code
     *
     * Example codes are:
     * DN   Doctor number
     * EI   Employee Identifier
     * PI   Patient Internal identifier
     * SS   Social Security number
     *
     * @param string $identifier
     * @return CX|null
     */
    public function getPatientIdentifierByIdentifier($identifier)
    {
        foreach ($this->getPatientIdentifierList() as $cx) {
            if ($cx->getIdentifierTypeCode() == $identifier)
                return $cx;
        }

        return null;
    }

    /**
     *
     * @return array of XPN
     */
    public function getPatientNames()
    {
        return $this->_getXPN(5);
    }

    /**
     * Get a patient name for a specific type
     *
     * @param string $type 'L' for Legal, leave empty for first, which should be legal
     * @return XPN or null
     */
    public function getPatientXpnFor($type = null)
    {
        $xpns = $this->getPatientNames();

        if (! $type) {
            // Return first
            return reset($xpns);
        }

        foreach ($xpns as $xpn) {
            if ($xpn instanceof XPN) {
                if ($xpn->getNameTypeCode() == $type) {
                    return $xpn;
                }
            }
        }
    }

    /**
     *
     * @return XPN
     */
    public function getPatientAlias()
    {
        return $this->_getXPN(9);
    }

    /**
     * Return the first hit for the XTN record matching code / type
     *
     * Use null to ignore code and / or type, omitting both will return first record
     *
     * @param string|null $code Any \Gems\HL7\Type\XTN::getUseCode()
     * @param string|null $type Any \Gems\HL7\Type\XTN::getEquipmentType()
     * @return XTN|null
     */
    public function getPhoneBusiness($code = null, $type = null)
    {
        $items = $this->getPhoneBusinessList();

        return $this->_getXTNByType($items, $code, $type);
    }

    /**
     * Get a list of XTN objects for business phone
     *
     * @return XTN[]
     */
    public function getPhoneBusinessList($code = null, $type = null)
    {
        return $this->_getXTN(14);
    }

    /**
     * Return the first hit for the XTN record matching code / type
     *
     * Use null to ignore code and / or type, omitting both will return first record
     *
     * @param string|null $code Any \Gems\HL7\Type\XTN::getUseCode()
     * @param string|null $type Any \Gems\HL7\Type\XTN::getEquipmentType()
     * @return XTN|null
     */
    public function getPhoneHome($code = null, $type = null)
    {
        $items = $this->getPhoneHomeList();

        return $this->_getXTNByType($items, $code, $type);
    }

    /**
     * Get a list of XTN objects for home phone
     *
     * @return XTN[]
     */
    public function getPhonehomeList($code = null, $type = null)
    {
        return $this->_getXTN(13);
    }

    /**
     *
     * @return string
     */
    public function getPrimaryLanguage()
    {
        $pl = $this->get(15,0);
        return reset($pl);
    }

    public function getSex()
    {
        return (string) $this->get(8);
    }
}
