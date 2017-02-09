<?php
namespace NBasnet\CodeWriter;

use NBasnet\CodeWriter\Components\GeneralComponent;

/**
 * Class CodePage
 * @package App\Services\File\Writer
 */
class CodePage extends BaseComponent
{
    protected $namespace  = "";
    protected $imports    = [];
    protected $components = [];

    /**
     * CodePage constructor.
     * @param string $namespace
     * @param array $using
     */
    protected function __construct($namespace = "", array $using = [])
    {
        $this->namespace = $namespace;
        $this->imports   = $using;
        //set the indent for the children components
        $this->indent = 0;
    }


    /**
     * @param string $namespace
     * @param array $using
     * @return static
     */
    public static function create($namespace = "", array $using = [])
    {
        return new static($namespace, $using);
    }

    /**
     * @param IComponentWrite $component
     * @return $this
     */
    public function addComponents(IComponentWrite $component)
    {
        $component->setGrammar($this->grammar);
        $this->components[] = $component;

        return $this;
    }

    /**
     * @return $this
     */
    public function addBlankLine()
    {
        $this->addComponents(GeneralComponent::createBlankLine());

        return $this;
    }

    /**
     * Method to handle writing component
     * @return mixed
     */
    public function writeComponent()
    {
        $page_output = "";
        if ($this->grammar->openingTag()) $page_output .= FileWriter::addLine($this->grammar->openingTag(), $this->indent, $this->indent_space);

        if (!empty($this->namespace)) $page_output .= FileWriter::addLine("{$this->grammar->getNameSpace()} {$this->namespace};", $this->indent, $this->indent_space) . FileWriter::addBlankLine();

        foreach ($this->imports as $import) {
            $page_output .= FileWriter::addLine("{$this->grammar->import()} $import;", $this->indent, $this->indent_space);
        }

        foreach ($this->components as $component) {
            if ($component instanceof IComponentWrite) {
                $component->setGrammar($this->grammar);
                $page_output .= $component
                    ->setIndent($this->indent)
                    ->setIndentSpace($this->indent_space)
                    ->writeComponent();
            }
        }

        if ($this->grammar->closingTag()) $page_output .= FileWriter::addLine($this->grammar->closingTag(), $this->indent, $this->indent_space);

        return $page_output;
    }
}