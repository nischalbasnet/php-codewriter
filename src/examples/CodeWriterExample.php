<?php
require '../../vendor/autoload.php';

//Test file writer
use NBasnet\CodeWriter\BaseComponent;
use NBasnet\CodeWriter\CodePage;
use NBasnet\CodeWriter\CodeWriterSettings;
use NBasnet\CodeWriter\Components\ArrayComponent;
use NBasnet\CodeWriter\Components\BlankComponent;
use NBasnet\CodeWriter\Components\ClassComponent;
use NBasnet\CodeWriter\Components\FunctionComponent;
use NBasnet\CodeWriter\Components\GeneralComponent;
use NBasnet\CodeWriter\Components\VariableComponent;
use NBasnet\CodeWriter\FileWriter;
use NBasnet\CodeWriter\ISyntaxGrammar;

/**
 * @return string
 */
function generateCodeForWritingToFile()
{
    $code_writer_settings = CodeWriterSettings::create(ISyntaxGrammar::PHP, $indent = 0);

    $file_writer = FileWriter::create($code_writer_settings);
    $page        = CodePage::create('App\Controllers\Test', [
        'App\Controllers\Test',
        'App\Controllers\Test',
    ]);

    $class = ClassComponent::create('TestController')
        ->setExtends("Controller");

    $variable = VariableComponent::create("var")->setValue("Is Name", "string");
    $constant = VariableComponent::create("TEST")->setValue("VALUE 1")->makeConstant();
    $array    = ArrayComponent::create("what_is_this", TRUE)
        ->setValue([
            "string" => "is game",
            "number" => 2,
            "bool"   => FALSE,
        ]);

    $static_variable = VariableComponent::create('static::PLAY')->setValue('$test')->rawOutput();

    $function_parameters[] = $variable;
    $function_parameters[] = ArrayComponent::create("my_array");

    $function = FunctionComponent::create("myFunction")
        ->setFunctionDescription('Returns value for $val')
        ->setParameters($function_parameters)
        ->setAccessIdentifier(BaseComponent::ACCESS_PUBLIC)
        ->appendComponent($array)
        ->appendComponent($variable)
        ->appendComponent($static_variable)
        ->appendBlankLine()
        ->appendComponent(GeneralComponent::create('return $val;'));

    $class->appendComponent($variable)
        ->appendComponent($constant)
        ->appendBlankLine()
        ->appendComponent($array)
        ->appendBlankLine()
        ->appendComponent($function);

    $page->appendComponent(BlankComponent::create());

    $page->appendComponent($class);
    $file_writer->appendComponent($page);

    return $file_writer->writeComponent();
}

//Call the code generator function
$generated_file = generateCodeForWritingToFile();

print_r($generated_file);
