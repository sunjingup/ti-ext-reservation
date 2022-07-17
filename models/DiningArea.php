<?php

namespace Igniter\Reservation\Models;

use Admin\Models\Locations_model;
use Admin\Traits\Locationable;
use Igniter\Flame\Database\Traits\Purgeable;

class DiningArea extends \Igniter\Flame\Database\Model
{
    use Purgeable;
    use Locationable;

    public $table = 'dining_areas';

    public $timestamps = true;

    /**
     * @var array Relations
     */
    public $relation = [
        'hasMany' => [
            'dining_sections' => [DiningSection::class],
            'dining_tables' => [DiningTable::class, 'scope' => 'whereIsLeaf'],
            'dining_table_combos' => [DiningTable::class, 'scope' => 'hasChildren'],
            'reservable_tables' => [DiningTable::class, 'scope' => 'whereIsReservable'],
        ],
        'belongsTo' => [
            'location' => [Locations_model::class],
        ],
    ];

    protected $purgeable = ['dining_tables', 'dining_table_combos'];

    public static function getDropdownOptions()
    {
        return static::dropdown('name');
    }

    protected function getScopeAttributes()
    {
        return ['dining_area_id', 'dining_section_id'];
    }

    //
    // Accessors & Mutators
    //

    public function getDiningTableCountAttribute($value)
    {
        return $this->dining_tables->count();
    }
}
