<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Admin;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class MakeAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'cms:make-admin';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create admin account';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $name = $this->ask("What's your name?", "Anonymous");
        if(!$name) {
            $this->error('Please enter your name.');
            return;
        }

        $username = $this->ask("Enter username use for login");

        if(!$username) {
            $this->error('Please enter username.');
            return;
        }

        $email = $this->ask("Enter email use for recovery");

        if(!$email) {
            $this->error('Please enter email.');
            return;
        }

        $password = $this->ask("Enter password use for login");

        if(!$password) {
            $this->error('Please enter password.');
            return;
        }   
        
        $super_user = true;
        $admin = Admin::create(compact('name', 'email', 'username', 'password', 'super_user'));

        if($admin) {
            $admin->syncRoles(['Super Admin']);
            $this->info("Created admin account successfully!");
        } else {
            $this->error("Something went wrong!");
        }
    }
}
