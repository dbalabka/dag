<?php
/*
 * (c) 2014, Dmitrijs Balabka
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Amp;

class TaskGraph implements TaskInterface
{
    private $tasks;
    private $name;

    /**
     * ClosureTask dag constructor.
     *
     * @param array|ClosureTask[] $tasks
     */
    public function __construct(array $tasks, string $name = '')
    {
        $this->tasks = array_map('\Amp\TaskPromise::create', $tasks);
        $this->setTaskDependencies($this->tasks);
        $this->name = $name;
    }

    public function run(...$args)
    {
        yield $this->tasks;
    }

    /**
     * Set task dependencies
     *
     * @param TaskPromise[] $tasksPromises
     *
     * @return void
     */
    private function setTaskDependencies($tasksPromises)
    {
        // TODO: check cyclic dependencies (see https://en.wikipedia.org/wiki/Topological_sorting)
        foreach ($tasksPromises as $tasksPromise) {
            $depNames = $tasksPromise->getTask()->getDependencies();
            $depPromies = array_filter($tasksPromises, function ($tasksPromise) use ($depNames) {
                /** @var TaskPromise $tasksPromise */
                return in_array($tasksPromise->getTask()->getName(), $depNames, true);
            });
            $tasksPromise->setDependencies($depPromies);
        }
    }

    public function __invoke(...$args)
    {
        return $this->run(...$args);
    }

    public function getDependencies(): array
    {
        return [];
    }

    public function getName(): string
    {
        return $this->name;
    }
}
