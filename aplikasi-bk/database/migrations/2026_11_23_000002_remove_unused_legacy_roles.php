<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('roles')
            ->whereIn('name', ['guru', 'wali_kelas'])
            ->whereNotExists(function ($query) {
                $query->selectRaw('1')
                    ->from('model_has_roles')
                    ->whereColumn('model_has_roles.role_id', 'roles.id');
            })
            ->delete();
    }

    public function down(): void
    {
        // Legacy roles are not recreated automatically to avoid restoring removed access.
    }
};
