<?php
/*
 * (c) 2014, Dmitrijs Balabka
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Amp;

class ClosureTask extends AbstractTask
{
    /**
     * @var \Closure
     */
    private $closure;

    /**
     * ClosureTask constructor.
     *
     * @param string                    $name
     * @param \Closure                  $closure
     * @param array                     $deps
     * @param TaskContextInterface|null $context
     */
    public function __construct(string $name, \Closure $closure, array $deps = [], TaskContextInterface $context = null)
    {
        parent::__construct($name, $deps, $context);
        $this->closure = $closure;
    }

    public function run(...$args)
    {
        return $this->closure->call($this->getContext(), ...$args);
    }
}
