<?php
/*
 * (c) 2014, Dmitrijs Balabka
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Amp;

class TaskPromise implements Promise
{
    private $task;
    private $dependencies;
    private $promise;

    public function __construct(ClosureTask $task, array $dependenciesPromises = [])
    {
        $this->task = $task;
        $this->dependencies = $dependenciesPromises;
    }

    public function __invoke()
    {
        yield $this->dependencies;
        return call($this->task);
    }

    public function onResolve(callable $onResolved) {
        if ($this->promise === null) {
            $this->promise = call($this);
        }
        $this->promise->onResolve($onResolved);
    }

    /**
     * Set dependencies
     *
     * @param array|Promise[] $dependencies
     *
     * @return void
     */
    public function setDependencies(array $dependencies)
    {
        $this->dependencies = $dependencies;
    }

    /**
     * Get task
     *
     * @return ClosureTask
     */
    public function getTask(): ClosureTask
    {
        return $this->task;
    }

    public static function create(ClosureTask $task, array $dependenciesPromises = [])
    {
        return new static($task, $dependenciesPromises);
    }
}
