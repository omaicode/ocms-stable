<?php
namespace OCMS\Repository\Events;

/**
 * Class RepositoryEntityCreated
 * @package OCMS\Repository\Events
 * @author Anderson Andrade <contato@andersonandra.de>
 */
class RepositoryEntityCreated extends RepositoryEventBase
{
    /**
     * @var string
     */
    protected $action = "created";
}
