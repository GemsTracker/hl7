<?php

/**
 *
 * @package    Gems
 * @subpackage HL7\Extractor
 * @author     Matijs de Jong <mjong@magnafacta.nl>
 * @copyright  Copyright (c) 2016 Erasmus MC
 * @license    New BSD License
 */

namespace Gems\HL7\Extractor;

use Gems\HL7\Node\Message;
use Gems\HL7\Segment\PIDSegment;
use Gems\HL7\Type\XAD;
use PharmaIntelligence\HL7\Node\Component;

/**
 *
 *
 * @package    Gems
 * @subpackage HL7\Extractor
 * @copyright  Copyright (c) 2016 Erasmus MC
 * @license    New BSD License
 * @since      Class available since version 1.7.2 Jul 28, 2016 7:09:45 PM
 */
class RespondentExtractor implements ExtractorInterface
{
    protected $_fieldList = [
        'gr2o_patient_nr'      => '_extractPatientId',
        'gr2o_id_organization' => '_extractOrganizationId',
        'gr2o_consent'         => '_extractConsent',
        'gr2o_reception_code'  => '_extractReceptionCode',
        'grs_ssn'              => '_extractSsn',
        'grs_iso_lang'         => '_extractLanguage',
        'grs_email'            => '_extractEmail',
        'grs_first_name'       => '_extractNameParts',
        'grs_surname_prefix'   => '_extractNameParts',
        'grs_last_name'        => '_extractNameParts',
        'grs_gender'           => '_extractGender',
        'grs_birthday'         => '_extractBirthDay',
        'grs_address_1'        => '_extractAddress1',
        'grs_zipcode'          => '_extractZipcode',
        'grs_city'             => '_extractCity',
        'grs_iso_country'      => '_extractCountry',
        'grs_phone_1'          => '_extractPhoneHome',
        'grs_phone_2'          => '_extractPhoneBusiness',
    ];

    /**
     *
     * @var string Default country and language code
     */
    public $defaultCountry = 'EN';

    /**
     *
     * @var \Gems\HL7\Type\XAD
     */
    protected $mailingAddres;

    /**
     *
     * @var \Gems\HL7\Node\Message;
     */
    protected $message;

    /**
     * The organization id for the patient (cannot be extracted from PIDSegment,
     * maybe from MSGSegment.
     *
     * @var int
     */
    protected $organizationId = false;

    /**
     * The authority id for the patient ID, usually LOCAL
     *
     * @var string
     */
    protected $patientIdAuthority = 'LOCAL';

    /**
     *
     * @var \Gems\HL7\Segment\PIDSegment;
     */
    protected $pid;

    /**
     *
     * @var string Reception code for deceased patients
     */
    protected $receptionCodeDeceased = 'deceased';

    /**
     *
     * @var string Reception code for OK
     */
    protected $receptionCodeOK = 'OK';

    /**
     * The authority id for the Social Security number, SSN is not returned when empty
     *
     * @var string
     */
    protected $ssnAuthority = 'NLMINBIZA';

    /**
     *
     * @return string Or false when should not be used
     */
    protected function _extractAddress1()
    {
        if (! $this->mailingAddres instanceof XAD) {
            return false;
        }

        return $this->mailingAddres->getStreet() ?: false;
    }

    /**
     *
     * @return string Or false when should not be used
     */
    protected function _extractBirthDay()
    {
        $birthday = $this->pid->getBirthDateTime();

        if (! $birthday->exists()) {
            return false;
        }

        return $birthday->getFormatted('Y-m-d');
    }

    /**
     *
     * @return string Or false when should not be used
     */
    protected function _extractCity()
    {
        if (! $this->mailingAddres instanceof XAD) {
            return false;
        }

        return $this->mailingAddres->getCity() ?: false;
    }

    /**
     *
     * @return string Or false when should not be used
     */
    protected function _extractConsent()
    {
        // No default mechanism exists
        return false;
    }

    /**
     *
     * @return string Or false when should not be used
     */
    protected function _extractCountry()
    {
        if (! $this->mailingAddres instanceof XAD) {
            return $this->defaultCountry;
        }

        return $this->mailingAddres->getCountryIso() ?: $this->defaultCountry;
    }

    /**
     *
     * @return string Or false when should not be used
     */
    protected function _extractEmail()
    {
        return $this->pid->getEmailAddress() ?: false;
    }

    /**
     *
     * @return string Or false when should not be used
     */
    protected function _extractGender()
    {
        $gender = (string) $this->pid->getSex();

        if (! $gender) {
            return false;
        }
        // Common failure
        if ('V' == $gender) {
            return 'F';
        }

        return $gender;
    }

    /**
     *
     * @return string Or false when should not be used
     */
    protected function _extractLanguage()
    {
        $prim = $this->pid->getPrimaryLanguage();
        if ($prim) {
            return $prim;
        }

        if (! $this->mailingAddres instanceof XAD) {
            return $this->defaultCountry;
        }

        return $this->mailingAddres->getCountryIso() ?: $this->defaultCountry;
    }

