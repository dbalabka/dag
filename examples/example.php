<?php
/*
 * (c) 2014, Dmitrijs Balabka
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Amp\{ClosureTask, TaskGraph, Context};

require_once __DIR__ . '/../vendor/autoload.php';

$taskDag = (new TaskGraph())

    // root task in graph (there might be multiple root tasks)
    ->addTask('1', new ClosureTask(function () {
        echo 1;
    }))

    // these two tasks were be executed only after task with name '1'
    // the order of execution is not guaranteed
    ->addTask('2', new ClosureTask(function () {
        echo 2;
    }), ['1'])
    ->addTask('3', new ClosureTask(function () {
        echo 3;
    }), ['1'])

    // this is the last task in graph (there might be multiple leaf tasks)
    ->addTask('4', new ClosureTask(function () {
        echo 4;
    }), ['3', '2']);

Amp\Loop::run($taskDag); // will output "1234"
