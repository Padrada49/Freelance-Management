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
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->timestamps();
        });

        // Insert default pricing settings
        DB::table('settings')->insert([
            ['key' => 'freelance_monthly_price', 'value' => '299', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'freelance_yearly_price', 'value' => '2990', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'customer_monthly_price', 'value' => '199', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'customer_yearly_price', 'value' => '1990', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
