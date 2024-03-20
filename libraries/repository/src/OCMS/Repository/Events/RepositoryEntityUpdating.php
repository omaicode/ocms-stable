<?php
namespace OCMS\Repository\Events;

/**
 * Class RepositoryEntityUpdated
 * @package OCMS\Repository\Events
 * @author Anderson Andrade <contato@andersonandra.de>
 */
class RepositoryEntityUpdating extends RepositoryEventBase
{
    /**
     * @var string
     */
    protected $action = "updating";
}
