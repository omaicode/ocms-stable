<?php
namespace App\Supports;

use Illuminate\Http\Response;
use Illuminate\Support\Collection;

class ApiResponse extends Response
{
    protected $message    = 'success';
    protected $code       = 0;
    protected $success    = true;
    protected $with_data  = [];

    public function __construct()
    {
        parent::__construct();
        $this->setContent($this->getData());
    }

    private function getData()
    {
        return [
            'code'    => $this->code,
            'success' => $this->success,
            'message' => $this->message,
            'data'    => $this->with_data
        ];
    }    

    public function message(string $message)
    {
        $this->message = $message;
        $this->setContent($this->getData());

        return $this;
    }

    public function code(int $code)
    {
        $this->code = $code;
        $this->setContent($this->getData());

        return $this;
    }

    public function success($message = 'success')
    {
        $this->success = true;
        $this->code    = 0;
        $this->message = $message;
        $this->setContent($this->getData());

        return $this;
    }

    public function error(string $message = 'error', int $code = -1)
    {
        $this->success = false;
        $this->code    = $code;
        $this->message = $message;
        $this->setContent($this->getData());

        return $this;
    }

    public function data($data = [])
    {
        if($data instanceof Collection) {
            $data = $data->toArray();
        }

        $this->with_data = $data;
        $this->setContent($this->getData());

        return $this;
    }
}