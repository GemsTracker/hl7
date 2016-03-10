<?php
namespace Gems\HL7\Segment;

use Gems\HL7\Segment;
use Gems\HL7\Type\TS;
use PharmaIntelligence\HL7\Node\Repetition;

/**
 * EVN segment
 * 
 * SEQ	LEN	DT	OPT	RP/#	TBL#	ITEM#	ELEMENT NAME
 * 1	3	ID	B		0003	00099	Event Type Code
 * 2	26	TS	R			00100	Recorded Date/Time 
 * 3	26	TS	O			00101	Date/Time Planned Event
 * 4	3	IS	O		0062    00102	Event Reason Code
 * 5	250	XCN	O	Y	0188    00103	Operator ID
 * 6	26	TS	O			01278	Event Occurred
 * 7	180	HD	O			01534	Event Facility
 *
 * @author Menno Dekker <menno.dekker@erasmusmc.nl>
 */
class EVNSegment extends Segment {
    
    const IDENTIFIER = 'EVN';
    
    public function __construct($segmentName = self::IDENTIFIER) {
        parent::__construct($segmentName);
    }
    
    /**
     * 
     * @param type $idx
     * @return XCN
     */
    protected function _getXCN($idx)
    {
        $result = array();
        if($items = $this->get($idx)) {
            foreach ($items as $item) {
                $result[] = new XCN($item);
            }
        }        

        return $result;       
    }
    
    public function getEventTypeCode() {
        return (string) $this->get(1,0);
    } 
    
    /**
     * 
     * @return TS
     */
    public function getRecordedDateTimestamp() {
        return new TS($this->get(2,0));
    }
    
    /**
     * 
     * @return TS
     */
    public function getPlannedDateTimestamp() {
        return new TS($this->get(3,0));
    }
    
    public function getEventReasonCode() {
        return (string) $this->get(4,0);
    }
    
    /**
     * Get operator ID
     * 
     * Could be repeating
     * 
     * @return \Gems\HL7\Type\XCN
     */
    public function getOperatorId() {
        return $this->_getXCN(5);
    }
    
    /**
     * 
     * @return TS
     */
    public function getEventOccurredDateTimestamp() {
        return new TS($this->get(6,0));
    }
    
    public function getEventFacility() {
        return $this->get(7,0);
    }
}
