<?php

/**
 * @package    Gems
 * @subpackage HL7\Segment
 * @author     Matijs de Jong <mjong@magnafacta.nl>
 */

namespace Gems\HL7\Segment;

use Gems\HL7\Segment;

/**
 * @package    Gems
 * @subpackage HL7\Segment
 * @since      Class available since version 1.0
 */
class AILSegment extends Segment
{
    const IDENTIFIER = 'AIL';

    /**
     *
     * @param string $segmentName
     */
    public function __construct($segmentName = self::IDENTIFIER)
    {
        parent::__construct($segmentName);
    }

    public function getLocationCode()
    {
        $values = explode('^', $this->get(3));
        return isset($values[3]) ? $values[3] : null;
    }
}