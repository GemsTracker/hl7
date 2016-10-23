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

/**
 * XCN: Extended Address
 *
 * This data type specifies the address of a person, place or organization plus associated information.
 *
 *
 * See http://hl7-definition.caristix.com:9010
 *
 * SEQ	LENGTH	DT	OPT	TBL #	NAME
 * XAD.1	0	SAD	O	Street	Street Address
 * XAD.2	0	ST	O		Other Designation -- huisnummer toevoeging in nederland
 * XAD.3	0	ST	O	City	City
 * XAD.4	0	ST	O	State	State Or Province
 * XAD.5	0	ST	O	ZipCode	Zip Or Postal Code
 * XAD.6	0	ID	O		Country
 * XAD.7	0	ID	O		Address Type
 * XAD.8	0	ST	O		Other Geographic Designation
 * XAD.9	0	IS	O		County/Parish Code
 * XAD.10	0	IS	O		Census Tract
 * XAD.11	0	ID	O		Address Representation Code
 * XAD.12	0	DR	O		Address Validity Range
 *
 * @package    Gems
 * @subpackage HL7\Type
 * @copyright  Copyright (c) 2016, Erasmus MC and MagnaFacta B.V.
 * @license    No free license, do not copy
 * @license    New BSD License
 */
class XAD extends \Gems\HL7\Type
{
    /**
     *
     * @return string
     */
    public function getStreet()
    {
        $streetPart = $this->_get(1);

        if (isset($streetPart[1])) {
            $street = $streetPart[1] . ' ' . $streetPart[2];
        } else {
            $street = $streetPart[0];
        }
        $letter = (string) $this->_get(2);
        if ($letter) {
            return $street . ' ' . $letter;
        }
        return $street;
    }

    /**
     *
     * @return string
     */
    public function getCity()
    {
        return (string) $this->_get(3);
    }

    /**
     *
     * @return string
     */
    public function getZipcode()
    {
        return (string) $this->_get(5);
    }

    /**
     *
     * @return string 3 letter country code
     */
    public function getCountry()
    {
        return trim((string) $this->_get(6), '"');
    }

    /**
     *
     * @return string 2 letter country code
     */
    public function getCountryIso()
    {
        $country = strtoupper($this->getCountry());

        if (3 !== strlen($country)) {
            return $country;
        }

        $isoList = json_decode(file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . 'country.io_iso3.json'), true);

        return array_search($country, $isoList);
    }
}
