use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('product_variants', function (Blueprint $table) {
            if (!Schema::hasColumn('product_variants', 'image')) {
                $table->string('image')->nullable()->after('stock_status');
            }

            if (!Schema::hasColumn('product_variants', 'images')) {
                $table->json('images')->nullable()->after('image');
            }
        });
    }

    public function down(): void
    {
        Schema::table('product_variants', function (Blueprint $table) {
            if (Schema::hasColumn('product_variants', 'images')) {
                $table->dropColumn('images');
            }

            if (Schema::hasColumn('product_variants', 'image')) {
                $table->dropColumn('image');
            }
        });
    }
};