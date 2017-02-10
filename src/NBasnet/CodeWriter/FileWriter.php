<?php
namespace NBasnet\CodeWriter;

use NBasnet\CodeWriter\Components\BlankComponent;

/**
 * Class FileWriter
 * @package App\Services\File\Writer
 */
class FileWriter extends BaseComponent
{
    protected $writer_contents = [];
    protected $syntax_language;

    /**
     * FileWriter constructor
     * @param CodeWriterSettings $settings
     */
    protected function __construct(CodeWriterSettings $settings)
    {
        $this->settings = $settings;
    }

    /**
     * @param CodeWriterSettings $settings
     * @return static
     */
    public static function create(CodeWriterSettings $settings)
    {
        return new static($settings);
    }

    /**
     * @param IComponentWrite $component
     * @return $this
     */
    public function appendComponent(IComponentWrite $component)
    {
        $this->writer_contents[] = $component;

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
        $file_write_output = "";

        foreach ($this->writer_contents as $content) {
            if ($content instanceof IComponentWrite) {
                $content->setSettings($this->settings);
                $file_write_output .= $content->writeComponent();
            }
        }

        return $file_write_output;
    }

    /**
     * @param     $content
     * @param int $indent
     * @param int $indent_space
     * @return string
     */
    public static function addLine($content, $indent = 1, $indent_space = 4)
    {
        return self::addContent($content, $indent, 1, $indent_space);
    }

    /**
     * @param $content
     * @param int $indent
     * @param int $line_end
     * @param int $indent_space
     * @return string
     */
    public static function addContent($content, $indent = 1, $line_end = 1, $indent_space = 4)
    {
        $return_string = str_pad($content, strlen($content) + $indent * $indent_space, " ", STR_PAD_LEFT);
        $return_string = str_pad($return_string, strlen($return_string) + ($line_end * 2), "\r\n");

        return $return_string;
    }

    /**
     * @param int $blank_lines
     * @return string
     */
    public static function addBlankLine($blank_lines = 1)
    {
        return str_repeat("\r\n", $blank_lines);
    }

    /**
     * @param $value
     * @return string
     */
    public static function quoteValue($value)
    {
        if (is_string($value)) $value = "'$value'";
        if (is_bool($value)) {
            $value = $value ? "true" : "false";
        }

        return $value;
    }
}