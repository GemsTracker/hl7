<?php

/**
 *
 * @package    Gems
 * @subpackage HL7
 * @author     Menno Dekker <menno.dekker@erasmusmc.nl>
 * @copyright  Copyright (c) 2016 Erasmus MC and MagnaFacta BV
 * @license    New BSD License
 */

namespace Gems\HL7;

use PharmaIntelligence\HL7\Node\Field;
use PharmaIntelligence\HL7\Node\BaseNode;

/**
 *
 *
 * @package    Gems
 * @subpackage HL7
 * @copyright  Copyright (c) 2016 Erasmus MC and MagnaFacta BV
 * @license    New BSD License
 * @since      Class available since version 1.8.1 Oct 20, 2016 404661
 */
class Type
{
    /**
     * @var Field
     */
    protected $content;


    public function __construct(BaseNode $node)
    {
        $this->content = $node;
    }

    public function _get($idx, $default = null)
    {
        $realIdx = $idx - 1;
        
        if ($realIdx == 0 && count($this->content) == 0) {
            $value = $this->content->value;
        } elseif ($this->content->offsetExists($realIdx)) {
            $value = $this->content->offsetGet($realIdx);
        } else {
            $value = $default;
        }
        
        if ((string) $value === '""') $value = null;
        return $value;
    }

    public function __toString() {
        return (string) $this->content;
    }
}
