<?php
namespace NBasnet\CodeWriter;

/**
 * Class BaseComponent
 * @package NBasnet\CodeWriter\Components
 */
abstract class BaseComponent implements IComponentWrite
{
    const ACCESS_PUBLIC = "public";
    const ACCESS_PRIVATE = "private";

    /** @var  ISyntaxGrammar $grammar */
    protected $grammar;

    protected $indent       = 1;
    protected $indent_space = 4;

    /**
     * @param ISyntaxGrammar $grammar
     * @return $this
     */
    public function setGrammar($grammar)
    {
        $this->grammar = $grammar;

        return $this;
    }

    /**
     * @param int $indent
     * @return $this
     */
    public function setIndent($indent)
    {
        $this->indent = $indent;

        return $this;
    }

    /**
     * @param int $indent_space
     * @return $this
     */
    public function setIndentSpace($indent_space)
    {
        $this->indent_space = $indent_space;

        return $this;
    }

}