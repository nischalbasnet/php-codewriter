<?php
namespace NBasnet\CodeWriter\Components;

use NBasnet\CodeWriter\FileWriter;
use NBasnet\CodeWriter\IComponentWrite;
use NBasnet\CodeWriter\ISyntaxGrammar;

/**
 * Class ClassComponent
 * @package App\Services\File\Writer
 */
class ClassComponent extends BaseComponent
{
    /** @var  ISyntaxGrammar $grammar */
    protected $grammar;
    protected $abstractClass;
    protected $className;
    protected $extends    = null;
    protected $interfaces = [];
    protected $traits     = [];
    protected $components = [];

    /**
     * ClassComponent constructor.
     * @param string $className
     * @param  bool $abstractClass
     */
    private function __construct($className, $abstractClass = FALSE)
    {
        $this->abstractClass = $abstractClass;
        $this->className     = $className;
    }

    /**
     * @param string $className
     * @param  bool $abstractClass
     * @return static
     */
    public static function create($className, $abstractClass = FALSE)
    {
        return new static($className, $abstractClass);
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
        $this->addComponents(GeneralComponent::create()->addLine());

        return $this;
    }

    /**
     * @return string
     */
    public function writeComponent()
    {
        //write the doc string
        $output_class = CommentComponent::create(CommentComponent::TYPE_MULTI_LINE)
            ->setComment("Class {$this->className}")
            ->setIndent($this->indent)
            ->setIndentSpace($this->indent_space)
            ->writeComponent();

        $class_name_output = "";
        if ($this->abstractClass) {
            $class_name_output .= $this->grammar->getAbstract();
        }

        $class_name_output .= " {$this->grammar->getClass()} {$this->className}";

        if (!empty($this->extends)) $class_name_output .= " {$this->grammar->getExtends()} {$this->extends}";

        //check the current code to change it accordingly
        if (!empty($this->interfaces)) {
            $class_name_output .= " {$this->grammar->implement()}";
            foreach ($this->interfaces as $implement) {
                $class_name_output .= " $implement,";
            }
        }

        $output_class .= FileWriter::addLine(trim($class_name_output), $this->indent, $this->indent_space);
        $output_class .= FileWriter::addLine($this->grammar->regionStartTag(), $this->indent, $this->indent_space);

        if ($this->grammar->getProgram() === ISyntaxGrammar::PHP) {
            foreach ($this->traits as $trait) {
                $output_class .= FileWriter::addLine("{$this->grammar->traitUse()} $trait;", $this->indent + 1, $this->indent_space);
            }
        }

        //create and add other components here
        foreach ($this->components as $component) {
            if ($component instanceof IComponentWrite) {
                $component->setGrammar($this->grammar);
                $output_class .= $component->setIndent($this->indent + 1)
                    ->setIndentSpace($this->indent_space)
                    ->writeComponent();
            }
        }

        $output_class .= FileWriter::addLine($this->grammar->regionEndTag(), $this->indent, $this->indent_space);

        return $output_class;
    }
}