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
use Gems\HL7\Type\XAD;
use Gems\HL7\Type\XTN;
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
        'grs_first_name'       => '_extractFirstName',
        'grs_surname_prefix'   => '_extractSurnamePrefix',
        'grs_last_name'        => '_extractLastName',
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
     * @var XAD
     */
    protected $mailingAddres;

    /**
     *
     * @var \Gems\HL7\Node\Message;
     */
    protected $message;

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
     * The authority id for the Social Security number, SSN is not returned when empty
     *
     * @var string
     */
    protected $ssnTypeCode = 'NNNLD';

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
     * Extract the firstname, overrule if you prefer initials
     * 
     * @return type
     */
    protected function _extractFirstName()
    {
        // bij L record alleen initialen, bij B record alle voornamen, bij N record alleen de roepnaam
        $names = [
            $this->pid->getPatientXpnFor('N'), // Try nickname first
            $this->pid->getPatientXpnFor('B'), // Try first (and other) firstnames
            $this->pid->getPatientXpnFor('L'), // Only initials normally
            $this->pid->getPatientXpnFor()      // Anything will do
        ];
        
        return $this->getFirstName($names);       
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
    
    protected function _extractLastName()
    {
        // bij L(egal) record alleen initialen, bij B(irth) record alle voornamen, bij N(ick) record alleen de roepnaam
        $name = $this->pid->getPatientXpnFor('L');
        if (is_null($name)) {
            $name = $this->pid->getPatientXpnFor();
        }

        $nameTypeCode = $name->getNameTypeCode();
        switch ($nameTypeCode) {
            case 'B':
                $nameParts = $name->getFamilyName();
                $lastName  = $nameParts->getBirthName();
                break;

            case 'L':
                $nameParts     = $name->getFamilyName();

                if ($order = $name->getNameAssemblyOrder()) {
                    /**
                     * NL0	onbekend
                     * NL1	eigennaam
                     * NL2	naampartner
                     * NL3	naampartner gevolgd door eigennaam
                     * NL4	eigennaam gevolgd door naam partner
                     */
                    switch ($order) {
                        case 'NL2':
                            $lastName = $nameParts->getPartnerName();
                            break;

                        case 'NL3':
                            $lastName = $nameParts->getPartnerName() . '-' . join(' ', $nameParts->getBirthPrefix(), $nameParts->getBirthName());
                            break;

                        case 'NL4':
                            $lastName = $nameParts->getBirthName() . '-' . join(' ', $nameParts->getPartnerPrefix(), $nameParts->getPartnerName());
                            break;

                        case 'NL1':
                        default:
                            $lastName = $nameParts->getBirthName();
                            break;
                    }
                } else {
                    $lastName = $nameParts->getBirthName();
                }
                break;

            default:
                $lastName = '';
                break;
        }

        return (string) $lastName;
    }

    /**
     *
     * @return string Or false when should not be used
     */
    protected function _extractOrganizationId()
    {
        return $this->message->getMshSegment()->getSendingOrganizationId();
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
        // Add phone numbers in preferred order
        $xtns[] = $this->pid->getPhoneBusiness('WPN', 'PH');
        $xtns[] = $this->pid->getPhoneBusiness('WPN', 'CP');

        /** @var $xtns XTN[] */
        // Get the phone numbers if possible
        $phones = [];
        foreach($xtns as $xtn) {
            if (!is_null($xtn)) {
                $phones[] = $xtn->getPhoneNumber();                
            }                    
        }
        
        $phones = array_filter($phones);
        $phone  = reset($phones);
        
        return $phone;
    }

    /**
     *
     * @return string Or false when should not be used
     */
    protected function _extractPhoneHome()
    {
        // Add phone numbers in preferred order
        $xtns[] = $this->pid->getPhoneHome('PRN', 'PH');
        $xtns[] = $this->pid->getPhoneHome('PRN', 'CP');
        $xtns[] = $this->pid->getPhoneHome('ORN', 'PH');
        $xtns[] = $this->pid->getPhoneHome('ORN', 'CP');

        /** @var $xtns XTN[] */
        // Get the phone numbers if possible
        $phones = [];
        foreach($xtns as $xtn) {
            if (!is_null($xtn)) {
                $phones[] = $xtn->getPhoneNumber();                
            }                    
        }
        
        $phones = array_filter($phones);
        $phone  = reset($phones);
        
        return $phone;
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
    
    protected function _extractSurnamePrefix()
    {
        // bij L(egal) record alleen initialen, bij B(irth) record alle voornamen, bij N(ick) record alleen de roepnaam
        $name = $this->pid->getPatientXpnFor('L');
        if (is_null($name)) {
            $name = $this->pid->getPatientXpnFor();
        }

        $nameTypeCode = $name->getNameTypeCode();
        switch ($nameTypeCode) {
            case 'B':
                $nameParts = $name->getFamilyName();
                $prefix    = $nameParts->getBirthPrefix();
                break;

            case 'L':
                $nameParts     = $name->getFamilyName();
                $eigenPrefix   = $nameParts->getBirthPrefix();
                $partnerPrefix = $nameParts->getPartnerPrefix();

                if ($order = $name->getNameAssemblyOrder()) {
                    /**
                     * NL0	onbekend
                     * NL1	eigennaam
                     * NL2	naampartner
                     * NL3	naampartner gevolgd door eigennaam
                     * NL4	eigennaam gevolgd door naam partner
                     */
                    switch ($order) {
                        case 'NL2':
                        case 'NL3':
                            $prefix = $partnerPrefix;
                            break;

                        case 'NL4':
                        case 'NL1':
                        default:
                            $prefix = $eigenPrefix;
                            break;
                    }
                } else {
                    $prefix = $nameParts->getBirthPrefix();
                }
                break;

            default:
                $prefix = '';
                break;
        }

        return (string) $prefix;
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
     * @param Message $message
     * @return array Or false when not valid
     */
    public function extractRow(Message $message)
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
     * Internal helper function
     * 
     * @see self::_extractFirstName
     * 
     * @param XPN[] $names
     * @return type
     */
    protected function getFirstName($names)
    {
        /** @var $names XPN[] */
        $names = array_filter($names);  // Strip emtpy elements

        $firstNames = [];        
        foreach ($names as $name) {
            $nameTypeCode = $name->getNameTypeCode();
            switch ($nameTypeCode) {
                case 'B':
                    $firstName  = $name->getGivenName();
                    $otherNames = $name->getOtherGivenName();
                    if (!empty($otherNames)) {
                        $firstName .= ' ' . $otherNames;
                    }
                    break;

                case 'N':
                    $firstName = $name->getGivenName();
                    break;

                case 'L':
                    $firstName  = $name->getGivenName();
                    $otherNames = $name->getOtherGivenName();
                    if (!empty($otherNames)) {
                        $firstName .= ' ' . $otherNames;
                    }

                    if (!empty($firstName)) {
                        $firstName = $firstName . ' ';
                    }

                    $firstName = str_replace(' ', '.', $firstName);
                    break;

                default:
                    // Should not be here... could be P for partner, then don't use
                    $firstName = '';
                    break;
            }
            $firstNames[] = $firstName;
        }
        
        $firstNames = array_filter($firstNames);

        if (false === $firstNames) return null;

        return reset($firstNames);
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
     * Get the authority id for the Social Security number, SSN is not returned when empty
     *
     * @return string
     */
    public function getSsnAutority()
    {
        return $this->ssnAuthority;
    }
    
    /**
     * Get the typecode for the Social Security number
     *
     * @return string
     */
    public function getSsnTypeCode()
    {
        return $this->ssnTypeCode;
    }

    /**
     * Set the authority id for the patient ID, usually LOCAL
     *
     * @param string $authority
     * @return RespondentExtractor
     */
    public function setPatientIdAutority($authority)
    {
        $this->patientIdAuthority = $authority;

        return $this;
    }

    /**
     * Set the authority id for the Social Security number, SSN is not returned when empty
     * 
     * Use typecode when authority provides more than one number
     *
     * @param string $authority     E.g. 'NLMINBIZA' for BSN
     * @param string $typecode      E.g. 'NNNLD' for BSN, 'PPN' for passport/identity card
     * @return RespondentExtractor
     */
    public function setSsnAutority($authority, $typeCode = null)
    {
        $this->ssnAuthority = $authority;
        $this->ssnTypecode  = $typeCode;

        return $this;
    }
}
