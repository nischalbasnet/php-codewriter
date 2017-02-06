<?php
namespace NBasnet\CodeWriter\Components;

use NBasnet\CodeWriter\FileWriter;
use NBasnet\CodeWriter\IComponentWrite;

/**
 * Class GeneralComponent
 * @package NBasnet\CodeWriter\Components
 */
class GeneralComponent extends BaseComponent
{
    protected $components = [];

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
        $output = '';
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