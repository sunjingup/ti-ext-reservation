<?php

namespace Igniter\Reservation\Models;

use Igniter\Flame\Database\Traits\NestedTree;
use Igniter\Flame\Database\Traits\Sortable;
use Igniter\Flame\Database\Traits\Validation;

class DiningTable extends \Igniter\Flame\Database\Model
{
    use Sortable;
    use NestedTree;
    use Validation;

    const SORT_ORDER = 'priority';

    public $table = 'dining_tables';

    public $timestamps = true;

    protected $guarded = [];

    /**
     * @var array Relations
     */
    public $relation = [
        'belongsTo' => [
            'dining_area' => [DiningArea::class],
            'dining_section' => [DiningSection::class],
        ],
    ];

    public $rules = [
        'name' => ['sometimes', 'required', 'string', 'between:2,255'],
        'min_capacity' => ['sometimes', 'required', 'integer', 'min:1', 'lte:max_capacity'],
        'max_capacity' => ['sometimes', 'required', 'integer', 'min:1', 'gte:min_capacity'],
        'extra_capacity' => ['sometimes', 'required', 'integer'],
        'priority' => ['sometimes', 'required', 'integer'],
        'is_enabled' => ['sometimes', 'required', 'boolean'],
        'dining_area_id' => ['sometimes', 'required', 'integer'],
        'dining_section_id' => ['integer'],
    ];

    public function getSectionNameAttribute()
    {
        return optional($this->dining_section)->name;
    }

    public function beforeSave()
    {
        if (!$this->getRgt() || !$this->getLft())
            $this->fixTree();

        if (static::isBroken())
            static::fixTree();
    }

    public function scopeWhereIsReservable($query)
    {
        return $query->whereIsRoot()->where('is_enabled', 1);
    }
}
