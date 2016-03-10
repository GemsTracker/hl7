<?php

namespace Gems\HL7\Type;

use Gems\HL7\Type;

/**
 * PL: Person Location
 * 
 * This data type is used to specify a patient location within a healthcare institution. Which components are valued depends on the needs of the site. For example for a patient treated at home, only the person location type is valued. It is most commonly used for specifying patient locations, but may refer to other types of persons within a healthcare setting.
 * 
 * SEQ	LENGTH	DT	OPT	TBL #	NAME
 * PL.1	0	IS	O           Point Of Care
 * PL.2	0	IS	O           Room
 * PL.3	0	IS	O           Bed
 * PL.4	0	HD	O           Facility
 * PL.5	0	IS	O           Location Status
 * PL.6	0	IS	O           Person Location Type
 * PL.7	0	IS	O           Building
 * PL.8	0	IS	O           Floor
 * PL.9	0	ST	O           Location Description
 *
 * @author Menno Dekker <menno.dekker@erasmusmc.nl>
 */
class PL extends Type {
    
    public function getPointOfCare()
    {
        return (string) $this->_get(1);
    }
    
    public function getRoom()
    {
        return (string) $this->_get(2);
    }
    
    public function getBed()
    {
        return (string) $this->_get(3);
    }
    
    public function getFacility()
    {
        return $this->_get(4);
    }
    
    public function getLocationStatus()
    {
        return (string) $this->_get(5);
    }
    
    public function getPersonLocationType()
    {
        return (string) $this->_get(6);
    }
    
    public function getBuilding()
    {
        return (string) $this->_get(7);
    }
    
    public function getFloor()
    {
        return (string) $this->_get(8);
    }
    
    public function getLocationDescription()
    {
        return (string) $this->_get(9);
    }
    
}