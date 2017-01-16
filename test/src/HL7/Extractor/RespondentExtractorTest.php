<?php

namespace Gems\HL7\Extractor;

use Gems\HL7\Unserializer;
use Zalt\Loader\ProjectOverloader;
use PHPUnit_Framework_TestCase;

/**
 * Generated by PHPUnit_SkeletonGenerator on 2016-02-09 at 13:22:05.
 */
class RespondentExtractorTest extends RespondentExtractorTestAbstract
{
    public function testRespondent()
    {
        $message = $this->_getMessageFromFile('orm.txt');

        $expectedResult = array(
            'gr2o_patient_nr'      => '8101892',
            'gr2o_id_organization' => 'HISCOM',
            'gr2o_reception_code'  => 'OK',
            'grs_ssn'              => '134960713',
            'grs_iso_lang'         => 'EN',
            'grs_first_name'       => 'Wendy',
            'grs_surname_prefix'   => 'van',
            'grs_last_name'        => 'Liempd',
            'grs_gender'           => 'F',
            'grs_birthday'         => '1982-07-03',
            'grs_address_1'        => 'Schipholstraat 20',
            'grs_zipcode'          => '3045XC',
            'grs_city'             => 'Rotterdam',
            'grs_iso_country'      => 'EN',
            'grs_phone_1'          => '06-43064759',
        );

        $this->respondentExtractor->setSsnAutority('NLMINBIZA');
        $this->assertEquals(
                $expectedResult, $this->respondentExtractor->extractRow($message)
        );
    }

    public function testRespondent2()
    {
        $message = $this->_getMessageFromFile('orm_1.txt');

        $expectedResult = array(
            'gr2o_patient_nr'      => '8101892',
            'gr2o_id_organization' => 'HISCOM',
            'gr2o_reception_code'  => 'OK',
            'grs_ssn'              => '134960713',
            'grs_iso_lang'         => 'EN',
            'grs_first_name'       => 'Wendy',
            'grs_surname_prefix'   => 'van',
            'grs_last_name'        => 'Liempd',
            'grs_gender'           => 'F',
            'grs_birthday'         => '1982-07-03',
            'grs_address_1'        => 'Schipholstraat 20',
            'grs_zipcode'          => '3045XC',
            'grs_city'             => 'Rotterdam',
            'grs_iso_country'      => 'EN',
            'grs_phone_1'          => '06-43064759',
        );

        $this->respondentExtractor->setSsnAutority('NLMINBIZA');
        $this->assertEquals(
                $expectedResult, $this->respondentExtractor->extractRow($message)
        );
    }

    public function testRespondentIdFailAuthority()
    {
        $message = $this->_getMessageFromFile('orm.txt');

        // Non existing authority
        $this->assertEquals(null, $message->getPidSegment()->getPatientCxFor('XYZ'));
    }

}
