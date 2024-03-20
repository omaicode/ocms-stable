<?php

namespace OCMS\Larinfo\Wrapper;

use Linfo\Exceptions\FatalException;
use Linfo\Linfo;
use OCMS\Larinfo\Windows\WindowsOs;
use OCMS\Larinfo\Windows\WindowsUname;
use OCMS\Larinfo\Windows\WindowsWrapper;

class WrapperFactory
{
    /**
     * @var array
     */
    private array $config;

    /**
     * WrapperFactory constructor.
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        $this->config = $config;
    }

    /**
     * @return LinfoWrapperContract
     */
    public function getWrapper(): LinfoWrapperContract
    {
        try {
            $linfo = new Linfo($this->config);

            return new LinfoWrapper($linfo);
        } catch (FatalException $exception) {
            $windows = new WindowsOs($this->config, new WindowsUname());

            return new WindowsWrapper($windows);
        }
    }
}
