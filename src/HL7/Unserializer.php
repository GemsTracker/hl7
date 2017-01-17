<?php

/**
 *
 * @package    Gems
 * @subpackage HL7
 * @author     Matijs de Jong <mjong@magnafacta.nl>
 * @copyright  Copyright (c) 2016 hl7
 * @license    New BSD License
 */
namespace Gems\HL7;

use PharmaIntelligence\HL7\Unserializer as PharmaUnserializer;

use Zalt\Loader\Target\TargetInterface;
use Zalt\Loader\Target\TargetTrait;

/**
 *
 *
 * @package    Gems
 * @subpackage HL7
 * @copyright  Copyright (c) 2016 hl7
 * @license    New BSD License
 * @since      Class available since version 1.8.1 Oct 20, 2016 404661
 */
class Unserializer extends PharmaUnserializer implements TargetInterface
{
    use TargetTrait;

    /**
     *
     * @var \Zalt\Loader\ProjectOverloader
     */
    protected $loader;

    /**
     * Allows the loader to know the resources to set.
     *
     * Returns those object variables defined by the subclass but not at the level of this definition.
     *
     * Can be overruled.
     *
     * @return array of string names
     */
    public function getRegistryRequests()
    {
        return ['loader'];
    }

    /**
     *
     * @param string $hl7String
     * @param array $segmentClassMap
     * @param boolean $noEncodingCheck  To prevent endless loops, use this switch
     * @return \Gems\HL7\Node\Message
     */
    public function loadMessageFromString($hl7String, $segmentClassMap = array(), $noEncodingCheck = false)
    {
        $this->hl7String = $hl7String;
        $this->segmentClassMap = $segmentClassMap;

        $this->validate();
        $this->setEscapeSequences();

        $this->message = $this->loader->create('HL7\\Node\\Message');
        $this->message->setEscapeSequences($this->escapeSequences);

        $this->splitSegments();
        
        if ($encoding = $this->message->getMessageHeaderSegment()->getCharacterset()) {
            $internal = mb_internal_encoding();
            if ($noEncodingCheck === false && $encoding !== $internal) {
                $message = mb_convert_encoding($hl7String, $internal, $encoding);
                // Use third parameter to prevent endless loops
                $this->message = $this->loadMessageFromString($message, $segmentClassMap, true);
            }
        }
        return $this->message;
    }
}
