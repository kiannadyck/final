<?php
/**
 * Date: 2/23/18
 * Time: 12:21 PM
 * notes.php
 *
 * Instantiates a Notes deck. Extends from Deck.
 * Holds an array of strings.
 *
 * @author Kianna <kdyck@mail.greenriver.edu> and Jen <jshin13@mail.greenriver.edu>
 * @copyright 2018
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
     * Gets array of strings.
     * @return array strings
     */

    public function getNotes()
    {
        return $this->_notes;
    }

    /**
     * Sets array of strings.
     * @param array $notes strings
     */
    public function setNotes($notes)
    {
        $this->_notes = $notes;
    }
}