    /**
     * A function called three times: first first_name, then surname_prefix and then family_name
     *
     * @return string Or false when should not be used
     */
    protected function _extractNameParts()
    {
        static $lastName, $surnamePrefix;

        if (null !== $surnamePrefix) {
            $result = $surnamePrefix;
            $surnamePrefix = null;
            return $result;
        }

        if (null !== $lastName) {
            $result = $lastName;
            $lastName = null;
            return $result;
        }

        $name = $this->pid->getPatientXpnFor('L');
        if ($name) {
            $firstName = $name->getGivenName();

            $familyName = $name->getFamilyName();
            if ($familyName instanceof Component) {
                $surnamePrefix = (string) $familyName[1];
                $lastName = (string)  $familyName[2];
            } else {
                $surnamePrefix = false;
                $lastName = (string) $familyName;
            }
        }

        return $firstName;
    }

    /**
     *
     * @return string Or false when should not be used
     */
    protected function _extractOrganizationId()
    {
        // TODO: something with the header
        return $this->getOrganizationId();
    }

    /**
     *
     * @return string Or false when should not be used
     */
    protected function _extractPatientId()
    {
        $cxPid = $this->pid->getPatientCxFor($this->getPatientIdAutority());
        if ($cxPid) {
            return $cxPid->getId();
        }
        return false;
    }

    /**
     *
     * @return string Or false when should not be used
     */
    protected function _extractPhoneBusiness()
    {
        return $this->pid->getPhoneBusiness()->getPhoneNumber() ?: false;
    }

    /**
     *
     * @return string Or false when should not be used
     */
    protected function _extractPhoneHome()
    {
        return $this->pid->getPhoneHome()->getPhoneNumber() ?: false;
    }

    /**
     *
     * @return string Or false when should not be used
     */
    protected function _extractReceptionCode()
    {
        if (('Y' === substr($this->pid->getDeathIndicator(), 0, 1)) || $this->pid->getDeathDateTime()->exists()) {
            return $this->receptionCodeDeceased;
        }
        return $this->receptionCodeOK;
    }

    /**
     *
     * @return string Or false when should not be used
     */
    protected function _extractSsn()
    {
        $auth = $this->getSsnAutority();
        if ($auth) {
            $cxBsn = $this->pid->getPatientCxFor($this->getSsnAutority());
            if ($cxBsn) {
                return $cxBsn->getId();
            }
        }
        return false;
    }

    /**
     *
     * @return string Or false when should not be used
     */
    protected function _extractZipcode()
    {
        if (! $this->mailingAddres instanceof XAD) {
            return false;
        }

        return $this->mailingAddres->getZipcode() ?: false;
    }

    /**
     *
     * @param PIDSegment $pid
     * @return array Or false when not valid
     */
    public function extractPatientRow(Message $message)
    {
        $this->message = $message;
        $this->pid     = $message->getPidSegment();
        $this->mailingAddres = $this->pid->getMailingAddress();

        $output = [];
        foreach ($this->_fieldList as $field => $functionName) {
            $value = call_user_func([$this, $functionName]);
            if (false !== $value) {
                $output[$field] = $value;
            }
        }
        if (! isset($output['gr2o_patient_nr'])) {
            return false;
        }

        return $output;
    }

    /**
     * Get the organization id for the patient (cannot be extracted from PIDSegment,
     * maybe from MSGSegment.
     *
     * @return $orgId
     */
    public function getOrganizationId()
    {
        return $this->organizationId;
    }

    /**
     * Get the authority id for the patient ID, usually LOCAL
     *
     * @return string
     */
    public function getPatientIdAutority()
    {
        return $this->patientIdAuthority;
    }

    /**
     * Set the authority id for the Social Security number, SSN is not returned when empty
     *
     * @return string
     */
    public function getSsnAutority()
    {
        return $this->ssnAuthority;
    }

    /**
     * Set the organization id for the patient (cannot be extracted from PIDSegment,
     * maybe from MSGSegment.
     *
     * @param int $orgId
     * @return \Gems\HL7\Extractor\RespondentExtractor
     */
    public function setOrganizationId($orgId)
    {
        $this->organizationId = $orgId;

        return $this;
    }

    /**
     * Set the authority id for the patient ID, usually LOCAL
     *
     * @param string $authority
     * @return \Gems\HL7\Extractor\RespondentExtractor
     */
    public function setPatientIdAutority($authority)
    {
        $this->patientIdAuthority = $authority;

        return $this;
    }

    /**
     * Set the authority id for the Social Security number, SSN is not returned when empty
     *
     * @param string $authority     E.g. 'NLMINBIZA' for BSN
     * @return \Gems\HL7\Extractor\RespondentExtractor
     */
    public function setSsnAutority($authority)
    {
        $this->ssnAuthority = $authority;

        return $this;
    }
}
