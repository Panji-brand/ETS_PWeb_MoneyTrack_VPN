    <?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    return new class extends Migration
    {
        public function up(): void
        {
            Schema::create('anggarans', function (Blueprint $table) {
                $table->id();

                $table->foreignId('kategori_id')
                    ->constrained('kategoris')
                    ->cascadeOnDelete();

                $table->decimal('jumlah_anggaran', 15, 2);
                $table->unsignedTinyInteger('bulan');
                $table->unsignedSmallInteger('tahun');

                $table->timestamps();
            });
        }

        public function down(): void
        {
            Schema::dropIfExists('anggarans');
        }
    };
