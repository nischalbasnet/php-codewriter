<?php
require '../../vendor/autoload.php';

//Test file writer
use NBasnet\CodeWriter\BaseComponent;
use NBasnet\CodeWriter\CodePage;
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
    $file_writer = FileWriter::create(ISyntaxGrammar::PHP);
    $page        = CodePage::create('App\Controllers\Test', [
        'App\Controllers\Test',
        'App\Controllers\Test',
    ]);

    $class = ClassComponent::create('TestController')
        ->setExtends("Controller");

    $variable = VariableComponent::create("var")->setValue("Is Name");
    $constant = VariableComponent::create("TEST")->setValue("VALUE 1")->makeConstant();
    $array    = ArrayComponent::create("what_is_this", TRUE)
        ->setValue([
            "string" => "is game",
            "number" => 2,
            "bool"   => FALSE,
        ]);

    $function = FunctionComponent::create("myFunction")
        ->setFunctionDescription('Return $val')
        ->setParameters(['array $my_array', '$val']);

    $function->addComponentToBody($array)
        ->setAccessIdentifier(BaseComponent::ACCESS_PUBLIC)
        ->addComponentToBody($variable)
        ->addComponentToBody(GeneralComponent::create()->addLine())
        ->addComponentToBody(GeneralComponent::create('return $val;'));

    $class->addComponents($variable)
        ->addComponents($constant)
        ->addComponents($array)
        ->addComponents($function);

    $page->addComponents(BlankComponent::create());

    $page->addComponents($class);
    $file_writer->addCodeComponent($page);

    return $file_writer->writeComponent();
}

//Call the code generator function
$generated_file = generateCodeForWritingToFile();

print_r($generated_file);
