<?php

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
 * SEQ	LENGTH	DT	OPT	TBL #	NAME
 * TS.1	0       ST	O           Time Of An Event
 *
 * @author Menno Dekker <menno.dekker@erasmusmc.nl>
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

        if (strlen($stamp) >= 8) {
            $dateObject = new DateTime();
            $dateObject->setDate(
                    substr($stamp, 0, 4), substr($stamp, 4, 2), substr($stamp, 6, 2)
            );

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

    public function getTime()
    {
        return $this->exists() ? $this->_dateObject->format(self::FORMAT_TIME) : null;
    }


    /**
     *
     * @return DateTime
     */
    public function getObject() {
        return $this->exists() ? $this->_dateObject : null;
    }

    public function exists()
    {
        return ($this->_dateObject instanceof DateTime);
    }

}
