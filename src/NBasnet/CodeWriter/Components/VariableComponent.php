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
    protected $type           = '';
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
     * @param string $type
     * @return $this
     */
    public function setValue($value, $type = '')
    {
        $this->value = $value;

        if (!empty($type)) {
            $this->type = $type;
        }

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
     * @return mixed
     */
    public function getValue()
    {
        return $this->unquoted_value ? $this->value : FileWriter::quoteValue($this->value);
    }

    /**
     * Check if value is present
     * @return bool
     */
    public function hasValue()
    {
        return !empty($this->value);
    }

    /**
     * @return string
     */
    public function getVariableName()
    {
        return ($this->raw_value || $this->constant) ?
            $this->variable_name :
            $this->getGrammar()->variableStartSymbol() . $this->variable_name;
    }

    /**
     * @param bool $force_type_return
     * @return string
     */
    public function getNameWithType($force_type_return = FALSE)
    {
        if (($this->type && (
                    (in_array($this->type, static::PRIMITIVE_TYPES) && $this->settings->typeHintPrimitive()) || !in_array($this->type, static::PRIMITIVE_TYPES))
            ) ||
            ($this->type && $force_type_return)
        ) {
            $name[] = $this->type;
        }
        $name[] = $this->getVariableName();

        return (count($name) > 1) ? implode(" ", $name) : $name[0];
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

            $name_parts[] = $this->getVariableName();
        }

        //handle other languages here
        return implode(" ", $name_parts);
    }
}