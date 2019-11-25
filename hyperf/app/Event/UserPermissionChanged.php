<?php

declare(strict_types=1);

namespace App\Event;

class UserPermissionChanged
{
    /**
     * @var object
     */
    public $model;

    /**
     * @var int|array
     */
    public $uid;

    public function __construct($model, $uid)
    {
        $this->model = $model;
        $this->uid = $uid;
    }
}
