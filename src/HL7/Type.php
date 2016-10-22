<?php

namespace Gems\HL7;

use PharmaIntelligence\HL7\Node\Field;
use PharmaIntelligence\HL7\Node\Repetition;

/**
 * Description of Type
 *
 * @author Menno Dekker <menno.dekker@erasmusmc.nl>
 */
class Type {

    /**
     * @var Field
     */
    protected $content;


    public function __construct(Repetition $node) {
        $this->content = $node;
    }

    public function _get($idx, $default = null)
    {
        $realIdx = $idx - 1;
        if ($realIdx == 0 && count($this->content) == 0) return $this->content->value;
        return $this->content->offsetExists($realIdx) ? $this->content->offsetGet($realIdx) : $default;
    }

    public function __toString() {
        return (string) $this->content;
    }
}
