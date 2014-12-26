<?php

/**
 * @package    dev
 * @author     David Molineus <david.molineus@netzmacht.de>
 * @copyright  2014 netzmacht creative David Molineus
 * @license    LGPL 3.0
 * @filesource
 *
 */

namespace Netzmacht\Javascript\Exception;

use Exception;

/**
 * Class GetReferenceFailed is thrown if no reference could be created.
 *
 * @package Netzmacht\Javascript\Exception
 */
class GetReferenceFailed extends \Exception
{
    /**
     * The object.
     *
     * @var mixed
     */
    private $object;

    /**
     * Construct.
     *
     * @param string    $object   The object.
     * @param int       $code     The error code.
     * @param Exception $previous The previous exception.
     */
    public function __construct($object, $code = 0, Exception $previous = null)
    {
        $this->object = $object;

        parent::__construct('Could not create reference for object.', $code, $previous);
    }

    /**
     * Get the object.
     *
     * @return mixed
     */
    public function getObject()
    {
        return $this->object;
    }
}
