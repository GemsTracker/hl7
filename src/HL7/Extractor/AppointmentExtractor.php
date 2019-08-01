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
        'gap_patient_nr'       => '_extractPatientId',
        'gap_organization_id'  => '_extractOrganizationId',
        'gap_source'           => '_extractAppointmentSource',
        'gap_id_in_source'     => '_extractAppointmentPlacerId',
        'gap_admission_code'   => '_extractAppointmentCode',
        'gap_status_code'      => '_extractAppointmentStatus',
        'gap_admission_time'   => '_extractAdmissionTime',
        'gap_discharge_time'   => '_extractEndTime',
        'gap_attended_by'      => '_extractAttendedBy',
        'gap_referred_by'      => '_extractReferredBy',
        'gap_activity'         => '_extractActivity',
        'gap_procedure'        => '_extractProcedure',
        'gap_location'         => '_extractLocation',
        'gap_subject'          => '_extractSubject',
        'gap_comment'          => '_extractComment',
    ];

    /**
     *
     * @var string The date time format used in the export
     */
    protected $_datetimeFormat = 'c';

    /**
     * Default code value
     *
     * @var string
     */
    protected $defaultCode = 'A';

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
    protected function _extractActivity()
    {
        return ((string) $this->sch->getAppointmentTypeText()) ? : false;
    }

    /**
     *
     * @return string Or false when should not be used
     */
    protected function _extractAdmissionTime()
    {

        return $this->sch->getAppointmentStartDatetime()->getFormatted($this->_datetimeFormat) ?: false;
    }

    /**
     *
     * @return string Or false when should not be used
     */
    protected function _extractEndTime()
    {
        return $this->sch->getAppointmentEndDatetime()->getFormatted($this->_datetimeFormat) ?: false;
    }

    /**
     *
     * @return string Or false when should not be used
     */
    protected function _extractAppointmentCode()
    {

        return $this->defaultCode ?: false;
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
    protected function _extractAppointmentStatus()
    {
        $type = $this->message->getMshSegment()->getMessageType();

        if (in_array($type->getTriggerEvent(), ['S15', 'S16', 'S17'])) {
            return 'CA';
        }

        $code = $this->message->getSchSegment()->getFillerStatusCode();
        if (!is_null($code)) {
            $status = strtolower($code->getId());
            if (in_array($status, ['cancelled'])) {
                return 'CA';    // Cancelled
            }
            if (in_array($status, ['completed'])) {
                return 'CO';    // Completed
            }
        }

        return 'AC';            // Active
    }

    /**
     *
     * @return string Or false when should not be used
     */
    protected function _extractAttendedBy()
    {
        return ((string) $this->sch->getFillerContact()) ? : false;
    }

    /**
     *
     * @return string Or false when should not be used
     */
    protected function _extractComment()
    {
        return false;
    }

    /**
     *
     * @return string Or false when should not be used
     */
    protected function _extractLocation()
    {
        $location = $this->sch->getFillerLocation();
        if ($location) {
            return $location->getLocationDescription();
        }

        return false;
    }

    /**
     *
     * @return string Or false when should not be used
     */
    protected function _extractOrganizationId()
    {
        return $this->message->getMshSegment()->getSendingOrganizationId() ?: false;
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
    protected function _extractProcedure()
    {
        return ((string) $this->sch->getAppointmentReason()) ? : false;
    }

    /**
     *
     * @return string Or false when should not be used
     */
    protected function _extractReferredBy()
    {
        return ((string) $this->sch->getContactPerson()) ? : false;
    }

    /**
     *
     * @return string Or false when should not be used
     */
    protected function _extractSubject()
    {
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
        if (! isset($output['gap_patient_nr'], $output['gap_admission_time'])) {
            return false;
        }

        return $output;
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
