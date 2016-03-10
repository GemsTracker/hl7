<?php
namespace Gems\HL7\Segment;

use Gems\HL7\Segment;
use Exception;
use PharmaIntelligence\HL7\Node\Component;
use PharmaIntelligence\HL7\Node\Field;
use PharmaIntelligence\HL7\Node\Repetition;

/**
 * MSA segment, used in ACK message to acknowledge reveiving a message
 *
 * @author Menno Dekker <menno.dekker@erasmusmc.nl>
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
