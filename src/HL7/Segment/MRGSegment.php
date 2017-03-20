<?php

/**
 *
 * @package    Gems
 * @subpackage HL7\Segment
 * @author     Menno Dekker <menno.dekker@erasmusmc.nl>
 * @copyright  Copyright (c) 2017, Erasmus MC
 * @license    New BSD License
 */

namespace Gems\HL7\Segment;

use Gems\HL7\Segment;
use Gems\HL7\Type\CX;
use Gems\HL7\Type\XPN;
use PharmaIntelligence\HL7\Node\Field;

/**
 * MRG: Merge patient information
 *
 * The MRG segment provides receiving applications with information necessary to
 * initiate the merging of patient data as well as groups of records. It is intended
 * that this segment be used throughout the Standard to allow the merging of
 * registration, accounting, and clinical records within specific applications.
 *
 * See http://hl7-definition.caristix.com:9010
 *

 * SEQ          LENGTH	DT	OPT	RPT / #	TBL #	NAME
 * MRG.1	250	CX	R	*		Prior Patient Identifier List
 * MRG.2	250	CX	B	*		Prior Alternate Patient ID
 * MRG.3	250	CX	O	1		Prior Patient Account Number
 * MRG.4	250	CX	B	1		Prior Patient ID
 * MRG.5	250	CX	O	1		Prior Visit Number
 * MRG.6	250	CX	O	1		Prior Alternate Visit ID
 * MRG.7	250	XPN	O	*		Prior Patient Name
 *
 * @package    Gems
 * @subpackage HL7\Segment
 * @copyright  Copyright (c) 2017, Erasmus MC
 * @license    New BSD License
 */
class MRGSegment extends Segment
{
    const IDENTIFIER = 'MRG';

    /**
     *
     * @param string $segmentName
     */
    public function __construct($segmentName = self::IDENTIFIER)
    {
        parent::__construct($segmentName);
    }

    /**
     *
     * @param int $idx
     * @return CX
     */
    protected function _getCX($idx)
    {
        $items = $this->get($idx);

        if ($items instanceof Field) {
            return new CX($items->current());
        }
        return new CX($items);
    }

    /**
     *
     * @param int $idx
     * @return CX
     */
    protected function _getXPN($idx)
    {
        $items = $this->get($idx);

        if ($items instanceof Field) {
            return new XPN($items->current());
        }
        return new XPN($items);
    }

    /**
     * Get prior Patient Identifier List
     *
     * @return CX
     */
    public function getPriorPID()
    {
        return (string) $this->get(1);
    }
    
    /**
     * Get prior Patient Account Number
     *
     * @return CX
     */
    public function getPriorPatientAccountNumber()
    {
        return (string) $this->get(3);
    }
    
    /**
     * Get prior Visit Number
     *
     * @return CX
     */
    public function getPriorVisitNumber()
    {
        return (string) $this->get(5);
    }
    
    /**
     * Get prior Alternate Visit ID
     *
     * @return CX
     */
    public function getPriorAlternateVisitID()
    {
        return (string) $this->get(6);
    }

    /**
     * Get prior Patient Name
     *
     * @return XPN
     */
    public function getPriorName()
    {
        return $this->_getXPN(7);
    }
}
