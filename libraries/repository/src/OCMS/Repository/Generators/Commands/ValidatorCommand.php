<?php
namespace OCMS\Repository\Generators\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use OCMS\Repository\Generators\FileAlreadyExistsException;
use OCMS\Repository\Generators\ValidatorGenerator;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

/**
 * Class ValidatorCommand
 * @package OCMS\Repository\Generators\Commands
 */
class ValidatorCommand extends Command
{

    /**
     * The name of command.
     *
     * @var string
     */
    protected $name = 'repository:validator';

    /**
     * The description of command.
     *
     * @var string
     */
    protected $description = 'Create a new validator.';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Validator';


    /**
     * Execute the command.
     *
     * @see fire()
     * @return void
     */
    public function handle(){
        $this->laravel->call([$this, 'fire'], func_get_args());
    }

    /**
     * Execute the command.
     *
     * @return void
     */
    public function fire()
    {
        try {
            (new ValidatorGenerator([
                'name' => $this->argument('name'),
                'rules' => $this->option('rules'),
                'force' => $this->option('force'),
            ]))->run();
            $this->info("Validator created successfully.");
        } catch (FileAlreadyExistsException $e) {
            $this->error($this->type . ' already exists!');

            return false;
        }
    }


    /**
     * The array of command arguments.
     *
     * @return array
     */
    public function getArguments()
    {
        return [
            [
                'name',
                InputArgument::REQUIRED,
                'The name of model for which the validator is being generated.',
                null
            ],
            [
                'module',
                InputArgument::REQUIRED,
                'The name of module.',
                null
            ],
        ];
    }


    /**
     * The array of command options.
     *
     * @return array
     */
    public function getOptions()
    {
        return [
            [
                'rules',
                null,
                InputOption::VALUE_OPTIONAL,
                'The rules of validation attributes.',
                null
            ],
            [
                'force',
                'f',
                InputOption::VALUE_NONE,
                'Force the creation if file already exists.',
                null
            ],
        ];
    }
}
