<?php
namespace Gems\HL7;

use PharmaIntelligence\HL7\Node\Segment as OldSegment;

/**
 * Description of Segment
 *
 * @author Menno Dekker <menno.dekker@erasmusmc.nl>
 */
class Segment extends OldSegment {
    
    /**
     * Utility function 
     * 
     * @param int $idx 1 based index
     * @param int $offset 0 basex index
     */
    protected function get($idx, $offset = null)
    {
        $realIdx = $idx - 1;
        $object = $this->offsetExists($realIdx) ? $this->offsetGet($realIdx) : null;
        
        if (!is_null($offset) && !is_null($object)) {
            $c = count($object);
            if ($offset == 0 && count($object) == 0) return $object->value;
            
            return $object->offsetExists($offset) ? $object->offsetGet($offset) : null;
        }
        
        return $object;
    }
}
