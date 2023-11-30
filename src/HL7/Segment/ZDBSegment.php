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
class ZDBSegment extends \Gems\HL7\Segment
{
    const IDENTIFIER = 'ZDB';

    /**
     *
     * @param string $segmentName
     */
    public function __construct($segmentName = self::IDENTIFIER)
    {
        parent::__construct($segmentName);
    }
}