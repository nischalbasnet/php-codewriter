<?php
namespace NBasnet\CodeWriter\Components;

use NBasnet\CodeWriter\BaseComponent;
use NBasnet\CodeWriter\FileWriter;
use NBasnet\CodeWriter\ISyntaxGrammar;

/**
 * Class VariableComponent
 * @package App\Services\File\Writer
 */
class VariableComponent extends BaseComponent
{
    /** @var  ISyntaxGrammar $grammar */
    protected $grammar;
    protected $variableName;
    protected $access_identifier;
    protected $value;
    protected $constant       = FALSE;
    protected $static         = FALSE;
    protected $unquoted_value = FALSE;

    /**
     * VariableComponent constructor.
     * @param string $variableName
     */
    protected function __construct($variableName)
    {
        $this->variableName = $variableName;
    }

    /**
     * @param string $variableName
     * @return static
     */
    public static function create($variableName)
    {
        return new static($variableName);
    }

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
     * set constant to true
     * @return $this
     */
    public function makeConstant()
    {
        $this->constant = TRUE;

        return $this;
    }

    /**
     * sets static to true
     * @return $this
     */
    public function makeStatic()
    {
        $this->static = TRUE;

        return $this;
    }

    /**
     * @param mixed $value
     * @return $this
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * @param mixed $variableAccess
     * @return $this
     */
    public function setAccessIdentifier($variableAccess)
    {
        $this->access_identifier = $variableAccess;

        return $this;
    }

    /**
     * dont quote the variable value
     * @return $this
     */
    public function unQuoteValue()
    {
        $this->unquoted_value = TRUE;

        return $this;
    }

    /**
     * Method to handle writing component
     * @return mixed
     */
    public function writeComponent()
    {
        return FileWriter::addLine(
            sprintf("{$this->generateVariableName()} = %s;", $this->unquoted_value ? $this->value : FileWriter::quoteValue($this->value)),
            $this->indent,
            $this->indent_space
        );
    }

    /**
     * @return string
     */
    public function generateVariableName()
    {
        $name_parts = [];
        if ($this->grammar->getProgram() === ISyntaxGrammar::PHP) {
            if (!empty($this->access_identifier)) $name_parts[] = $this->access_identifier;
            if ($this->static) $name_parts[] = $this->grammar->getStatic();
            if ($this->constant) $name_parts[] = $this->grammar->constant();
            $name_parts[] = ($this->constant) ? $this->variableName : '$' . $this->variableName;
        }

        //handle other languages here
        return implode(" ", $name_parts);
    }
}