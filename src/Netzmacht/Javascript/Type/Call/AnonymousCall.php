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

class AnonymousCall extends AbstractCall
{
    /**
     * @var array
     */
    private $lines = array();

    public function addLine($line)
    {
        $this->lines[] = $line;
    }

    public function addLines($lines)
    {
        if (!is_array($lines)) {
            $lines = explode("\n", $lines);
        }

        foreach ($lines as $line) {
            $this->addLine($line);
        }

        return $this;
    }

    public function getLines()
    {
        return $this->lines;
    }

    /**
     * {@inheritdoc}
     */
    public function build(Builder $builder, $finish = true)
    {
        return sprintf(
            'function(%s) { %s } %s',
            $builder->buildArguments($this->getArguments()),
            implode("\n", $this->getLines()),
            $finish ? ';' : ''
        );
    }
}
