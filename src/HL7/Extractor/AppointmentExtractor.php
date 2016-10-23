<?php

/**
 *
 * @package    Gems
 * @subpackage HL7\Extractor
 * @author     Matijs de Jong <mjong@magnafacta.nl>
 * @copyright  Copyright (c) 2016, Erasmus MC and MagnaFacta B.V.
 * @license    New BSD License
 */

namespace Gems\HL7\Extractor;

use Gems\HL7\Node\Message;

/**
 *
 * @package    Gems
 * @subpackage HL7\Extractor
 * @copyright  Copyright (c) 2016, Erasmus MC and MagnaFacta B.V.
 * @license    New BSD License
 * @since      Class available since version 1.8.1 Oct 22, 2016 9:24:42 PM
 */
class AppointmentExtractor implements ExtractorInterface
{
    protected $_fieldList = [
        'gr2o_patient_nr'      => '_extractPatientId',
        'gr2o_id_organization' => '_extractOrganizationId',
        'gap_source'           => '_extractAppointmentSource',
        'gap_id_in_source'     => '_extractAppointmentPlacerId',
        'gap_code'             => '_extractAppointmentCode',
    ];

    /**
     *
     * @var string Start id to identify the source if the appointment as HL7
     */
    protected $hl7SourceStartId = 'HL7v24.';

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
     * @var \Gems\HL7\Segment\SCHSegment;
     */
    protected $sch;

    /**
     *
     * @return string Or false when should not be used
     */
    protected function _extractAppointmentCode()
    {

        return $this->sch->getPlacerAppointmentId();
    }

    /**
     *
     * @return string Or false when should not be used
     */
    protected function _extractAppointmentPlacerId()
    {

        return $this->sch->getPlacerAppointmentId();
    }

    /**
     *
     * @return string Or false when should not be used
     */
    protected function _extractAppointmentSource()
    {

        return $this->hl7SourceStartId . $this->_extractOrganizationId();
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
     * @param Message $message
     * @return array Or false when not valid
     */
    public function extractRow(Message $message)
    {
        $this->message = $message;
        $this->pid     = $message->getPidSegment();
        $this->sch     = $message->getSchSegment();
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
}
