<?php

return [
    'layout' => [
        'sidebar' => [
            'should_show_logo' => false,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Filament export
    |--------------------------------------------------------------------------
    |
    | When exporting from filament bulk action and $exportColumns
    | is not defined, the export will default to exporting from the table columns.
    | If false the export will be created based on $fillable.
    |
    */
    'excel' => [
        'export_from_table' => true,
        'concatenate_relations_with' => "\n",
    ],
];
