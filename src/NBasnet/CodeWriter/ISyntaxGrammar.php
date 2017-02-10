<?php
namespace NBasnet\CodeWriter;

/**
 * Interface ICodeGrammar
 * @package App\Services\File\Writer
 */
interface ISyntaxGrammar
{
    const PHP = "PHP";
    const SCALA = "SCALA";

    public function getProgram();

    public function openingTag();

    public function closingTag();

    public function getNameSpace();

    public function import();

    public function traitUse();

    public function getAbstract();

    public function getClass();

    public function getExtends();

    public function extendWith();

    public function implement();

    public function regionStartTag();

    public function regionEndTag();

    public function constant();

    public function getStatic();

    public function arrayStartTag();

    public function arrayEndTag();

    public function variableStartSymbol();

    public function getFunction();
}