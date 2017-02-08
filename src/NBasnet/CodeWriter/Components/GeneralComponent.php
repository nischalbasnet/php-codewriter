<?php
namespace NBasnet\CodeWriter\Components;

use NBasnet\CodeWriter\BaseComponent;
use NBasnet\CodeWriter\FileWriter;
use NBasnet\CodeWriter\IComponentWrite;

/**
 * Class GeneralComponent
 * @package NBasnet\CodeWriter\Components
 */
class GeneralComponent extends BaseComponent
{
    protected $content;
    protected $components = [];
    protected $addLine    = FALSE;

    /**
     * GeneralComponent constructor.
     * @param $content
     */
    public function __construct($content = "")
    {
        $this->content = $content;
    }

    /**
     * @param string $content
     * @return static
     */
    public static function create($content = "")
    {
        return new static($content);
    }

    /**
     * @return $this
     */
    public function addLine()
    {
        $this->addLine = TRUE;

        return $this;
    }

    /**
     * @param IComponentWrite $component
     * @return $this
     */
    public function addComponent(IComponentWrite $component)
    {
        $component->setGrammar($this->grammar);
        $this->components[] = $component;

        return $this;
    }

    /**
     * @return string
     */
    public function writeComponent()
    {
        $output = ($this->content || $this->addLine) ?
            FileWriter::addLine($this->content, $this->indent, $this->indent_space) :
            '';

        //create and add other components here
        foreach ($this->components as $component) {
            if ($component instanceof IComponentWrite) {
                $component->setGrammar($this->grammar);
                $output .= FileWriter::addLine($component->writeComponent());
            }
        }

        return $output;
    }
}