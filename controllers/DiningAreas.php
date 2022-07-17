<?php

namespace Igniter\Reservation\Controllers;

use Admin\Facades\AdminMenu;
use Igniter\Flame\Exception\ApplicationException;

/**
 * Admin Controller Class Dining Areas
 */
class DiningAreas extends \Admin\Classes\AdminController
{
    public $implement = [
        \Admin\Actions\ListController::class,
        \Admin\Actions\FormController::class,
        \Admin\Actions\LocationAwareController::class,
    ];

    public $listConfig = [
        'list' => [
            'model' => \Igniter\Reservation\Models\DiningArea::class,
            'title' => 'lang:igniter.reservation::default.dining_areas.text_title',
            'emptyMessage' => 'lang:igniter.reservation::default.dining_areas.text_empty',
            'defaultSort' => ['updated_at', 'DESC'],
            'configFile' => 'dining_area',
        ],
    ];

    public $formConfig = [
        'name' => 'lang:igniter.reservation::default.dining_areas.text_form_name',
        'model' => \Igniter\Reservation\Models\DiningArea::class,
        'request' => \Igniter\Reservation\Requests\DiningArea::class,
        'create' => [
            'title' => 'lang:admin::lang.form.create_title',
            'redirect' => 'igniter/reservation/dining_areas/edit/{id}',
            'redirectClose' => 'igniter/reservation/dining_areas',
            'redirectNew' => 'igniter/reservation/dining_areas/create',
        ],
        'edit' => [
            'title' => 'lang:admin::lang.form.edit_title',
            'redirect' => 'igniter/reservation/dining_areas/edit/{id}',
            'redirectClose' => 'igniter/reservation/dining_areas',
            'redirectNew' => 'igniter/reservation/dining_areas/create',
        ],
        'preview' => [
            'title' => 'lang:admin::lang.form.preview_title',
            'redirect' => 'igniter/reservation/dining_areas',
        ],
        'delete' => [
            'redirect' => 'igniter/reservation/dining_areas',
        ],
        'configFile' => 'dining_area',
    ];

    protected $requiredPermissions = 'Igniter.Reservation.DiningAreas';

    public function __construct()
    {
        parent::__construct();

        AdminMenu::setContext('dining_areas', 'restaurant');
    }

    public function edit_onApplySection($context = null, $recordId = null)
    {
        if (!$checked = $this->getCheckedTables())
            throw new ApplicationException(lang('igniter.reservation::default.dining_areas.alert_tables_not_checked'));

        $model = $this->asExtension('FormController')->formFindModelObject($recordId);

        $sectionId = (int)post('DiningArea._dining_sections');
        if (!$model->dining_sections()->find($sectionId))
            throw new ApplicationException(lang('igniter.reservation::default.dining_areas.alert_section_not_found'));

        $model->dining_tables()
            ->whereIn('id', $checked)
            ->update(['dining_section_id' => $sectionId]);

        flash()->success(sprintf(lang('admin::lang.alert_success'), 'Selected table(s) sectioned'))->now();

        return $this->redirectBack();
    }

    public function edit_onCreateCombo($context = null, $recordId = null)
    {
        if (!$checked = (array)post('DiningArea._dining_tables', []))
            throw new ApplicationException(lang('igniter.reservation::default.dining_areas.alert_tables_not_checked'));

        $model = $this->asExtension('FormController')->formFindModelObject($recordId);

        $checkedTables = $model->dining_tables()->whereIn('id', $checked)->get();
        $tableNames = $checkedTables->pluck('name')->join('/');

        $comboTable = $model->dining_tables()->create([
            'name' => $tableNames,
            'dining_area_id' => $model->id,
            'min_capacity' => $checkedTables->sum('min_capacity'),
            'max_capacity' => $checkedTables->sum('max_capacity'),
        ]);

        // check checked tables belong to the same section

        // check if check tables does not already belong to a combo table

        $checkedTables->each(function ($table) use ($comboTable) {
            $table->parent()->associate($comboTable)->save();
        });

        flash()->success(sprintf(lang('admin::lang.alert_success'), 'Table combo created'))->now();

        return $this->redirectBack();
    }

    protected function getCheckedTables()
    {
        return collect(post('DiningArea.dining_tables', []))
            ->filter(function ($table) {
                return (bool)$table['is_selected'];
            })
            ->pluck('id')
            ->filter()
            ->toArray();
    }
}
