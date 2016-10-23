<?php

/**
 *
 * @package    Gems
 * @subpackage HL7\Extractor
 * @author     Matijs de Jong <mjong@magnafacta.nl>
 * @copyright  Copyright (c) 2016 Erasmus MC
 * @license    New BSD License
 */

namespace Gems\HL7\Extractor;

use Gems\HL7\Node\Message;

/**
 *
 * @package    Gems
 * @subpackage HL7\Extractor
 * @copyright  Copyright (c) 2016 Erasmus MC
 * @license    New BSD License
 * @since      Class available since version 1.7.2 Jul 28, 2016 7:09:55 PM
 */
interface ExtractorInterface
{
    /**
     *
     * @param Message $message
     * @return array Or false when not valid
     */
    public function extractRow(Message $message);
}
