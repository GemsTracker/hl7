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
use Gems\HL7\Type\PL;
use Gems\HL7\Type\TS;
use Gems\HL7\Type\XCN;

/**
 * PV1: Patient visit
 *
 * See http://hl7-definition.caristix.com:9010
 *
 * SEQ	LENGTH	DT	OPT	RPT/#	TBL #	NAME
 * PV1.1	4	SI	O	1           	Set ID - PV1
 * PV1.2	1	IS	R	1       0004	Patient Class
 * PV1.3	80	PL	O	1               Assigned Patient Location
 * PV1.4	2	IS	O	1       0007	Admission Type
 * PV1.5	250	CX	O	1           	Preadmit Number
 * PV1.6	80	PL	O	1         		Prior Patient Location
 * PV1.7	250	XCN	O	*   	0010	Attending Doctor
 * PV1.8	250	XCN	O	*   	0010	Referring Doctor
 * PV1.9	250	XCN	O	*       0010	Consulting Doctor
 * PV1.10	3	IS	O	1       0069	Hospital Service
 * PV1.11	80	PL	O	1               Temporary Location
 * PV1.12	2	IS	O	1       0087	Preadmit Test Indicator
 * PV1.13	2	IS	O	1       0092	Re-admission Indicator
 * PV1.14	6	IS	O	1   	0023	Admit Source
 * PV1.15	2	IS	O	*   	0009	Ambulatory Status
 * PV1.16	2	IS	O	1       0099	VIP Indicator
 * PV1.17	250	XCN	O	*       0010	Admitting Doctor
 * PV1.18	2	IS	O	1       0018	Patient Type
 * PV1.19	250	CX	O	1           	Visit Number
 * PV1.20	50	FC	O	*       0064	Financial Class
 * PV1.21	2	IS	O	1       0032	Charge Price Indicator
 * PV1.22	2	IS	O	1       0045	Courtesy Code
 * PV1.23	2	IS	O	1       0046	Credit Rating
 * PV1.24	2	IS	O	*       0044	Contract Code
 * PV1.25	8	DT	O	*           	Contract Effective Date
 * PV1.26	12	NM	O	*       		Contract Amount
 * PV1.27	3	NM	O	*       		Contract Period
 * PV1.28	2	IS	O	1   	0073	Interest Code
 * PV1.29	1	IS	O	1   	0110	Transfer to Bad Debt Code
 * PV1.30	8	DT	O	1       		Transfer to Bad Debt Date
 * PV1.31	10	IS	O	1       0021	Bad Debt Agency Code
 * PV1.32	12	NM	O	1       		Bad Debt Transfer Amount
 * PV1.33	12	NM	O	1       		Bad Debt Recovery Amount
 * PV1.34	1	IS	O	1   	0111	Delete Account Indicator
 * PV1.35	8	DT	O	1       		Delete Account Date
 * PV1.36	3	IS	O	1   	0112	Discharge Disposition
 * PV1.37	25	DLD	O	1       0113	Discharged to Location
 * PV1.38	250	CE	O	1       0114	Diet Type
 * PV1.39	2	IS	O	1       0115	Servicing Facility
 * PV1.40	1	IS	O	1       0116	Bed Status
 * PV1.41	2	IS	O	1       0117	Account Status
 * PV1.42	80	PL	O	1           	Pending Location
 * PV1.43	80	PL	O	1            	Prior Temporary Location
 * PV1.44	26	TS	O	1       		Admit Date/Time
 * PV1.45	26	TS	O	*           	Discharge Date/Time
 * PV1.46	12	NM	O	1               Current Patient Balance
 * PV1.47	12	NM	O	1           	Total Charges
 * PV1.48	12	NM	O	1           	Total Adjustments
 * PV1.49	12	NM	O	1           	Total Payments
 * PV1.50	250	CX	O	1       0203	Alternate Visit ID
 * PV1.51	1	IS	O	1       0326	Visit Indicator
 * PV1.52	250	XCN	O	*       0010	Other Healthcare Provider
 *
 * @package    Gems
 * @subpackage HL7\Segment
 * @copyright  Copyright (c) 2016 Erasmus MC and MagnaFacta BV
 * @license    New BSD License
 * @since      Class available since version 1.8.1 Oct 20, 2016 404661
 */
class PV1Segment extends Segment {

    const IDENTIFIER = 'PV1';

    public function __construct($segmentName = self::IDENTIFIER) {
        parent::__construct($segmentName);
    }

    /**
     *
     * @param type $idx
     * @return XCN
     */
    protected function _getXCN($idx)
    {
        $result = array();
        if($items = $this->get($idx)) {
            foreach ($items as $item) {
                $result[] = new XCN($item);
            }
        }

        return $result;
    }

    public function getSetId() {
        return (string) $this->get(1,0);
    }

    public function getPatientClass() {
        return (string) $this->get(2,0);
    }

    /**
     *
     * @return PL
     */
    public function getPatientLocation() {
        return new PL($this->get(3,0));
    }

    public function getAdmissionType() {
        return (string) $this->get(4,0);
    }

    /**
     *
     * @return CX
     */
    public function getPreadmitNumber() {
        return new CX($this->get(5,0));
    }

    /**
     *
     * @return PL
     */
    public function getPriorPatientLocation() {
        return new PL($this->get(6,0));
    }

    /**
     * @return XCN
     */
    public function getAttendingDoctor() {
        return $this->_getXCN(7);
    }

    /**
     * @return XCN
     */
    public function getReferringDoctor() {
        return $this->_getXCN(8);
    }

    /**
     * @return XCN
     */
    public function getConsultingDoctor() {
        return $this->_getXCN(9);
    }

    public function getHospitalService() {
        return (string) $this->get(10,0);
    }

    /**
     * @return PL
     */
    public function getTemporaryLocation() {
        return new PL($this->get(11,0));
    }

    /**
     * @return TS
     */
    public function getAdmitDateTime() {
        return new TS($this->get(44,0));
    }

    /**
     * @return TS
     */
    public function getDischargeDateTime() {
        $result = array();
        if($items = $this->get(45)) {
            foreach ($items as $item) {
                $result[] = new TS($item);
            }
        }

        return $result;
    }


}
