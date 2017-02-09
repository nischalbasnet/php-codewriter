<?php
namespace NBasnet\CodeWriter;

/**
 * Interface ICodeComponentWrite
 * @package App\Services\File\Writer
 */
interface IComponentWrite
{
    /**
     * Method to handle writing component
     * @return mixed
     */
    public function writeComponent();

    /**
     * Method to set syntax grammer for the component
     * @param ISyntaxGrammar $grammar
     * @return $this
     */
    public function setGrammar($grammar);

    /**
     * @param int $indent
     * @return $this
     */
    public function setIndent($indent);

    /**
     * @param int $indent_space
     * @return $this
     */
    public function setIndentSpace($indent_space);
}