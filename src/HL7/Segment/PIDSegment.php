<?php

namespace Gems\HL7\Segment;

use Gems\HL7\Segment;
use Gems\HL7\Type\CX;
use Gems\HL7\Type\TS;
use Gems\HL7\Type\XPN;
use Gems\HL7\Type\XTN;

/**
 * PID segment
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
 * @author Menno Dekker <menno.dekker@erasmusmc.nl>
 */
class PIDSegment extends Segment {

    const IDENTIFIER = 'PID';

    public function __construct($segmentName = self::IDENTIFIER) {
        parent::__construct($segmentName);
    }

    /**
     *
     * @param type $idx
     * @return XPN
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
     * @return XTN
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
     *
     * @return CX
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
    public function getDeathDateTime()
    {
        return new TS($this->get(29,0));
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
    public function getLastUpdateDateTime()
    {
        return new TS($this->get(33,0));
    }

    /**
     * Get the patient id for a specific authority
     *
     * @param string $authority Authority used for patient id
     * @return CX or null
     */
    public function getPatientCxFor($authority)
    {
        if (! $authority) {
            return null;
        }
        foreach ($this->getPatientIdentifierList() as $cx) {
            if ($cx instanceof CX) {
                if ($cx->getAssigningAuthority() == $authority) {
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
     * @return array of XPN
     */
    public function getPatientNames()
    {
        return $this->_getXPN(5);
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
     *
     * @return TS
     */
    public function getBirthDateTime()
    {
        return new TS($this->get(7,0));
    }

    public function getSex()
    {
        return (string) $this->get(8);
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
     *
     * @return XTN
     */
    public function getPhoneBusiness()
    {
        return $this->_getXTN(14);
    }

    /**
     *
     * @return XTN
     */
    public function getPhoneHome()
    {
        return $this->_getXTN(13);
    }

    public function getSetId() {
        return $this->get(1,0);
    }
}
