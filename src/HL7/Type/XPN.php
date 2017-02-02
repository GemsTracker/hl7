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

use Gems\HL7\Type;

/**
 * XPN: Extended Person Name
 *
 * See http://hl7-definition.caristix.com:9010
 *
 * SEQ	LENGTH	DT	OPT	TBL #	NAME
 * XPN.1	0	FN	O		Family Name
 * XPN.2	0	ST	O       FirstName Given Name
 * XPN.3	0	ST	O		Second And Further Given Names Or Initials Thereof
 * XPN.4	0	ST	O		Suffix
 * XPN.5	0	ST	O		Prefix
 * XPN.6	0	IS	O		Degree
 * XPN.7	0	ID	O		Name Type Code
 * XPN.8	0	ID	O		Name Representation Code
 * XPN.9	0	CE	O		Name Context
 * XPN.10	0	DR	O		Name Validity Range
 * XPN.11	0	ID	O		Name Assembly Order
 *
 * @package    Gems
 * @subpackage HL7\Type
 * @copyright  Copyright (c) 2016, Erasmus MC and MagnaFacta B.V.
 * @license    No free license, do not copy
 * @license    New BSD License
 */
class XPN extends Type
{
    /**
     *
     * @return FN
     */
    public function getFamilyName()
    {
        return new FN($this->_get(1));
    }

    public function getGivenName()
    {
        return (string) $this->_get(2);
    }

    public function getOtherGivenName()
    {
        return (string) $this->_get(3);
    }

    public function getSuffix()
    {
        return (string) $this->_get(4);
    }

    public function getPrefix()
    {
        return (string) $this->_get(5);
    }

    public function getDegree()
    {
        return (string) $this->_get(6);
    }

    public function getNameTypeCode()
    {
        return (string) $this->_get(7);
    }

    public function getNameRepresentationCode()
    {
        return (string) $this->_get(8);
    }

    public function getNameContext()
    {
        return $this->_get(9);
    }

    public function getNameValidityRange()
    {
        return $this->_get(10);
    }

    public function getNameAssemblyOrder()
    {
        return (string) $this->_get(11);
    }

}