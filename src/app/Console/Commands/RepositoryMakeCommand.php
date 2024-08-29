<?php

namespace App\Console\Commands;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Contracts\Filesystem\FileNotFoundException;

class RepositoryMakeCommand extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:repository {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new repository';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Repository';

    /**
     * Build the class with the given name.
     *
     * @param  string  $name
     */
    protected function buildClass($name): string|FileNotFoundException
    {
        $stub = $this->files->get($this->getStub());

        return $this->replaceNamespace($stub, $name)->replaceClass($stub, $name);
    }

    /**
     * Replace the class & model name for the given stub.
     *
     * @param  string  $stub
     * @param  string  $name
     */
    protected function replaceClass($stub, $name): string
    {
        $class = str_replace($this->getNamespace($name) . '\\', '', $name);

        // $model = str_replace("Repository", "", $this->argument('name'));
        $model = str_replace("Repository", "", $class);

        return str_replace(array('ClassName', 'ModelName'), array($class, $model), $stub);
    }

    /**
     * Get the stub file for the generator.
     */
    protected function getStub(): string
    {
        return __DIR__ . '/stubs/repository.stub';
    }

    /**
     * Get the default namespace for the class.
     *
     * @param string $rootNamespace
     */
    protected function getDefaultNamespace($rootNamespace): string
    {
        return $rootNamespace . '\Repositories\Eloquent';
    }
}
