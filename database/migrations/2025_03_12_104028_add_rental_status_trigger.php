<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        DB::unprepared("
            CREATE OR REPLACE FUNCTION update_rental_status()
            RETURNS TRIGGER AS \$\$
            BEGIN
                IF NEW.end_date < NOW() AND NEW.status <> 'completed' THEN
                    UPDATE rentals
                    SET status = 'completed'
                    WHERE id = NEW.id;

                    UPDATE cars
                    SET available = TRUE
                    WHERE id = NEW.car_id;
                END IF;
                RETURN NEW;
            END;
            \$\$ LANGUAGE plpgsql;

            CREATE TRIGGER check_rental_status
            AFTER UPDATE ON rentals
            FOR EACH ROW EXECUTE FUNCTION update_rental_status();
        ");
    }

    public function down(): void
    {
        DB::unprepared("
            DROP TRIGGER IF EXISTS check_rental_status ON rentals;
            DROP FUNCTION IF EXISTS update_rental_status();
        ");
    }
};
