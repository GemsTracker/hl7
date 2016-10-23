<?php

/**
 *
 * @package    Gems
 * @subpackage HL7\Segment
 * @author     Menno Dekker <menno.dekker@erasmusmc.nl>
 * @copyright  Copyright (c) 2016, Erasmus MC and MagnaFacta B.V.
 * @license    New BSD License
 */

namespace Gems\HL7\Segment;

use Gems\HL7\Segment;
use Exception;
use PharmaIntelligence\HL7\Node\Component;
use PharmaIntelligence\HL7\Node\Field;
use PharmaIntelligence\HL7\Node\Repetition;

/**
 * MSA segment, used in ACK message to acknowledge receiving a message
 *
 * See http://hl7-definition.caristix.com:9010
 *
 * SEQ	LENGTH	DT	OPT	RPT / #	TBL #	NAME
 * MSA.1	2	ID	R	1	0008	Acknowledgment Code
 * MSA.2	20	ST	R	1		Message Control ID
 * MSA.3	80	ST	B	1		Text Message
 * MSA.4	15	NM	O	1		Expected Sequence Number
 * MSA.5	0	ID	W	1		Delayed Acknowledgment Type
 * MSA.6	250	CE	B	1	0357	Error Condition
 *
 * @package    Gems
 * @subpackage HL7\Segment
 * @copyright  Copyright (c) 2016 Erasmus MC and MagnaFacta BV
 * @license    New BSD License
 * @since      Class available since version 1.8.1 Oct 20, 2016 404661
 */
class MSASegment extends Segment {

    const IDENTIFIER = 'MSA';

    public function __construct($segmentName = self::IDENTIFIER) {
        parent::__construct($segmentName);
    }

    public function getAcknowledgementCode() {
        return (string) $this->get(1,0);
    }

    public function getMessageControlId() {
        return (string) $this->get(2,0);
    }

    /**
     * Set the acknowledgement code
     *
     * AA = Accept
     * AE = Error
     * AR = Reject
     *
     * enhanced mode
     *
     * CA = Commit Accept
     * CE = Commit Error
     * CR = Commit Reject
     *
     * @param type $code
     * @throws Exception
     */
    public function setAcknowledgementCode($code = "AA")
    {
        if (strlen($code) !== 2) {
            throw new Exception('Acknowledgement code should always be exactly 2 characters.');
        }

        $field = new Field();
        $field->setParent($this);

        $repetition = new Repetition();
        $field->append($repetition);
        $component = new Component($code);
        $repetition->append($component);

        $this->children[1] = $field;
    }

    public function setMessageControlId($id)
    {
        $field = new Field();
        $field->setParent($this);

        $repetition = new Repetition();
        $field->append($repetition);
        $component = new Component($id);
        $repetition->append($component);

        $this->children[2] = $field;
    }

    public function __toString() {
        $children = array_merge(array($this->segmentName), $this->children);
        $delimiter = $this->getRootNode()->escapeSequences['field_delimiter'];
        $result = implode($delimiter, $children);

        return $result;
    }
}
