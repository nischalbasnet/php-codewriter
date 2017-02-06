<?php
namespace NBasnet\CodeWriter\Components;

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
    protected $constant = FALSE;
    protected $static   = FALSE;
    protected $variableAccess;
    protected $value;

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
     * Method to handle writing component
     * @return mixed
     */
    public function writeComponent()
    {
        return FileWriter::addLine(
            sprintf("{$this->generateVariableName()} = %s;", FileWriter::quoteValue($this->value)),
            $this->indent,
            $this->indent_space
        );
    }

    /**
     * @return string
     */
    public function generateVariableName()
    {
        $variable_name = "";
        if ($this->grammar->getProgram() === ISyntaxGrammar::PHP) {
            if (!empty($this->variableAccess)) $variable_name = $this->variableAccess;
            if ($this->static) $variable_name .= $this->grammar->getStatic();
            if ($this->constant) $variable_name .= " {$this->grammar->constant()} ";
            if (!$this->constant) $variable_name .= ' $';
            $variable_name .= "{$this->variableName}";
        }

        //handle other languages here

        return $variable_name;
    }
}