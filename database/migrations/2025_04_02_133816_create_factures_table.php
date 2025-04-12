<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('factures', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            $table->string('num_fac')->unique();
            $table->date('date_facture');
            $table->decimal('montant_total', 10, 2);
            $table->timestamps();
        });

        DB::statement("CREATE SEQUENCE facture_num_seq START 1000");
        DB::statement("ALTER TABLE factures ALTER COLUMN num_fac SET DEFAULT 'FAC-' || nextval('facture_num_seq')::text");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("DROP SEQUENCE IF EXISTS facture_num_seq");
        Schema::dropIfExists('factures');
    }
};
