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

class GetReferenceFailed extends \Exception
{
    /**
     * @var mixed
     */
    private $object;

    public function __construct($object, $message = "", $code = 0, Exception $previous = null)
    {
        $this->object = $object;

        parent::__construct($message, $code, $previous);
    }

    /**
     * @return mixed
     */
    public function getObject()
    {
        return $this->object;
    }
}
