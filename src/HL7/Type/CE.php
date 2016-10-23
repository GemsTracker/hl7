<?php

/**
 *
 * @package    Gems
 * @subpackage HL7\Type
 * @author     Matijs de Jong <mjong@magnafacta.nl>
 * @copyright  Copyright (c) 2016, Erasmus MC and MagnaFacta B.V.
 * @license    New BSD License
 */

namespace Gems\HL7\Type;

use Gems\HL7\Type;

/**
 * CE: Coded Element
 *
 * This data type transmits codes and the text associated with the code.
 *
 * SEQ	LENGTH	DT	OPT	TBL #	NAME
 * CE.1	20	ST	O		Identifier
 * CE.2	199	ST	O		Text
 * CE.3	20	ID	O	0396	Name Of Coding System
 * CE.4	20	ST	O		Alternate Identifier
 * CE.5	199	ST	O		Alternate Text
 * CE.6	20	ID	O	0396	Name Of Alternate Coding System
 *
 * See http://hl7-definition.caristix.com:9010
 *
 * @package    Gems
 * @subpackage HL7\Type
 * @copyright  Copyright (c) 2016, Erasmus MC and MagnaFacta B.V.
 * @license    New BSD License
 * @since      Class available since version 1.8.1 Oct 23, 2016 1:56:08 PM
 */
class CE extends Type
{
    public function getId()
    {
        return (string) $this->_get(1);
    }

    public function getText()
    {
        return (string) $this->_get(2);
    }
}
