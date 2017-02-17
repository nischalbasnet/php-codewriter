<?php
namespace NBasnet\CodeWriter\Components;

use NBasnet\CodeWriter\BaseComponent;
use NBasnet\CodeWriter\FileWriter;
use NBasnet\CodeWriter\IComponentWrite;

/**
 * Class FunctionComponent
 * @package NBasnet\CodeWriter\Components
 */
class FunctionComponent extends BaseComponent
{
    protected $function_name;
    /** @var VariableComponent[] $parameters */
    protected $parameters           = [];
    protected $exceptions_thrown    = [];
    protected $return_type          = '';
    protected $access_identifier    = '';
    protected $function_description = '';

    /** @var array|IComponentWrite[] $function_body */
    protected $function_body = [];

    /**
     * FunctionComponent constructor.
     * @param $function_name
     */
    protected function __construct($function_name)
    {
        $this->function_name = $function_name;
    }

    /**
     * @param $function_name
     * @return static
     */
    public static function create($function_name)
    {
        return new static($function_name);
    }

    /**
     * @param VariableComponent[] $parameters
     * @return $this
     */
    public function setParameters(array $parameters)
    {
        $this->parameters = $parameters;

        return $this;
    }

    /**
     * @param string $return_type
     * @return $this
     */
    public function setReturnType($return_type)
    {
        $this->return_type = $return_type;

        return $this;
    }

    /**
     * @param array $exceptions_thrown
     * @return $this
     */
    public function setExceptionsThrown(array $exceptions_thrown)
    {
        $this->exceptions_thrown = $exceptions_thrown;

        return $this;
    }

    /**
     * @param string $access_identifier
     * @return $this
     */
    public function setAccessIdentifier($access_identifier)
    {
        $this->access_identifier = $access_identifier;

        return $this;
    }

    /**
     * @param string $function_description
     * @return $this
     */
    public function setFunctionDescription($function_description)
    {
        $this->function_description = $function_description;

        return $this;
    }

    /**
     * @param IComponentWrite $component
     * @return $this
     */
    public function appendComponent(IComponentWrite $component)
    {
        $this->function_body[] = $component;

        return $this;
    }

    /**
     * @param int $blank_lines
     * @return $this
     */
    public function appendBlankLine($blank_lines = 1)
    {
        $this->appendComponent(BlankComponent::create($blank_lines));

        return $this;
    }

    /**
     * write the function component
     */
    public function writeComponent()
    {
        $function_output = '';

        if (!empty($this->function_description) || !empty($this->parameters) || !empty($this->return_type) || !empty($this->exceptions_thrown)) {
            $doc_string = [];

            if (!empty($this->function_description)) {
                $doc_string[] = $this->function_description;
            }

            foreach ($this->parameters as $parameter) {
                $parameter->setSettings($this->settings);
                $doc_string[] = "@param {$parameter->getNameWithType($force_type = TRUE)}";
            }

            if (!empty($this->return_type)) {
                $doc_string[] = "@return {$this->return_type}";
            }

            foreach ($this->exceptions_thrown as $exception) {
                $doc_string[] = "@throws $exception";
            }

            $function_output .= CommentComponent::create(CommentComponent::TYPE_MULTI_LINE)
                ->setComment($doc_string)
                ->setSettings($this->settings)
                ->writeComponent();
        }

        $function_name = "{$this->access_identifier} {$this->getGrammar()->getFunction()} $this->function_name(";

        $last_parameter_key = count($this->parameters) - 1;
        foreach ($this->parameters as $key => $parameter) {
            $function_name .= $parameter->getNameWithType();
            $function_name .= $parameter->hasValue() ? " = {$parameter->getValue()}" : "";
            $function_name .= ($last_parameter_key !== $key) ? ", " : "";
        }

        $function_name .= ")";

        $function_output .= FileWriter::addLine($function_name, $this->getIndent(), $this->getIndentSpace());
        $function_output .= FileWriter::addLine($this->getGrammar()->regionStartTag(), $this->getIndent(), $this->getIndentSpace());

        //create and add other components here
        foreach ($this->function_body as $component) {
            if ($component instanceof IComponentWrite) {
                $component->setSettings($this->settings, $this->getIndent() + 1);
                $function_output .= $component->writeComponent();
            }
        }

        $function_output .= FileWriter::addLine($this->getGrammar()->regionEndTag(), $this->getIndent(), $this->getIndentSpace());

        return $function_output;
    }
}