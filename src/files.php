<?php

/**
 * Generate a checksum file
 * It is useful to add the integrity attribute to external elements such as js or css files
 * <code>
 *  <link rel="stylesheet"
 *        href="styles.css"
 *        integrity="<?=checksum_file("styles.css", "sha384")?>"
 *        crossorigin="anonymous">
 * </code>
 *
 * @param string $filename
 * @param string|null $algorithm 'sha256' is default hash algorithm
 * @throws \InvalidArgumentException An hash algorithm is necessary
 * @throws \InvalidArgumentException The file does not exists
 * @throws \InvalidArgumentException The file could not be read
 * @return string
 */
function checksum_file(string $filename, string $algorithm = null): string
{
    // check algorithm
    $algorithm = hash_check($algorithm);

    if ($algorithm === false) {
        throw new \InvalidArgumentException('An hash algorithm is necessary');
    }

    // check file exists
    if (!is_file($filename)) {
        throw new \InvalidArgumentException('The file does not exists');
    }

    if (!is_readable($filename)) {
        throw new \InvalidArgumentException('The file could not be read');
    }

    $hash = hash_file($algorithm, $filename, true);
    $hash_base64 = base64_encode($hash);

    return $algorithm . "-" . $hash_base64;
}

/**
 * Converts a number of bytes to a value understandable by humans
 * <code>
 *  echo human_bytes_convert(1024); // 1 KB
 *  echo human_bytes_convert(1024 * 1024 * 5); // 5 MB
 * </code>
 *
 * @see https://es.wikipedia.org/wiki/Byte
 * @param int $bytes
 * @return string
 */
function human_bytes_convert(int $bytes): string
{
    $bytes = abs($bytes);

    if ($bytes === 0) {
        return "0 B";
    }

    $s = ['B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];
    $e = floor(log($bytes, 1024));
    $unit = $s[(int) $e] ?? current($s);

    return round($bytes / pow(1024, $e), 2) . ' ' . $unit;
}
