<?php
namespace App\Supports;

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\View;
use InvalidArgumentException;
use App\Emails\DefaultMail;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Container\ContainerExceptionInterface;

class Email
{
    protected $templates = [];

    /**
     * 
     * @param mixed $name 
     * @param array $data 
     * @return $this 
     * @throws InvalidArgumentException 
     */
    public function addTemplate($name, array $data)
    {
        $data['type'] = 'text';
        $keys = array_keys($data);

        if(!in_array('subject', $keys) || !in_array('content', $keys)) {
            throw new InvalidArgumentException("Email template data must have subject and content.");
        }

        if(View::exists($data['content'])) {
            $data['type'] = 'view';
        }

        $this->templates[$name] = $data;
        
        return $this;
    }

    /**
     * 
     * @param mixed $name 
     * @return bool 
     */
    public function exists($name)
    {
        if(isset($this->templates[$name])) {
            return true;
        }

        return false;
    }    

    /**
     * 
     * @param mixed $name 
     * @return $this 
     */
    public function removeTemplate($name)
    {
        if($this->exists($name)) {
            unset($this->templates[$name]);
        }

        return $this;
    }

    /**
     * 
     * @param string $template 
     * @param mixed $email 
     * @param array $variables 
     * @return void 
     * @throws InvalidArgumentException 
     * @throws BindingResolutionException 
     * @throws BindingResolutionException 
     * @throws NotFoundExceptionInterface 
     * @throws ContainerExceptionInterface 
     */
    public function send(string $template, $email, array $variables = [])
    {
        if(!isset($this->templates[$template])) {
            throw new InvalidArgumentException("Email template doesn't exists.");
        }        

        if(filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
            throw new InvalidArgumentException("Email is not valid.");
        }

        $template   = $this->templates[$template];
        $mail       = Mail::to($email);
        $mail_class = new DefaultMail(
            $template['type'] == 'view' ? true : false,
            __($template['subject']),
            $template['content'],
            $variables
        );

        if(config('mail.queue', false) == 1) {
            $mail->queue($mail_class);
        } else {
            $mail->send($mail_class);
        }
    }

    /**
     * 
     * @return Collection 
     */
    public function all()
    {
        return collect($this->templates);
    }

    /**
     * 
     * @param mixed $name 
     * @return mixed 
     */
    public function get($name)
    {
        return isset($this->templates[$name]) ? $this->templates[$name] : null;
    }

    /**
     * 
     * @param mixed $name 
     * @return mixed 
     */
    public function set($name, $value)
    {
        if(!$template = $this->get($name)) {
            return false;
        }

        $rules   = Helper::getViewRules();
        $search  = $rules->values()->all();
        $replace = $rules->keys()->all();

        $view = Helper::getRawView($template['content']);
        $value = str_replace($search, $replace, $value);

        return file_put_contents($view['full_path'], $value);
    }
}