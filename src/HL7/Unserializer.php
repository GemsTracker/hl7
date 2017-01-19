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
     * Encoding to use for incoming messages.
     *
     * When empty encoding from message header will be used.
     *
     * @var string
     */
    protected $_fixedEncoding;

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
     * @param boolean $encodingCheck  To prevent endless loops, use this switch
     * @return \Gems\HL7\Node\Message
     */
    public function loadMessageFromString($hl7String, $segmentClassMap = array(), $encodingCheck = true)
    {
        if ($encodingCheck === true && !is_null($this->_fixedEncoding)) {
            $internalEncoding = mb_internal_encoding();
            // No need to 'autosense' encoding later, change the parameter
            $encodingCheck    = false;
            if ($this->_fixedEncoding !== $internalEncoding) {
                $hl7String = mb_convert_encoding($hl7String, $internalEncoding, $this->_fixedEncoding);
            }
        }

        $this->hl7String       = $hl7String;
        $this->segmentClassMap = $segmentClassMap;

        $this->validate();
        $this->setEscapeSequences();

        $this->message = $this->loader->create('HL7\\Node\\Message');
        $this->message->setEscapeSequences($this->escapeSequences);

        $this->splitSegments();

        /**
         * To 'autosense' encoding, we need to read the msh segment first, when the
         * found encoding does not match the internal encoding, we need to convert
         * the string first, and then generate the message. since this could be a
         * waste of CPU cycles, it is advised to fix the encoding when possible.
         */
        if ($encodingCheck === true && $encoding = $this->message->getMessageHeaderSegment()->getCharacterset()) {
            $internalEncoding = mb_internal_encoding();
            if ($encoding !== $internalEncoding) {
                $message       = mb_convert_encoding($hl7String, $internalEncoding, $encoding);
                // Use third parameter to prevent endless loops
                $this->message = $this->loadMessageFromString($message, $segmentClassMap, false);
            }
        }
        return $this->message;
    }

    /**
     * Set the encoding of incoming messages
     * 
     * When this is set to null, the encoding will be read from the MSH segment
     * 
     * @param string|null $encoding
     * @throws \Exception
     */
    public function setFixedEncoding($encoding = null)
    {
        $available = mb_list_encodings();
        if (is_null($encoding) || in_array($encoding, $available)) {
            $this->_fixedEncoding = $encoding;
        } else {
            throw new \Exception(sprintf("Requested encoding '%s' unavailable, choose one of: %s", $encoding, join(', ', $available)));
        }
    }

}
