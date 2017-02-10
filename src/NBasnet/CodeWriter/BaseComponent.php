<?php
namespace NBasnet\CodeWriter;

/**
 * Class BaseComponent
 * @package NBasnet\CodeWriter\Components
 */
abstract class BaseComponent implements IComponentWrite
{
    const ACCESS_PUBLIC = "public";
    const ACCESS_PRIVATE = "private";

    /** @var  CodeWriterSettings $settings */
    protected $settings;

    /**
     * Set the settings for the component
     * @param CodeWriterSettings $code_writer_settings
     * @param int $component_indent
     * @return $this
     */
    public function setSettings(CodeWriterSettings $code_writer_settings, $component_indent = -1)
    {
        $this->settings = $code_writer_settings->replicate($component_indent);

        return $this;
    }

    /**
     * @return ISyntaxGrammar
     */
    public function getGrammar()
    {
        return $this->settings->getSyntaxGrammar();
    }

    /**
     * @return int
     */
    public function getIndent()
    {
        return $this->settings->getIndent();
    }

    /**
     * @return int
     */
    public function getIndentSpace()
    {
        return $this->settings->getIndentSpace();
    }

    /**
     * @return int
     */
    public function getBlankIndent()
    {
        return $this->settings->getBlankIndent();
    }

    /**
     * For debug purpose
     * @param $var
     */
    public static function dd($var)
    {
        die(var_dump($var));
    }
}