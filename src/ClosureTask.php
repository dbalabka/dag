<?php
/*
 * (c) 2014, Dmitrijs Balabka
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Amp;

class ClosureTask implements Task
{
    /**
     * @var \Closure
     */
    private $closure;

    /**
     * @var Context
     */
    private $context;


    /**
     * ClosureTask constructor.
     *
     * @param \Closure     $closure
     * @param Context|null $context
     */
    public function __construct(\Closure $closure, Context $context = null)
    {
        $this->closure = $closure;
        $this->context = $context ?? new DefaultContext();
    }

    public function run(...$args)
    {
        return $this->closure->call($this->getContext(), ...$args);
    }

    protected function getContext(): Context
    {
        return $this->context;
    }

    public function __invoke(...$args)
    {
        return $this->run(...$args);
    }
}
