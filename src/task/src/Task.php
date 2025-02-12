<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://doc.hyperf.io
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf-cloud/hyperf/blob/master/LICENSE
 */

namespace Hyperf\Task;

class Task
{
    /**
     * @var callable|array
     */
    public $callback;

    /**
     * @var array
     */
    public $arguments;

    public function __construct($callback, array $arguments = [])
    {
        $this->callback = $callback;
        $this->arguments = $arguments;
    }
}
