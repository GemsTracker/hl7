<?php

namespace Gems\HL7\Type;

use Gems\HL7\Type;

/**
 * CX: Extended Composite ID With Check Digit
 * 
 * This data type is used for specifying an identifier with its associated administrative detail.
 * 
 * SEQ	LENGTH	DT	OPT	TBL #	NAME
 * CX.1	0	ST	O               Id
 * CX.2	0	ST	O               Check Digit
 * CX.3	0	ID	O               Code Identifying The Check Digit Scheme Employed
 * CX.4	0	HD	O               Assigning Authority
 * CX.5	0	ID	O       0203	Identifier Type Code
 * CX.6	0	HD	O               Assigning Facility
 * CX.7	0	DT	O               Effective Date
 * CX.8	0	DT	O               Expiration Date
 *
 * @author Menno Dekker <menno.dekker@erasmusmc.nl>
 */
class CX extends Type {
    
    public function getId()
    {
        return (string) $this->_get(1);
    }
    
    public function getCheckDigit()
    {
        return (string) $this->_get(2);
    }
    
    public function getCheckDigitSchemeCode()
    {
        return (string) $this->_get(3);
    }
    
    public function getAssigningAuthority()
    {
        return $this->_get(4);
    }
    
    public function getIdentifierTypeCode()
    {
        return (string) $this->_get(5);
    }
    
    public function getAssingingFacility()
    {
        return $this->_get(6);
    }
    
    public function getEffectiveDate()
    {
        return $this->_get(7);
    }
    
    public function getExpirationDate()
    {
        return $this->_get(8);
    }
    
}