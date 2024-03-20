<?php
namespace OCMS\TableBuilder\Traits;

use ApiResponse;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;

trait RequestTrait
{
    protected function handleRequest(Request $request)
    {
        if($request->handle == 'table-builder') {
            throw new HttpResponseException(
                ApiResponse::success()->data(json_decode($this->getJsonTable(), true))
            );
        }
    }
}