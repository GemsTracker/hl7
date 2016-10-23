<?php

/**
 *
 * @package    Gems
 * @subpackage HL7\Type
 * @author     Menno Dekker <menno.dekker@erasmusmc.nl>
 * @copyright  Copyright (c) 2016, Erasmus MC and MagnaFacta B.V.
 * @license    New BSD License
 */

namespace Gems\HL7\Type;

/**
 * XCN: Extended Composite ID Number And Name For Persons
 *
 * This data type is used extensively appearing in the PV1, ORC, RXO, RXE, OBR
 * and SCH segments , as well as others, where there is a need to specify the
 * ID number and name of a person.
 *
 * See http://hl7-definition.caristix.com:9010
 *
 * SEQ	LENGTH	DT	OPT	TBL #	NAME
 * XCN.1	0	ST	O		Id Number
 * XCN.2	0	FN	O		Family Name
 * XCN.3	0	ST	O	FirstName	Given Name
 * XCN.4	0	ST	O		Second And Further Given Names Or Initials Thereof
 * XCN.5	0	ST	O		Suffix
 * XCN.6	0	ST	O		Prefix
 * XCN.7	0	IS	O		Degree
 * XCN.8	0	IS	O		Source Table
 * XCN.9	0	HD	O		Assigning Authority
 * XCN.10	0	ID	O		Name Type Code
 * XCN.11	0	ST	O		Identifier Check Digit
 * XCN.12	0	ID	O		Code Identifying The Check Digit Scheme Employed
 * XCN.13	0	IS	O		Identifier Type Code
 * XCN.14	0	HD	O		Assigning Facility
 * XCN.15	0	ID	O		Name Representation Code
 * XCN.16	0	CE	O		Name Context
 * XCN.17	0	DR	O		Name Validity Range
 * XCN.18	0	ID	O		Name Assembly Order
 *
 * @package    Gems
 * @subpackage HL7\Type
 * @copyright  Copyright (c) 2016, Erasmus MC and MagnaFacta B.V.
 * @license    No free license, do not copy
 * @license    New BSD License
 */
class XCN extends \Gems\HL7\Type {

    public function __construct(\PharmaIntelligence\HL7\Node\Repetition $node) {
        parent::__construct($node);

        var_dump($node);
    }

}