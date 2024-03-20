<?php

namespace OCMS\Larinfo\Entities;

use Linfo\OS\OS;
use OCMS\Larinfo\Wrapper\LinfoWrapperContract;

abstract class LinfoEntity
{
    /**
     * @var OS|null
     */
    protected ?OS $linfo;

    /**
     * ServerInfo constructor.
     * @param LinfoWrapperContract $linfo
     */
    public function __construct(LinfoWrapperContract $linfo)
    {
        $this->linfo = $this->parse($linfo);
    }

    /**
     * @param  LinfoWrapperContract $linfo
     * @return OS|null
     */
    protected function parse(LinfoWrapperContract $linfo): ?OS
    {
        return $linfo->getParser();
    }
}
