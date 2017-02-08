<?php
namespace NBasnet\CodeWriter;

/**
 * Class CodePage
 * @package App\Services\File\Writer
 */
class CodePage implements IComponentWrite
{
    /** @var  ISyntaxGrammar $grammar */
    protected $grammar;
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

    public function setIndent($indent)
    {
        // TODO: Implement setIndent() method.
    }

    public function setIndentSpace($indent_space)
    {
        // TODO: Implement setIndentSpace() method.
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
     * @param $grammar
     * @return $this
     */
    public function setGrammar($grammar)
    {
        $this->grammar = $grammar;

        return $this;
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
     * Method to handle writing component
     * @return mixed
     */
    public function writeComponent()
    {
        $page_output = "";
        if ($this->grammar->openingTag()) $page_output .= FileWriter::addLine($this->grammar->openingTag(), 0);

        if (!empty($this->namespace)) $page_output .= FileWriter::addLine("{$this->grammar->getNameSpace()} {$this->namespace};", 0) . FileWriter::addBlankLine();

        foreach ($this->imports as $import) {
            $page_output .= FileWriter::addLine("{$this->grammar->import()} $import;", 0);
        }

        foreach ($this->components as $component) {
            if ($component instanceof IComponentWrite) {
                $component->setGrammar($this->grammar);
                $page_output .= $component->setIndent(0)->writeComponent();
            }
        }

        if ($this->grammar->closingTag()) $page_output .= FileWriter::addLine($this->grammar->closingTag());

        return $page_output;
    }
}