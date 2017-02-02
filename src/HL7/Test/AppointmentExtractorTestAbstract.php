<?php

namespace Gems\HL7\Test;

use Gems\HL7\Extractor\AppointmentExtractor;
use Gems\HL7\Unserializer;

abstract class AppointmentExtractorTestAbstract extends TestAbstract
{

    /**
     * @var AppointmentExtractor
     */
    protected $appointmentExtractor;

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
        
        $this->appointmentExtractor = new AppointmentExtractor();
    }

}
