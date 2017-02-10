<?php
namespace NBasnet\CodeWriter;

/**
 * Interface ICodeComponentWrite
 * @package App\Services\File\Writer
 */
interface IComponentWrite
{
    /**
     * Method to handle writing component
     * @return mixed
     */
    public function writeComponent();

    /**
     * @param CodeWriterSettings $code_writer_settings
     * @param int $component_indent
     * @return mixed
     */
    public function setSettings(CodeWriterSettings $code_writer_settings, $component_indent = -1);

}