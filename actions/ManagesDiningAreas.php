<?php

namespace Igniter\Reservation\Actions;

use Igniter\Flame\Database\Model;
use Igniter\Flame\Traits\ExtensionTrait;
use Igniter\Reservation\Models\DiningArea;
use System\Actions\ModelAction;

class ManagesDiningAreas extends ModelAction
{
    use ExtensionTrait;

    protected $manager;

    public function __construct(Model $model)
    {
        parent::__construct($model);

        $model->relation['hasMany']['dining_areas'] = [DiningArea::class];
    }
}
