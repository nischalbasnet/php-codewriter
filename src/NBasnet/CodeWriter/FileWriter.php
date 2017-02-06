<?php
namespace NBasnet\CodeWriter;

/**
 * Class FileWriter
 * @package App\Services\File\Writer
 */
class FileWriter implements IComponentWrite
{
    protected $writerContents = [];
    protected $syntaxLanguage;
    protected $syntaxGrammar;

    public function setIndent($indent)
    {
        // TODO: Implement setIndent() method.
    }

    public function setIndentSpace($indent_space)
    {
        // TODO: Implement setIndentSpace() method.
    }


    /**
     * FileWriter constructor.
     * @param $syntaxLanguage
     */
    protected function __construct($syntaxLanguage)
    {
        $this->syntaxLanguage = $syntaxLanguage;

        if ($this->syntaxLanguage === ISyntaxGrammar::PHP) {
            $this->syntaxGrammar = new PHPSyntaxGrammar();
        }
    }

    /**
     * @param $syntaxLanguage
     * @return static
     */
    public static function create($syntaxLanguage)
    {
        return new static($syntaxLanguage);
    }

    /**
     * Add component to the writer
     * @param IComponentWrite $component
     */
    public function addCodeComponent(IComponentWrite $component)
    {
        $component->setGrammar($this->syntaxGrammar);
        $this->writerContents[] = $component;
    }

    /**
     * @param ISyntaxGrammar $grammar
     */
    public function setGrammar($grammar)
    {
        $this->syntaxGrammar = $grammar;
    }

    /**
     * @return string
     */
    public function writeComponent()
    {
        $file_write_output = "";

        foreach ($this->writerContents as $content) {
            if ($content instanceof IComponentWrite) {
                $content->setGrammar($this->syntaxGrammar);
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