<?php

namespace Gems\Test\HL7\Extractor;

use Gems\HL7\Extractor\RespondentExtractor;
use Gems\HL7\Node\Message;
use Gems\HL7\Unserializer;
use PHPUnit_Framework_TestCase;
use Zalt\Loader\ProjectOverloader;
use const TEST_DIR;

abstract class RespondentExtractorTestAbstract extends PHPUnit_Framework_TestCase
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
        
        $loader = new ProjectOverloader([
            'Gems\\Clover',
            'Gems',
            'PharmaIntelligence',
        ]);
        $loader->createServiceManager();

        $this->unserializer        = $loader->create('HL7\\Unserializer');
        $this->respondentExtractor = new RespondentExtractor();
    }

    /**
     *
     * @staticvar array $map
     * @param type $filename
     * @return Message
     */
    protected function _getMessageFromFile($filename)
    {
        static $map = array(
            'EVN' => 'Gems\HL7\Segment\EVNSegment',
            'MSA' => 'Gems\HL7\Segment\MSASegment',
            'MSH' => 'Gems\HL7\Segment\MSHSegment',
            'PID' => 'Gems\HL7\Segment\PIDSegment',
            'PV1' => 'Gems\HL7\Segment\PV1Segment',
            'SCH' => 'Gems\HL7\Segment\SCHSegment',
        );

        $file    = TEST_DIR . '/resources/' . $filename;
        $testHl7 = file_get_contents($file);

        $message = $this->unserializer->loadMessageFromString($testHl7, $map);
        
        return $message;
    }

}
