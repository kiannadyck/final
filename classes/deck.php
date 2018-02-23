<?php
/**
 * Created by PhpStorm.
 * User: Kianna and Jen
 * Date: 2/23/18
 * Time: 12:13 PM
 *
 * This creates a Deck object;
 */

class Deck
{
    protected $name;

    /**
     * Creates a Deck object.
     * Deck constructor.
     * @param $name string
     */
    function __construct($name)
    {
        $this-> name = $name;
    }
}