<?php

namespace Gems\HL7\Test;

use Gems\HL7\Extractor\RespondentExtractor;
use Gems\HL7\Unserializer;

abstract class RespondentExtractorTestAbstract extends TestAbstract
{

    /**
     * @var RespondentExtractor
     */
    protected $respondentExtractor;

    /**
     * @var Unserializer
     */
    protected $unserializer;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        parent::setUp();
        
        $this->respondentExtractor = new RespondentExtractor();
    }

}
