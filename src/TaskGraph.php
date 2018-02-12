<?php
/*
 * (c) 2014, Dmitrijs Balabka
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Amp;

class TaskGraph implements Task
{
    private $tasks = [];
    private $dependencies = [];

    /**
     * @param array|Task[] $tasks
     */
    public function __construct(array $tasks = [])
    {
        $this->setTasks($tasks);
    }

    public function run(...$args)
    {
        yield $this->getTaskPromises();
    }

    /**
     * Set task dependencies
     *
     * @param TaskPromise[] $tasksPromises
     *
     * @return void
     */
    private function setPromisesDependencies($tasksPromises)
    {
        foreach ($tasksPromises as $tasksPromise) {
            $depNames = $this->getTaskDependencies($tasksPromise->getTask());
            $depPromises = array_filter($tasksPromises, function ($tasksPromise) use ($depNames) {
                /** @var TaskPromise $tasksPromise */
                return in_array($this->getTaskName($tasksPromise->getTask()), $depNames, true);
            });
            $tasksPromise->setDependencies($depPromises);
        }
    }

    public function getTaskDependencies(Task $task)
    {
        return $this->dependencies[$this->getTaskName($task)];
    }

    public function getTaskName(Task $task)
    {
        $name = array_search($task, $this->tasks, true);
        if ($name === false) {
            throw new InvalidArgumentException(sprintf('Task "%s" was not found', $name));
        }
        return $name;
    }

    private function getTaskPromises()
    {
        $promises = array_map('\Amp\TaskPromise::create', $this->tasks);
        $this->setPromisesDependencies($promises);
        return $promises;
    }

    public function __invoke(...$args)
    {
        return $this->run(...$args);
    }

    public function hasTask(string $name) : bool
    {
        return array_key_exists($name, $this->tasks);
    }

    public function addTask(string $name, Task $task, array $dependencies = []) : TaskGraph
    {
        // TODO: check cyclic dependencies (see https://en.wikipedia.org/wiki/Topological_sorting)
        if ($this->hasTask($name)) {
            throw new InvalidArgumentException(sprintf('Task "%s" already added', $name));
        }
        $this->tasks[$name] = $task;
        $this->dependencies[$name] = $dependencies;

        return $this;
    }

    public function setTasks(array $tasks) : TaskGraph
    {
        array_map([$this, 'addTask'], $tasks);
        return $this;
    }
}
