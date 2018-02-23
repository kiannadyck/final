<?php
/**
 * Created by PhpStorm.
 * User: Kianna and Jen
 * Date: 2/23/18
 * Time: 12:21 PM
 */

class Notes extends Deck
{
    private $_notes;

    /**
     * Notes constructor.
     * @param $name string name of deck
     * @param $array array of strings
     */

    function __construct($name, $array)
    {
        parent::__construct($name);
        $this->_notes = $array;
    }

    /**
     * @return array strings
     */

    public function getNotes()
    {
        return $this->_notes;
    }

    /**
     * @param array $notes strings
     */
    public function setNotes($notes)
    {
        $this->_notes = $notes;
    }
}