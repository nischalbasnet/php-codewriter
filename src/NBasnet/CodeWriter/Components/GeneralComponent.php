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
    protected $components   = [];
    protected $addLineBreak = FALSE;

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
    public static function createBlankLine()
    {
        return self::create()->addLineBreak();
    }


    /**
     * @return $this
     */
    public function addLineBreak()
    {
        $this->addLineBreak = TRUE;

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
        if (!empty($this->content)) {
            $output = FileWriter::addLine($this->content, $this->indent, $this->indent_space);
        }
        else {
            $output = $this->addLineBreak ?
                FileWriter::addLine('', 0) :
                '';
        }

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