<?php

namespace Statikbe\LaravelMailEditor\Console;

use Illuminate\Console\GeneratorCommand;

class MailMakeCommand extends GeneratorCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'make:mail-class';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new mail class';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Mail';

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return $this->resolveStubPath('/stubs/mail.plain.stub');
    }

    /**
     * Resolve the fully-qualified path to the stub.
     *
     * @param  string  $stub
     * @return string
     */
    protected function resolveStubPath($stub)
    {
        return file_exists($customPath = $this->laravel->basePath(trim($stub, '/')))
            ? $customPath
            : __DIR__.$stub;
    }

    /**
     * Get the default namespace for the class.
     *
     * @param  string $rootNamespace
     *
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace . '\Mails';
    }

    /**
     * Build the class with the given name.
     * Remove the base controller import if we are already in base namespace.
     *
     * @param  string $name
     *
     * @return string
     */
    protected function buildClass($name)
    {
        $replace = [];

        return str_replace(
            array_keys($replace), array_values($replace), parent::buildClass($name)
        );
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
        ];
    }
}
