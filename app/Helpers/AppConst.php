<?php

namespace App\Helpers;

/**
 * JSON response
 */
define('RESP_STATUS', 'status');
define('RESP_MSG', 'message');
define('RESP_ERR', 'error');
define('RESP_DATA', 'data');
define('RESP_REDIRECT', 'redirect');
define('PAGINATION_PER_PAGE', 15);

/**
 * DateTime default formats
 */
define('DATE_FORMAT', 'd/m/Y');
define('DB_DATE_FORMAT', 'Y-m-d');
define('DATETIME_FORMAT', 'd/m/Y H:i:s');
define('DB_DATETIME_FORMAT', 'Y-m-d H:i:s');

/**
 * CRUD
 */
define('CREATE', 'create');
define('VIEW', 'view');
define('VIEW_ANY', 'view_any');
define('UPDATE', 'update');
define('DELETE', 'delete');
define('DELETE_ANY', 'delete_any');
define('RESTORE', 'restore');
define('RESTORE_ANY', 'restore_any');
define('REPLICATE', 'replicate');
define('REORDER', 'reorder');
define('FORCE_DELETE', 'force_delete');
define('FORCE_DELETE_ANY', 'force_delete_any');

/**
 * Aliases
 */
define('INDEX', VIEW_ANY);
define('STORE', CREATE);
define('REQUEST_DATE_FORMAT', DATE_FORMAT);
define('DISPLAY_DATE_FORMAT', DATE_FORMAT);
define('SERIALIZE_DATE_FORMAT', DATE_FORMAT);
define('SERIALIZE_DATETIME_FORMAT', DATETIME_FORMAT);

class AppConst
{
    private const CRUD_OPERATIONS = [
        CREATE,
        VIEW,
        UPDATE,
        DELETE,
    ];

    private const CRUD_OPERATIONS_COMPLETE = [
        CREATE,
        VIEW,
        VIEW_ANY,
        UPDATE,
        DELETE,
        DELETE_ANY,
        RESTORE,
        RESTORE_ANY,
        REPLICATE,
        REORDER,
        FORCE_DELETE,
        FORCE_DELETE_ANY,
    ];

    public static function getCrudOperations(?string $type = null): array
    {
        if (!$type || $type == 'short') {
            return static::CRUD_OPERATIONS;
        }

        if ($type == 'complete') {
            return static::CRUD_OPERATIONS_COMPLETE;
        }
    }
}
