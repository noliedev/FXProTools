<?php
namespace Respect\Validation\Rules;

use DateTime;

class Date extends AbstractRule
{
    public $format = null;

    public function __construct($format = null)
    {
        $this->format = $format;
    }

    public function validate($input)
    {
        if ($input instanceof DateTime) {
            return true;
        } elseif (!is_string($input)) {
            return false;
        } elseif (is_null($this->format)) {
            return false !== strtotime($input);
        }

        $exceptionalFormats = array(
            'c'     =>  'Y-m-d\TH:i:sP',
            'r'     =>  'D, d M Y H:i:s O',
        );

        if (in_array($this->format, array_keys($exceptionalFormats))) {
            $this->format = $exceptionalFormats[ $this->format ];
        }

        $info = date_parse_from_format($this->format, $input);

        return ($info['error_count'] === 0 && $info['warning_count'] === 0);
    }
}
