<?php

/**
 *
 * @package    Gems
 * @subpackage HL7\Segment
 * @author     Matijs de Jong <mjong@magnafacta.nl>
 * @copyright  Copyright (c) 2016, Erasmus MC and MagnaFacta B.V.
 * @license    New BSD License
 */

namespace Gems\HL7\Segment;

use Gems\HL7\Segment;
use Gems\HL7\Type\CE;
use Gems\HL7\Type\PL;
use Gems\HL7\Type\TQ;
use Gems\HL7\Type\TS;
use Gems\HL7\Type\XAD;
use Gems\HL7\Type\XCN;
use PharmaIntelligence\HL7\Node\Field;

/**
 * NTE: Notes and Comments
 *
 * The NTE segment is defined here for inclusion in messages defined in other chapters. It is commonly used for sending notes and comments.
 *
 * See http://hl7-definition.caristix.com:9010
 *
 * SEQ	LENGTH	DT	OPT	RPT / #	TBL #	NAME
 * NTE.1	4	SI	O	1		Set ID - NTE
 * NTE.2	8	ID	O	1	0105	Source of Comment
 * NTE.3	65536	FT	O	*		Comment
 * NTE.4	250	CE	O	1	0364	Comment Type
 *
 * @package    Gems
 * @subpackage HL7\Segment
 * @copyright  Copyright (c) 2016 Erasmus MC and MagnaFacta BV
 * @license    New BSD License
 * @since      Class available since version 1.8.1 Oct 20, 2016 404661
 */
class NTESegment extends Segment
{

    const IDENTIFIER = 'NTE';

    /**
     *
     * @var TQ
     */
    protected $_timingQuantity;

    /**
     *
     * @param string $segmentName
     */
    public function __construct($segmentName = self::IDENTIFIER)
    {
        parent::__construct($segmentName);
    }

    public function getSetId()
    {
        return (string) $this->get(1);
    }

    /**
     * This is a formatted field
     * @TODO: find out how the encoding works (For additional examples of formatting commands see Section 2.10, Use of escape sequences in text fields.)
     * 
     * @return string
     */
    public function getSourceOfComment()
    {
        return (string) $this->get(2);
    }

    public function getComment()
    {
        return (string) $this->get(3);
    }

    /**
     * Get the comment type
     *
     * PI	Patient Instructions
     * AI	Ancillary Instructions
     * GI	General Instructions
     * 1R	Primary Reason
     * 2R	Secondary Reason
     * GR	General Reason
     * RE	Remark
     * DR	Duplicate/Interaction Reason
     *
     * @return string
     */
    public function getCommentType()
    {
        return (string) $this->get(4);
    }

}
