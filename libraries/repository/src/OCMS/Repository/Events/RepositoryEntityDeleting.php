<?php
namespace OCMS\Repository\Events;

/**
 * Class RepositoryEntityDeleted
 * @package OCMS\Repository\Events
 * @author Anderson Andrade <contato@andersonandra.de>
 */
class RepositoryEntityDeleting extends RepositoryEventBase
{
    /**
     * @var string
     */
    protected $action = "deleting";
}
