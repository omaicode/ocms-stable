<?php

namespace App\Exceptions;

use RuntimeException;

class ComposerLoaderException extends RuntimeException
{
    /**
     * @return \App\Exceptions\ComposerLoaderException
     */
    public static function duplicate(string $name): self
    {
        return new static(sprintf(
            'A package named "%s" already exists.',
            $name
        ));
    }
}
