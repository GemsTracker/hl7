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

use Gems\HL7\Segment\PIDSegment;
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
    /**
     * The organization id for the patient (cannot be extracted from PIDSegment,
     * maybe from MSGSegment.
     *
     * @var int
     */
    protected $organizationId;

    /**
     * The authority id for the patient ID, usually LOCAL
     *
     * @var string
     */
    protected $patientIdAuthority = 'LOCAL';

    /**
     * The authority id for the Social Security number, SSN is not returned when empty
     *
     * @var string
     */
    protected $ssnAuthority;

    /**
     *
     * @param PIDSegment $pid
     * @return array
     */
    public function extractPatientRow(PIDSegment $pid)
    {
        $output = array();

        $cxPid = $pid->getPatientCxFor($this->getPatientIdAutority());
        if (! $cxPid) {
            return null;
        }
        $output['gr2o_patient_nr'] = $cxPid->getId();

        $orgId = $this->getOrganizationId();
        if ($orgId) {
            $output['gr2o_id_organization'] = $orgId;
        }

        $cxBsn = $pid->getPatientCxFor($this->getSsnAutority());
        if ($cxBsn) {
            $output['grs_ssn'] = $cxBsn->getId();
        }

        $name = $pid->getPatientXpnFor('L');
        if ($name) {
            $output['grs_first_name'] = $name->getGivenName();

            $familyName = $name->getFamilyName();
            if ($familyName instanceof Component) {
                $output['grs_surname_prefix'] = (string) $familyName[1];
                $output['grs_last_name'] = (string) $familyName[2];
            } else {
                $output['grs_last_name'] = (string) $familyName;
            }
        }

        $gender = $pid->getSex();
        if ($gender) {
            $output['grs_gender'] = (string) $gender;

            // Common failure
            if ('V' == $output['grs_gender']) {
                $output['grs_gender'] = 'F';
            }
        }

        // print_r($output);

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
