<?php
namespace App\Facades;

use Illuminate\Support\Facades\Facade;
use App\Supports\ApiResponse as SupportsApiResponse;

class ApiResponseFacade extends Facade
{
    /**
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return SupportsApiResponse::class;
    }
}
