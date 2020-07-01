<?php

namespace Corals\Modules\Utility\Providers;

use Corals\Foundation\Providers\BaseUninstallModuleServiceProvider;
use Corals\Modules\Utility\database\migrations\CreateAddressTables;
use Corals\Modules\Utility\database\migrations\CreateCategoryAttributeTables;
use Corals\Modules\Utility\database\migrations\CreateCommentsTable;
use Corals\Modules\Utility\database\migrations\CreateGuidesTable;
use Corals\Modules\Utility\database\migrations\CreateListOfValuesTable;
use Corals\Modules\Utility\database\migrations\CreateRatingsTable;
use Corals\Modules\Utility\database\migrations\CreateSchedulesTable;
use Corals\Modules\Utility\database\migrations\CreateSEOItemsTable;
use Corals\Modules\Utility\database\migrations\CreateTagTables;
use Corals\Modules\Utility\database\migrations\CreateWebhooksTable;
use Corals\Modules\Utility\database\migrations\CreateWishlistsTable;
use Corals\Modules\Utility\database\seeds\UtilityDatabaseSeeder;

class UninstallModuleServiceProvider extends BaseUninstallModuleServiceProvider
{
    protected $migrations = [
        CreateSEOItemsTable::class,
        CreateRatingsTable::class,
        CreateWishlistsTable::class,
        CreateAddressTables::class,
        CreateTagTables::class,
        CreateCategoryAttributeTables::class,
        CreateSchedulesTable::Class,
        CreateCommentsTable::class,
        CreateWebhooksTable::class,
        CreateListOfValuesTable::class,
        CreateGuidesTable::class
    ];

    protected function booted()
    {
        $this->dropSchema();

        $utilityDatabaseSeeder = new UtilityDatabaseSeeder();

        $utilityDatabaseSeeder->rollback();
    }
}
