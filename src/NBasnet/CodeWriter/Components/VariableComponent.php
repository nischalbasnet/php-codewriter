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
    protected $variable_name;
    protected $access_identifier;
    protected $value;
    protected $constant       = FALSE;
    protected $static         = FALSE;
    protected $unquoted_value = FALSE;
    protected $raw_value      = FALSE;

    /**
     * VariableComponent constructor.
     * @param string $variableName
     */
    protected function __construct($variableName)
    {
        $this->variable_name = $variableName;
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
     * Output the value in variable name without any formatting
     * @return $this
     */
    public function rawOutput()
    {
        $this->raw_value = TRUE;

        return $this;
    }

    /**
     * Method to handle writing component
     * @return mixed
     */
    public function writeComponent()
    {
        $variable_output = $this->raw_value ?
            ($this->value ? "$this->variable_name = $this->value" : $this->variable_name) . ";" :
            sprintf("{$this->generateVariableName()} = %s;", $this->unquoted_value ? $this->value : FileWriter::quoteValue($this->value));

        return FileWriter::addLine(
            $variable_output,
            $this->getIndent(),
            $this->getIndentSpace()
        );
    }

    /**
     * @return string
     */
    public function generateVariableName()
    {
        $name_parts = [];
        if ($this->getGrammar()->getProgram() === ISyntaxGrammar::PHP) {
            if (!empty($this->access_identifier)) $name_parts[] = $this->access_identifier;
            if ($this->static) $name_parts[] = $this->getGrammar()->getStatic();
            if ($this->constant) $name_parts[] = $this->getGrammar()->constant();

            $name_parts[] = ($this->constant) ?
                $this->variable_name :
                $this->getGrammar()->variableStartSymbol() . $this->variable_name;
        }

        //handle other languages here
        return implode(" ", $name_parts);
    }
}