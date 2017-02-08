<?php
namespace NBasnet\CodeWriter\Components;

use NBasnet\CodeWriter\FileWriter;
use NBasnet\CodeWriter\IComponentWrite;

/**
 * Class FunctionComponent
 * @package NBasnet\CodeWriter\Components
 */
class FunctionComponent extends BaseComponent
{
    protected $function_name;
    protected $parameters           = [];
    protected $return_type          = '';
    protected $exceptions_thrown    = [];
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
     * @param array $parameters
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
     * @param IComponentWrite $component
     * @return $this
     */
    public function addComponentToBody(IComponentWrite $component)
    {
        $component->setGrammar($this->grammar);
        $this->function_body[] = $component;

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
     * @return $this
     */
    public function addBlankLine()
    {
        $this->addComponentToBody(GeneralComponent::create()->addLine());

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
                $doc_string[] = "@param $parameter";
            }

            if (!empty($this->return_type)) {
                $doc_string[] = "@return {$this->return_type}";
            }


            foreach ($this->exceptions_thrown as $exception) {
                $doc_string[] = "@throws $exception";
            }

            $function_output .= CommentComponent::create(CommentComponent::TYPE_MULTI_LINE)
                ->setComment($doc_string)
                ->setGrammar($this->grammar)
                ->setIndent($this->indent)
                ->setIndentSpace($this->indent_space)
                ->writeComponent();
        }

        $function_name = "{$this->access_identifier} {$this->grammar->getFunction()} $this->function_name(";

        foreach ($this->parameters as $parameter) {
            $function_name .= "$parameter, ";
        }
        $function_name = rtrim($function_name, ", ");
        $function_name .= ")";

        $function_output .= FileWriter::addLine($function_name, $this->indent, $this->indent_space);
        $function_output .= FileWriter::addLine($this->grammar->regionStartTag(), $this->indent, $this->indent_space);

        //create and add other components here
        foreach ($this->function_body as $component) {
            if ($component instanceof IComponentWrite) {
                $component->setGrammar($this->grammar);
                $function_output .= $component->setIndent($this->indent + 1)
                    ->setIndentSpace($this->indent_space)
                    ->writeComponent();
            }
        }

        $function_output .= FileWriter::addLine($this->grammar->regionEndTag(), $this->indent, $this->indent_space);

        return $function_output;
    }
}