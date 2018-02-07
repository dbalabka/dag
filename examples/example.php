<?php
/*
 * (c) 2014, Dmitrijs Balabka
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Amp\{ClosureTask, TaskGraph};

require_once __DIR__ . '/../vendor/autoload.php';

$tasks[] = new ClosureTask('1', function () {
    echo 1;
});

$tasks[] = new ClosureTask('2', function () {
    echo 2;
}, ['1']);

$tasks[] = new ClosureTask('3', function () {
    echo 3;
}, ['1']);

$tasks[] = new ClosureTask('4', function () {
    echo 4;
}, ['3', '2']);


$taskDag = new TaskGraph($tasks);

Amp\Loop::run($taskDag);
