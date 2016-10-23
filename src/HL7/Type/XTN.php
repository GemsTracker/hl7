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
 * XTN: Extended Telecommunication Number
 *
 * See http://hl7-definition.caristix.com:9010
 *
 * SEQ	LENGTH	DT	OPT	TBL #	NAME
 * XTN.1	0	TN	O           Telephone Number
 * XTN.2	0	ID	O           Telecommunication Use Code
 * XTN.3	0	ID	O           Telecommunication Equipment Type
 * XTN.4	0	ST	O           Email Address
 * XTN.5	0	NM	O           Country Code
 * XTN.6	0	NM	O           Area/City Code
 * XTN.7	0	NM	O           Phone Number
 * XTN.8	0	NM	O           Extension
 * XTN.9	0	ST	O           Any Text
 *
 * @package    Gems
 * @subpackage HL7\Type
 * @copyright  Copyright (c) 2016, Erasmus MC and MagnaFacta B.V.
 * @license    No free license, do not copy
 * @license    New BSD License
 */
class XTN extends \Gems\HL7\Type
{
    public function getPhoneNumber()
    {
        return (string) $this->_get(1);
    }

    public function getUseCode()
    {
        return (string) $this->_get(2);
    }

    public function getEquipmentType()
    {
        return (string) $this->_get(3);
    }

    public function getEmailAddress()
    {
        return (string) $this->_get(4);
    }

    public function getCountryCode()
    {
        return (string) $this->_get(5);
    }

    public function getAreaCode()
    {
        return (string) $this->_get(6);
    }

    public function getNumber()
    {
        return (string) $this->_get(7);
    }

    public function getExtension()
    {
        return (string) $this->_get(8);
    }

    public function getText()
    {
        return (string) $this->_get(9);
    }

}