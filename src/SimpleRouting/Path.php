<?php

namespace Morphable\SimpleRouting;

/**
 * Helper class to normalize things
 */
class Path
{
    /**
     * Normalize path in-case of inconsitencies
     * @param string path
     * @return string normalized path
     */
    public static function normalize(string $path)
    {
        return '/' . trim(trim($path), '/');
    }
}
