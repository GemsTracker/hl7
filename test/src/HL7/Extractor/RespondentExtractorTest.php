<?php

namespace Gems\HL7\Extractor;

use Gems\HL7\Test\RespondentExtractorTestAbstract;

/**
 * Generated by PHPUnit_SkeletonGenerator on 2016-02-09 at 13:22:05.
 */
class RespondentExtractorTest extends RespondentExtractorTestAbstract
{

    public function testRespondent()
    {
        $message = $this->_getMessageFromFile(TEST_DIR . '/resources/orm.txt');

        $expectedResult = array(
            'gr2o_patient_nr'      => '1001303',
            'gr2o_id_organization' => 'NES',
            'gr2o_email'           => 'email@example.net',
            'gr2o_reception_code'  => 'deceased',
            'grs_ssn'              => '134960713',
            'grs_iso_lang'         => 'DE',
            'grs_first_name'       => 'Klaas',
            'grs_surname_prefix'   => 'de',
            'grs_last_name'        => 'Peterse',
            'grs_gender'           => 'F',
            'grs_birthday'         => '1970-01-15',
            'grs_address_1'        => 'Woonstraße 2232 II',
            'grs_zipcode'          => '90210',
            'grs_city'             => 'Kukeleku',
            'grs_iso_country'      => 'DE',
            'grs_phone_1'          => '0106565656',
            'grs_phone_2'          => '0612345678',
        );

        $this->respondentExtractor->setSsnAutority('NLMINBIZA');
        $this->assertEquals(
                $expectedResult, $this->respondentExtractor->extractRow($message)
        );
    }

    public function testRespondent2()
    {
        $message = $this->_getMessageFromFile(TEST_DIR . '/resources/orm_1.txt');

        $expectedResult = array(
            'gr2o_patient_nr'      => '1001303',
            'gr2o_id_organization' => 'NES',
            'gr2o_email'           => 'email@example.net',
            'gr2o_reception_code'  => 'OK',
            'grs_ssn'              => '134960713',
            'grs_iso_lang'         => 'DE',
            'grs_first_name'       => 'Klaas',
            'grs_surname_prefix'   => 'de',
            'grs_last_name'        => 'Peterse',
            'grs_gender'           => 'F',
            'grs_birthday'         => '1970-01-15',
            'grs_address_1'        => 'Woonstraße 2232 II',
            'grs_zipcode'          => '90210',
            'grs_city'             => 'Kukeleku',
            'grs_iso_country'      => 'DE',
            'grs_phone_1'          => '0106565656',
            'grs_phone_2'          => '0612345678',
        );

        $this->respondentExtractor->setSsnAutority('NLMINBIZA');
        $this->assertEquals(
                $expectedResult, $this->respondentExtractor->extractRow($message)
        );
    }

    public function testRespondent3()
    {
        $message = $this->_getMessageFromFile(TEST_DIR . '/resources/orm_2.txt');

        $expectedResult = array(
            'gr2o_patient_nr'      => '1001303',
            'gr2o_id_organization' => 'NES',
            'gr2o_email'           => 'email@example.net',
            'gr2o_reception_code'  => 'OK',
            'grs_ssn'              => '134960713',
            'grs_iso_lang'         => 'DE',
            'grs_first_name'       => 'Klaas',
            'grs_surname_prefix'   => 'de',
            'grs_last_name'        => 'Vries-Peterse',
            'grs_gender'           => 'F',
            'grs_birthday'         => '1970-01-15',
            'grs_address_1'        => 'Woonstraße 2232 II',
            'grs_zipcode'          => '90210',
            'grs_city'             => 'Kukeleku',
            'grs_iso_country'      => 'DE',
            'grs_phone_1'          => '0106565656',
            'grs_phone_2'          => '0612345678',
        );

        $this->respondentExtractor->setSsnAutority('NLMINBIZA');
        $this->assertEquals(
                $expectedResult, $this->respondentExtractor->extractRow($message)
        );
    }

    public function testRespondentIdFailAuthority()
    {
        $message = $this->_getMessageFromFile(TEST_DIR . '/resources/orm.txt');

        // Non existing authority
        $this->assertEquals(null, $message->getPidSegment()->getPatientCxFor('XYZ'));
    }

}
