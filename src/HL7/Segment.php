<?php

/**
 *
 * @package    Gems
 * @subpackage HL7
 * @author     Menno Dekker <menno.dekker@erasmusmc.nl>
 * @copyright  Copyright (c) 2016 Erasmus MC and MagnaFacta BV
 * @license    New BSD License
 */

namespace Gems\HL7;

use PharmaIntelligence\HL7\Node\Segment as PharmaSegment;

/**
 *
 *
 * @package    Gems
 * @subpackage HL7
 * @copyright  Copyright (c) 2016 Erasmus MC and MagnaFacta BV
 * @license    New BSD License
 * @since      Class available since version 1.8.1 Oct 20, 2016 404661
 */
class Segment extends PharmaSegment
{
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
