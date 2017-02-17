<?php
namespace NBasnet\CodeWriter\Components;

use NBasnet\CodeWriter\BaseComponent;
use NBasnet\CodeWriter\FileWriter;
use NBasnet\CodeWriter\IComponentWrite;
use NBasnet\CodeWriter\ISyntaxGrammar;

/**
 * Class ClassComponent
 * @package App\Services\File\Writer
 */
class ClassComponent extends BaseComponent
{
    protected $namespace  = '';
    protected $abstract_class;
    protected $class_name;
    protected $extends    = null;
    protected $interfaces = [];
    protected $traits     = [];
    protected $components = [];

    /**
     * ClassComponent constructor.
     * @param string $class_name
     * @param  bool $abstract_class
     */
    private function __construct($class_name, $abstract_class = FALSE)
    {
        $this->abstract_class = $abstract_class;
        $this->class_name     = $class_name;
    }

    /**
     * @param string $class_name
     * @param  bool $abstract_class
     * @return static
     */
    public static function create($class_name, $abstract_class = FALSE)
    {
        return new static($class_name, $abstract_class);
    }

    /**
     * @param $extends
     * @return $this
     */
    public function setExtends($extends)
    {
        $this->extends = $extends;

        return $this;
    }

    /**
     * @param array $interfaces
     * @return $this
     */
    public function setInterfaces(array $interfaces)
    {
        $this->interfaces = $interfaces;

        return $this;
    }

    /**
     * @param array $traits
     * @return $this
     */
    public function setTraits(array $traits)
    {
        $this->traits = $traits;

        return $this;
    }

    /**
     * @param string $namespace
     * @return $this
     */
    public function setNamespace($namespace)
    {
        $this->namespace = $namespace;

        return $this;
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
     * @return string
     */
    public function writeComponent()
    {
        $class_doc_string[] = "Class {$this->class_name}";
        if (!empty($this->namespace)) {
            $class_doc_string[] = "@package {$this->namespace}";
        }
        //write the doc string
        $output_class = CommentComponent::create()
            ->setMultiLineComment($class_doc_string)
            ->setSettings($this->settings)
            ->writeComponent();

        $class_name_output = "";
        if ($this->abstract_class) {
            $class_name_output .= $this->getGrammar()->getAbstract();
        }

        $class_name_output .= " {$this->getGrammar()->getClass()} {$this->class_name}";

        if (!empty($this->extends)) $class_name_output .= " {$this->getGrammar()->getExtends()} {$this->extends}";

        //check the current code to change it accordingly
        if (!empty($this->interfaces)) {
            $class_name_output .= " {$this->getGrammar()->implement()}";
            foreach ($this->interfaces as $implement) {
                $class_name_output .= " $implement,";
            }
        }

        $output_class .= FileWriter::addLine(trim($class_name_output), $this->getIndent(), $this->getIndentSpace());
        $output_class .= FileWriter::addLine($this->getGrammar()->regionStartTag(), $this->getIndent(), $this->getIndentSpace());

        if ($this->getGrammar()->getProgram() === ISyntaxGrammar::PHP) {
            foreach ($this->traits as $trait) {
                $output_class .= FileWriter::addLine("{$this->getGrammar()->traitUse()} $trait;", $this->getIndent() + 1, $this->getIndentSpace());
            }
        }

        //create and add other components here
        foreach ($this->components as $component) {
            if ($component instanceof IComponentWrite) {
                $component->setSettings($this->settings, $this->getIndent() + 1);
                $output_class .= $component->writeComponent();
            }
        }

        $output_class .= FileWriter::addLine($this->getGrammar()->regionEndTag(), $this->getIndent(), $this->getIndentSpace());

        return $output_class;
    }
}