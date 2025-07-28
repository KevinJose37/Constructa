<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
	/**
	 * Run the migrations.
	 */
	public function up(): void
	{
		//
		DB::statement('ALTER TABLE work_progress_chapter_details MODIFY COLUMN balance_adjustment VARCHAR(255);');
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		//
	}
};
