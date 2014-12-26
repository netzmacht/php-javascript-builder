<?php

/**
 * @package    dev
 * @author     David Molineus <david.molineus@netzmacht.de>
 * @copyright  2014 netzmacht creative David Molineus
 * @license    LGPL 3.0
 * @filesource
 *
 */

namespace Netzmacht\Javascript\Type\Call;

use Netzmacht\Javascript\Builder;

/**
 * Class FunctionCall is used for named function calls. They have to be defined somewhere else.
 *
 * @package Netzmacht\Javascript\Type\Call
 */
class FunctionCall extends AbstractCall
{
    /**
     * Function name.
     *
     * @var string
     */
    private $name;

    /**
     * Construct.
     *
     * @param string $name       Function name.
     * @param array  $arguments  Method arguments.
     * @param null   $definition Linked definition.
     */
    public function __construct($name, array $arguments = array(), $definition = null)
    {
        parent::__construct($arguments, $definition);

        $this->name = $name;
    }

    /**
     * Get method name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * {@inheritdoc}
     */
    public function build(Builder $builder, $finish = true)
    {
        return sprintf(
            '%s(%s)%s',
            $this->getName(),
            $builder->buildArguments($this->getArguments()),
            $finish ? ';' : ''
        );
    }
}
