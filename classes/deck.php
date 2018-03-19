<?php
/**
 * Date: 2/23/18
 * Time: 12:13 PM
 * deck.php
 * This creates a Deck object;
 *
 * @author Kianna <kdyck@mail.greenriver.edu> and Jen <jshin13@mail.greenriver.edu>
 * @copyright 2018
 */

class Deck
{
    protected $name;
    protected $id;

    /**
     * Creates a Deck object.
     * Deck constructor.
     * @param $name string
     * @param $id int
     */
    function __construct($name, $id)
    {
        $this-> name = $name;
        $this-> id = $id;
    }

    /**
     * Gets deck id.
     * @return int id
     */
    function getDeckId()
    {
        return $this->id;
    }
}