<?php

/**
 *
 * @package    Gems
 * @subpackage HL7\Type
 * @author     Menno Dekker <menno.dekker@erasmusmc.nl>
 * @copyright  Copyright (c) 2017, Erasmus MC
 * @license    New BSD License
 */

namespace Gems\HL7\Type;

use DateTime;
use Gems\HL7\Type;

/**
 * MSG: Message Type
 * 
 * See http://hl7-definition.caristix.com:9010
 *
 * SEQ	LENGTH	DT	OPT	TBL #	NAME
 * MSG.1	0	ID	O		Message Type
 * MSG.2	0	ID	O		Trigger Event
 * MSG.3	0	ID	O		Message Structure
 *
 * @package    Gems
 * @subpackage HL7\Type
 * @copyright  Copyright (c) 2017, Erasmus MC
 * @license    New BSD License
 */
class MSG extends Type {
    
    /**
     *
     * @return String
     */
    public function getMessageStructure()
    {
        return (string) $this->_get(2);
    }

    /**
     *
     * @return String
     */
    public function getMessageType()
    {
        return (string) $this->_get(1);
    }
    
    /**
     *
     * @return String
     */
    public function getTriggerEvent()
    {
        return (string) $this->_get(2);
    }
}
