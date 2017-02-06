<?php
namespace NBasnet\CodeWriter\Components;

use NBasnet\CodeWriter\FileWriter;

/**
 * Class ArrayComponent
 * @package App\Services\File\Writer
 */
class ArrayComponent extends VariableComponent
{
    protected $associativeArray = FALSE;

    /**
     * VariableComponent constructor.
     * @param string $variableName
     * @param bool $associativeArray
     */
    protected function __construct($variableName, $associativeArray = FALSE)
    {
        parent::__construct($variableName);
        $this->associativeArray = $associativeArray;
    }

    /**
     * @param string $variableName
     * @param bool $associativeArray
     * @return static
     */
    public static function create($variableName, $associativeArray = FALSE)
    {
        return new static($variableName, $associativeArray);
    }

    /**
     * @param array $value
     * @return $this
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Handle writing array component
     * @return string
     */
    public function writeComponent()
    {
        $variable_string = FileWriter::addLine(
            "{$this->generateVariableName()} = {$this->grammar->arrayStartTag()}",
            $this->indent,
            $this->indent_space
        );

        foreach ($this->value as $key => $value) {
            $array_line = $this->associativeArray ?
                sprintf("%s => %s,", FileWriter::quoteValue($key), FileWriter::quoteValue($value)) :
                sprintf("%s,", FileWriter::quoteValue($value));

            $variable_string .= FileWriter::addLine($array_line,
                $this->indent + 1,
                $this->indent_space
            );
        }

        $variable_string .= FileWriter::addLine("{$this->grammar->arrayEndTag()};",
            $this->indent,
            $this->indent_space
        );

        return $variable_string;
    }
}