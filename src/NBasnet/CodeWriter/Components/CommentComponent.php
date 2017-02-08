<?php
namespace NBasnet\CodeWriter\Components;

use NBasnet\CodeWriter\BaseComponent;
use NBasnet\CodeWriter\FileWriter;
use NBasnet\CodeWriter\ISyntaxGrammar;

/**
 * Class CommentComponent
 * @package NBasnet\CodeWriter\Components
 */
class CommentComponent extends BaseComponent
{
    const TYPE_SINGLE_LINE = "single_line_comment";
    const TYPE_MULTI_LINE = "multi_line_comment";

    /** @var  ISyntaxGrammar $grammar */
    protected $grammar;
    private   $start_tag;
    private   $comment_type;
    private   $comment;

    /**
     * CommentComponent constructor.
     * @param $comment_type
     */
    protected function __construct($comment_type = self::TYPE_SINGLE_LINE)
    {
        $this->comment_type = $comment_type;

        $this->setDefaultCommentStartTag();
    }

    /**
     * @param $comment_type
     * @return static
     */
    public static function create($comment_type = self::TYPE_SINGLE_LINE)
    {
        return new static($comment_type);
    }

    /**
     * @return void
     */
    private function setDefaultCommentStartTag()
    {
        switch ($this->comment_type) {
            case self::TYPE_SINGLE_LINE:
                $this->start_tag = "//";
                break;
            case self::TYPE_MULTI_LINE:
                $this->start_tag = "/**";
                break;
        }
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
     * @param mixed $start_tag
     * @return $this
     */
    public function setStartTag($start_tag)
    {
        $this->start_tag = $start_tag;

        return $this;
    }

    /**
     * @param string|array $comment
     * @return $this
     */
    public function setComment($comment)
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * @param array $comments
     * @return $this
     */
    public function setMultiLineComment(array $comments)
    {
        $this->comment_type = self::TYPE_MULTI_LINE;
        $this->comment      = $comments;

        return $this;
    }

    /**
     * @return string
     */
    public function writeComponent()
    {
        $output_component = "";
        switch ($this->comment_type) {
            case self::TYPE_SINGLE_LINE:
                $output_component = $this->writeSingleLineComment();
                break;
            case self::TYPE_MULTI_LINE:
                $output_component = $this->writeMultiLineComponent();
                break;
        }

        return $output_component;
    }

    /**
     * @return string
     */
    private function writeSingleLineComment()
    {
        //write the doc string
        return FileWriter::addLine("//{$this->comment}", $this->indent, $this->indent_space);
    }

    /**
     * @return string
     */
    private function writeMultiLineComponent()
    {
        $comment_output = FileWriter::addLine($this->start_tag, $this->indent, $this->indent_space);

        if (is_array($this->comment)) {
            foreach ($this->comment as $cmt) {
                $comment_output .= FileWriter::addLine(" * {$cmt}", $this->indent, $this->indent_space);
            }

        }
        else {
            $comment_output .= FileWriter::addLine(" * {$this->comment}", $this->indent, $this->indent_space);
        }

        $comment_output .= FileWriter::addLine(" */", $this->indent, $this->indent_space);

        return $comment_output;
    }


}