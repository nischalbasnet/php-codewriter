<?php
require '../../vendor/autoload.php';

//Test file writer
use NBasnet\CodeWriter\CodePage;
use NBasnet\CodeWriter\Components\ArrayComponent;
use NBasnet\CodeWriter\Components\BaseComponent;
use NBasnet\CodeWriter\Components\BlankComponent;
use NBasnet\CodeWriter\Components\ClassComponent;
use NBasnet\CodeWriter\Components\FunctionComponent;
use NBasnet\CodeWriter\Components\VariableComponent;
use NBasnet\CodeWriter\FileWriter;
use NBasnet\CodeWriter\ISyntaxGrammar;

/**
 * @return string
 */
function generateCodeForWritingToFile()
{
    $file_writer = FileWriter::create(ISyntaxGrammar::PHP);
    $page        = CodePage::create('App\Http\Controllers\Project', [
        'App\Http\Controllers\Project',
        'App\Http\Controllers\Project',
    ]);

    $class = ClassComponent::create('ProjectController')
        ->setExtends("Controller");

    $variable = VariableComponent::create("nischal")->setValue("Is Name");
    $constant = VariableComponent::create("TEST")->setValue("VALUE 1")->makeConstant();
    $array    = ArrayComponent::create("what_is_this", TRUE)
        ->setValue([
            "string" => "is game",
            "number" => 2,
            "bool"   => FALSE,
        ]);

    $function = FunctionComponent::create("myFunction")
        ->setParameters(['array $my_array', '$val']);

    $function->addComponentToBody($array)
        ->setAccessIdentifier(BaseComponent::ACCESS_PUBLIC)
        ->addComponentToBody($variable);

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
