<?php
namespace NBasnet\CodeWriter\Components;


use NBasnet\CodeWriter\FileWriter;

class BlankComponent extends BaseComponent
{
    protected $blank_line_no = 1;

    /**
     * BlankComponent constructor.
     * @param int $blank_line_no
     */
    protected function __construct($blank_line_no = 1)
    {
        $this->blank_line_no = $blank_line_no;
    }

    /**
     * @param int $blank_line_no
     * @return static
     */
    public static function create($blank_line_no = 1)
    {
        return new static($blank_line_no);
    }

    /**
     * @return string
     */
    public function writeComponent()
    {
        return FileWriter::addBlankLine($this->blank_line_no);
    }
}