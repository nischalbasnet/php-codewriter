<?php
namespace NBasnet\CodeWriter;

/**
 * Class PHPSyntaxGrammar
 * @package App\Services\File\Writer
 */
class PHPSyntaxGrammar implements ISyntaxGrammar
{

    public function getProgram()
    {
        return ISyntaxGrammar::PHP;
    }

    public function openingTag()
    {
        return "<?php";
    }

    public function closingTag()
    {
        return "";
    }

    public function getNameSpace()
    {
        return "namespace";
    }

    public function import()
    {
        return "use";
    }

    public function traitUse()
    {
        return "use";
    }

    public function getAbstract()
    {
        return "abstract";
    }

    public function getClass()
    {
        return "class";
    }

    public function getExtends()
    {
        return "extends";
    }

    public function extendWith()
    {
        return "with";
    }

    public function implement()
    {
        return "implements";
    }

    public function regionStartTag()
    {
        return "{";
    }

    public function regionEndTag()
    {
        return "}";
    }

    public function constant()
    {
        return "const";
    }

    public function getStatic()
    {
        return "static";
    }

    public function arrayStartTag()
    {
        return "[";
    }

    public function arrayEndTag()
    {
        return "]";
    }

    public function getFunction()
    {
        return "function";
    }
}