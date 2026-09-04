<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Le domaine d'intervention auquel appartient le produit
            $table->foreignId('domain_id')->nullable()->after('category_id')->constrained()->nullOnDelete();

            // Le fabricant du produit (choisi parmi les partenaires)
            $table->foreignId('partner_id')->nullable()->after('domain_id')->constrained()->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropConstrainedForeignId('partner_id');
            $table->dropConstrainedForeignId('domain_id');
        });
    }
};
