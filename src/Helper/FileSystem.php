<?php

namespace Application\Helper;

class FileSystem {

    public static function normalizePathSeparator($pathToNormalize) {
        $systemSeparator    = PATH_SEPARATOR;
        if( $systemSeparator !== '/') {
            return str_replace('/', $systemSeparator, $pathToNormalize);
        } else {
            return $pathToNormalize;
        }
    }

}