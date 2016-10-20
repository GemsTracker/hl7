<?php

/**
 *
 * @package    Gems
 * @subpackage HL7
 * @author     Matijs de Jong <mjong@magnafacta.nl>
 * @copyright  Copyright (c) 2016 hl7
 * @license    No free license, do not copy
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
 * @license    Not licensed, do not copy
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
     * @return \Gems\HL7\Node\Message
     */
    public function loadMessageFromString($hl7String, $segmentClassMap = array())
    {
        $this->hl7String = $hl7String;
        $this->segmentClassMap = $segmentClassMap;

        $this->validate();
        $this->setEscapeSequences();

        $this->message = $this->loader->create('HL7\\Node\\Message');
        $this->message->setEscapeSequences($this->escapeSequences);

        $this->splitSegments();
        return $this->message;
    }
}
