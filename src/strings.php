<?php

/**
 * Cut text without cutting words
 * @param string $text text to cut
 * @param int $length text length
 * @param bool $escape escape spacial chars
 * @param null|string $suffix append a suffix like "..."
 * @param bool $truncate truncate text including words
 * @return string
 */
function cutText(string $text, int $length = 100, bool $escape = true, string $suffix = null, bool $truncate = false): string
{
    // remove unnecessary spaces
    $text = trim($text);

    // scape spacial html chars
    if ($escape === true) {
        $text = htmlspecialchars($text, ENT_COMPAT | ENT_HTML5);
    }

    // length always be positive
    $length = abs($length);

    // text is shorter than length?
    if (mb_strlen($text) <= $length) {
        return $text;
    }

    // truncate when requested or when there are no spaces (a single long word)
    if ($truncate === true || mb_strpos(' ', $text) !== false) {
        return trim(mb_substr($text, 0, $length) . ' ' . $suffix);
    }

    // cut the text after a space close to the length
    $text = mb_substr($text, 0, mb_strpos($text, ' ', $length));

    return trim($text . ' ' . $suffix);
}