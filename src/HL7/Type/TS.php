<?php

/**
 *
 * @package    Gems
 * @subpackage HL7\Type
 * @author     Menno Dekker <menno.dekker@erasmusmc.nl>
 * @copyright  Copyright (c) 2016, Erasmus MC and MagnaFacta B.V.
 * @license    New BSD License
 */

namespace Gems\HL7\Type;

use DateTime;
use Gems\HL7\Type;

/**
 * TS: Time Stamp
 *
 * Contains the exact time of an event, including the date and time. The date portion
 * of a time stamp follows the rules of a date field and the time portion follows
 * the rules of a time field. The time zone (+/-ZZZZ) is represented as +/-HHMM offset
 * from UTC (formerly Greenwich Mean Time (GMT)), where +0000 or -0000 both represent
 * UTC (without offset). The specific data representations used in the HL7 encoding
 * rules are compatible with ISO 8824-1987(E).
 *
 * See http://hl7-definition.caristix.com:9010
 *
 * SEQ	LENGTH	DT	OPT	TBL #	NAME
 * TS.1	0       ST	O           Time Of An Event
 *
 * @package    Gems
 * @subpackage HL7\Type
 * @copyright  Copyright (c) 2016, Erasmus MC and MagnaFacta B.V.
 * @license    No free license, do not copy
 * @license    New BSD License
 */
class TS extends Type {

    const FORMAT          = "!YmdHisP";
    const FORMAT_DATE     = "Ymd";
    const FORMAT_TIME     = "His";
    const FORMAT_DATETIME = "YmdHis";
    const FORMAT_TIMEZONE = "P";

    /**
     *
     * @var DateTime|null
     */
    protected $_dateObject = null;

    public function __construct($node)
    {
        parent::__construct($node);

        //  Ignore offset and microseconds for now until we have an example
        $stamp = (string) $node;

        
        if (strlen($stamp) >= 4) {
            $year = substr($stamp, 0, 4);
            $month = substr($stamp, 4, 2);
            if (false === $month || $month == 0) {
                $month = 7;
            }
            $day = substr($stamp, 6, 2);
            if (false === $day || $day == 0) {
                $day = 1;
            }
            
            $dateObject = new DateTime();
            $dateObject->setDate($year, $month, $day);

            $hour   = 0;
            $minute = 0;
            $second = 0;
            if (strlen($stamp) >= 12) {
                $hour   = substr($stamp, 8, 2);
                $minute = substr($stamp, 10, 2);

                if (strlen($stamp) >= 14) {
                    $second = substr($stamp, 12, 2);
                }
            }
            $dateObject->setTime($hour, $minute, $second);

            $this->_dateObject = $dateObject;
        }

    }

    /**
     *
     * @return boolean
     */
    public function exists()
    {
        return ($this->_dateObject instanceof DateTime);
    }

    public function getDate()
    {
        return $this->exists() ? $this->_dateObject->format(self::FORMAT_DATE) : null;
    }

    public function getDateTime()
    {
        return $this->exists() ? $this->_dateObject->format(self::FORMAT_DATETIME) : null;
    }

    public function getFormatted($format)
    {
        return $this->exists() ? $this->_dateObject->format($format) : null;
    }

    /**
     *
     * @return DateTime
     */
    public function getObject()
    {
        return $this->exists() ? $this->_dateObject : null;
    }

    public function getTime()
    {
        return $this->exists() ? $this->_dateObject->format(self::FORMAT_TIME) : null;
    }

    /**
     *
     * @param DateTime $dateTime
     * @return self
     */
    public function setObject(\DateTime $dateTime)
    {
        $this->_dateObject = $dateTime;

        return $this;
    }
}
