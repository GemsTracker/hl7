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
 * XPN: Extended Person Name
 *
 * See http://hl7-definition.caristix.com:9010
 *
 * SEQ	LENGTH	DT	OPT	TBL #	NAME
 * FN.1	0	ST	O		Display name
 * FN.2	0	ST	O               Own prefix
 * FN.3	0	ST	O		Own Family Name
 * FN.4	0	ST	O		Partner prefix 
 * FN.5	0	ST	O		Partner Family Name
 *
 * @package    Gems
 * @subpackage HL7\Type
 * @copyright  Copyright (c) 2016, Erasmus MC and MagnaFacta B.V.
 * @license    No free license, do not copy
 * @license    New BSD License
 */
class FN extends \Gems\HL7\Type
{
    /**
     *
     * @return mixed
     */
    public function getDisplayName()
    {
        return $this->_get(1);
    }

    public function getBirthPrefix()
    {
        return (string) $this->_get(2);
    }

    public function getBirthName()
    {
        return (string) $this->_get(3);
    }

    public function getPartnerPrefix()
    {
        return (string) $this->_get(4);
    }

    public function getPartnerName()
    {
        return (string) $this->_get(5);
    }

}