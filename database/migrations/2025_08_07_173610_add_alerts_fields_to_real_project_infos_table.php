<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
	/**
	 * Run the migrations.
	 */
	public function up(): void
	{
		Schema::table('real_project_infos', function (Blueprint $table) {
			$table->decimal('umbral_fisico', 15, 2)->default(0);
			$table->decimal('umbral_financiero', 15, 2)->default(0);
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::table('real_project_infos', function (Blueprint $table) {
			$table->dropColumn(['umbral_fisico', 'umbral_financiero']);
		});
	}
};
