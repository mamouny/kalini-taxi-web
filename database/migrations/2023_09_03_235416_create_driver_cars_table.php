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
        Schema::create('car_documents', function (Blueprint $table) {
            $table->id();
            $table->string("driver_id_firebase")->unique();
            $table->string("car_assurance_photo");
            $table->string("car_vignette_photo");
            $table->string("car_carte_grise_photo");
            $table->foreignIdFor(\App\Models\User::class);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('driver_cars');
    }
};
