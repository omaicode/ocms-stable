<?php

namespace OCMS\Permission\Exceptions;

use InvalidArgumentException;

class WildcardPermissionNotProperlyFormatted extends InvalidArgumentException
{
    public static function create(string $permission)
    {
        return new static("Wildcard permission `{$permission}` is not properly formatted.");
    }
}
