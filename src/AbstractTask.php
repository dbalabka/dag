<?php
/*
 * (c) 2014, Dmitrijs Balabka
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Amp;


abstract class AbstractTask implements Task
{
    /**
     * @var TaskContextInterface
     */
    private $context;

    /**
     * @var string[]
     */
    private $dependencies;

    /**
     * @var string
     */
    private $name;

    public function __construct(string $name, array $dependencies = [], TaskContextInterface $context = null)
    {
        $this->name = $name;
        $this->context = $context ?? new TaskContext();
        $this->dependencies = $dependencies;
    }

    public function getDependencies(): array
    {
        return $this->dependencies;
    }

    protected function getContext(): TaskContextInterface
    {
        return $this->context;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    public function __invoke(...$args)
    {
        return $this->run(...$args);
    }
}
