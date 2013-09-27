<?php

namespace SofaChamps\Bundle\BowlPickemBundle\Util;

use JMS\DiExtraBundle\Annotation as DI;

/**
 * TextManipulator
 *
 * TODO - Move to Core
 * @DI\Service("text.manipulator")
 */
class TextManipulator
{
    /**
     * turncateText
     * Truncates a string at a word boundary
     * String return will be at most the maxLength size + the length of the additionalText
     *
     * @param string $text - The string to truncate
     * @param int $maxLength - The maximum length of the returned string
     * @param string $additionalText - If the length of the string is larger than maxLenght, what will be appeneded
     * @return string
     */
    public function truncateText($text, $maxLen, $additionalText = '...')
    {
        $textLen = strlen($text);

        if ($textLen <= $maxLen) {
            return $text;
        }

        $maxTextLen = $maxLen + strlen($textLen);

        return preg_replace('/\s+?(\S+)?$/', '', substr($text, 0, $maxTextLen)) . $additionalText;
    }
}
