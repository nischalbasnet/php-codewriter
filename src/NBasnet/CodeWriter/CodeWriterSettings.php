<?php
namespace NBasnet\CodeWriter;

/**
 * Class CodeWriterSettings
 * @package NBasnet\CodeWriter
 */
class CodeWriterSettings
{
    /** @var  ISyntaxGrammar $syntax_grammar */
    private $syntax_grammar;
    private $syntax_language;
    private $indent              = 1;
    private $indent_space        = 4;
    private $blank_indent        = 0;
    private $type_hint_primitive = FALSE;

    /**
     * CodeWriterSettings constructor.
     * @param string $syntax_language
     * @param int $indent
     * @param int $indent_space
     */
    public function __construct($syntax_language, $indent = 1, $indent_space = 4)
    {
        $this->syntax_language = $syntax_language;
        $this->indent          = $indent;
        $this->indent_space    = $indent_space;

        if ($this->syntax_language === ISyntaxGrammar::PHP) {
            $this->syntax_grammar = new PHPSyntaxGrammar();
        }
    }

    /**
     * @param string $syntax_language
     * @param int $indent
     * @param int $indent_space
     * @return static
     */
    public static function create($syntax_language, $indent = 1, $indent_space = 4)
    {
        return new static($syntax_language, $indent, $indent_space);
    }

    /**
     * @return int
     */
    public function getIndent()
    {
        return $this->indent;
    }

    /**
     * @param int $indent
     * @return $this
     */
    private function setIndent($indent)
    {
        $this->indent = $indent;

        return $this;
    }

    /**
     * @return int
     */
    public function getIndentSpace()
    {
        return $this->indent_space;
    }

    /**
     * @return int
     */
    public function getBlankIndent()
    {
        return $this->blank_indent;
    }

    /**
     * @param int $blank_indent
     * @return $this
     */
    public function setBlankIndent($blank_indent)
    {
        $this->blank_indent = $blank_indent;

        return $this;
    }

    /**
     * @return ISyntaxGrammar
     */
    public function getSyntaxGrammar()
    {
        return $this->syntax_grammar;
    }

    /**
     * @param ISyntaxGrammar $syntax_grammar
     * @return $this
     */
    public function setSyntaxGrammar($syntax_grammar)
    {
        $this->syntax_grammar = $syntax_grammar;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getSyntaxLanguage()
    {
        return $this->syntax_language;
    }

    /**
     * @param string $syntax_language
     * @return $this
     */
    public function setSyntaxLanguage($syntax_language)
    {
        $this->syntax_language = $syntax_language;

        return $this;
    }

    /**
     * @return bool
     */
    public function typeHintPrimitive()
    {
        return $this->type_hint_primitive;
    }

    /**
     * @param bool $type_hint_primitive
     * @return $this
     */
    public function setTypeHintPrimitive($type_hint_primitive)
    {
        $this->type_hint_primitive = $type_hint_primitive;

        return $this;
    }


    /**
     * @param int $indent
     * @return CodeWriterSettings
     */
    public function replicate($indent = -1)
    {
        $indent = ($indent < 0) ? $this->indent : $indent;

        //clone the setting and set default values
        $new_settings = clone $this;
        $new_settings->setIndent($indent);

        return $new_settings;
    }
}