<?php

/**
 * Camelize a string
 *
 * @param string $string
 * @param bool $upperFirst
 * @return string
 */
function camelize(string $string, bool $upperFirst = false): string
{
    $string = preg_replace('/(?:^|_|-)(.?)/', "mb_strtoupper('$1')", rtrim($string, '_-'));

    if ($upperFirst === true) {
        $string[0] = mb_strtoupper($string[0]);
    }

    return $string;
}

/**
 * Get the checksum of a given string
 *
 * @param string $string data to process
 * @param string|null $algorithm 'sha256' is default value or the first supported
 * @throws \InvalidArgumentException An hash algorithm is necessary
 * @return string
 */
function checksum_string(string $string, string $algorithm = null): string
{
    // check algorithm
    $algorithm = hash_check($algorithm);

    if ($algorithm === false) {
        throw new \InvalidArgumentException('An hash algorithm is necessary');
    }

    $hash = hash($algorithm, $string, true);
    $hash_base64 = base64_encode($hash);

    return $algorithm . "-" . $hash_base64;
}

/**
 * Cut text without cutting words
 *
 * @param string $text text to cut
 * @param int $length text length
 * @param bool $escape escape spacial chars
 * @param null|string $suffix append a suffix like "..."
 * @param bool $truncate truncate text including words
 * @return string
 */
function cut_text(
    string $text,
    int $length = 100,
    bool $escape = true,
    string $suffix = null,
    bool $truncate = false
): string {
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

/**
 * It is checked whether the hash algorithm is valid or else it will be tried with the sha256 hash otherwise
 * the first supported algorithm will be returned
 *
 * @param string|null $algorithm
 * @return string|bool
 */
function hash_check(string $algorithm = null): string
{
    // check algorithm
    if ($algorithm === null || // not defined
        array_search($algorithm, hash_algos(), true) === false) {
        $algorithm = array_search('sha256', hash_algos(), true) ? 'sha256' : current(hash_algos());
    }

    if (!$algorithm) {
        return false;
    }

    return $algorithm;
}

/**
 * Generate html attributes from an array
 *
 * @param array $attributes
 * @return string
 */
function html_attributes(array $attributes): string
{
    $string = '';

    foreach (array_filter($attributes) as $k => $v) {
        $string .= ' ';
        $v = htmlspecialchars($v, ENT_QUOTES, 'UTF-8');

        // boolean attribute
        if (is_numeric($k)) {
            $string .= $v;
        } else {
            $string .= htmlspecialchars($k, ENT_QUOTES, 'UTF-8') . '="' . $v . '"';
        }
    }

    return $string;
}

/**
 * Upper Case Names
 *
 * @author jmarois at ca dot ibm dot com
 * @see http://php.net/manual/es/function.ucwords.php#96179
 * @param string $string
 * @return string
 */
function ucname(string $string): string
{
    $string = ucwords(strtolower($string));

    foreach (['-', '\''] as $delimiter) {
        if (strpos($string, $delimiter) !== false) {
            $string = implode($delimiter, array_map('ucfirst', explode($delimiter, $string)));
        }
    }

    return $string;
}

/**
 * Uncamelize a string
 *
 * @param string $string
 * @param string $separator
 * @return string
 */
function uncamelize(string $string, string $separator = '_'): string
{
    return mb_strtolower(preg_replace('/([^\p{L}])(\p{L})/u', "$1" . $separator . "$2", $string));
}


/**
 * Convertir un array en string
 *
 * @param mixed $data
 * @param int $depth if xdebug is installed, var_dump is modified, to show all data modify depth value
 * @return string
 */
function var_to_string($data, int $depth = -1): string
{
    if (extension_loaded('xdebug') === true) {
        ini_set('xdebug.var_display_max_children', $depth);
        ini_set('xdebug.var_display_max_depth', $depth);
        ini_set('xdebug.var_display_max_data', $depth);
    }

    ob_start();
    var_dump($data);

    return ob_get_clean();
}
