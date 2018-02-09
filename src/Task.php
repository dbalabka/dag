<?php
/*
 * (c) 2014, Dmitrijs Balabka
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Amp;

interface Task
{
    public function run(...$args);
    public function __invoke(...$args);
    public function getDependencies(): array;
    public function getName(): string;
}
