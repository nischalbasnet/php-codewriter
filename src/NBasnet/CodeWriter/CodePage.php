<?php
namespace NBasnet\CodeWriter;

use NBasnet\CodeWriter\Components\BlankComponent;

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
    public function appendComponent(IComponentWrite $component)
    {
        $this->components[] = $component;

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
     * Method to handle writing component
     * @return mixed
     */
    public function writeComponent()
    {
        $page_output = "";
        if ($this->getGrammar()->openingTag()) $page_output .= FileWriter::addLine($this->getGrammar()->openingTag(), $this->getIndent(), $this->getIndentSpace());

        if (!empty($this->namespace)) $page_output .= FileWriter::addLine("{$this->getGrammar()->getNameSpace()} {$this->namespace};", $this->getIndent(), $this->getIndentSpace()) . FileWriter::addBlankLine();

        foreach ($this->imports as $import) {
            $page_output .= FileWriter::addLine("{$this->getGrammar()->import()} $import;", $this->getIndent(), $this->getIndentSpace());
        }

        foreach ($this->components as $component) {
            if ($component instanceof IComponentWrite) {
                $component->setSettings($this->settings);
                $page_output .= $component->writeComponent();
            }
        }

        if ($this->getGrammar()->closingTag()) $page_output .= FileWriter::addLine($this->getGrammar()->closingTag(), $this->getIndent(), $this->getIndentSpace());

        return $page_output;
    }
}