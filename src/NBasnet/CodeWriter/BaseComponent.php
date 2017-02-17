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

    const PRIMITIVE_TYPES = [
        'int',
        'integer',
        'string',
        'bool',
        'boolean',
        'float',
        'decimal'
    ];

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
        $this->isSettingsSet();

        return $this->settings->getSyntaxGrammar();
    }

    /**
     * @return int
     */
    public function getIndent()
    {
        $this->isSettingsSet();

        return $this->settings->getIndent();
    }

    /**
     * @return int
     */
    public function getIndentSpace()
    {
        $this->isSettingsSet();

        return $this->settings->getIndentSpace();
    }

    /**
     * @return int
     */
    public function getBlankIndent()
    {
        $this->isSettingsSet();

        return $this->settings->getBlankIndent();
    }

    /**
     * @param bool $throw_exception
     * @return bool
     * @throws SettingsNotSet
     */
    public function isSettingsSet($throw_exception = TRUE)
    {
        if (!($this->settings instanceof CodeWriterSettings)) {
            if ($throw_exception) {
                throw new SettingsNotSet(sprintf("settings property not set in %s, use setSettings() method to set", static::class));
            }

            return FALSE;
        }

        return TRUE;
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