<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIndexesForReportingOptimization extends Migration
{
    /**
     * Run the migrations.
     *
     * Adds indexes to tables frequently used in PedidosReportsV2Controller
     * queries to improve performance.
     *
     * @return void
     */
    public function up()
    {
         // Index on pedido_gerals table
         Schema::table('pedido_gerals', function (Blueprint $table) {
            $tableName = 'pedido_gerals'; // Use variables for clarity
            $connection = Schema::getConnection();
            $schemaManager = $connection->getDoctrineSchemaManager();
            $indexes = $schemaManager->listTableIndexes($tableName);

            // Index for status
            if (!array_key_exists('pedido_gerals_status_index', $indexes)) {
                 $table->index('status', 'pedido_gerals_status_index'); // Explicitly name it
            }

            // Index for lead_name
             if (!array_key_exists('pedido_gerals_lead_name_index', $indexes)) {
                 $table->index('lead_name', 'pedido_gerals_lead_name_index');
            }

            // Index for user_id
             if (!array_key_exists('pedido_gerals_user_id_index', $indexes)) {
                 $table->index('user_id', 'pedido_gerals_user_id_index');
            }
        });

        // Index on the pivot table pedido_produto
        Schema::table('pedido_produto', function (Blueprint $table) {
            $tableName = 'pedido_produto';
            $connection = Schema::getConnection();
            $schemaManager = $connection->getDoctrineSchemaManager();
            $indexes = $schemaManager->listTableIndexes($tableName);

             if (!array_key_exists('pedido_produto_pedido_geral_id_index', $indexes)) {
                $table->index('pedido_geral_id', 'pedido_produto_pedido_geral_id_index');
             }
             if (!array_key_exists('pedido_produto_produto_id_index', $indexes)) {
                $table->index('produto_id', 'pedido_produto_produto_id_index');
             }
        });

        // Indexes for date filtering in related product tables (VERIFY TABLE NAMES!)

        // Assuming 'pedido_quartos' is the table for hotel bookings
        Schema::table('pedido_quartos', function (Blueprint $table) {
            $table->index('checkin'); // Used in date range checks
        });

        // Assuming 'pedido_transfers' is the table for transfers
        Schema::table('pedido_transfers', function (Blueprint $table) {
            $table->index('data'); // Used in date range checks
        });

        // Assuming 'pedido_games' is the table for golf/games
        Schema::table('pedido_games', function (Blueprint $table) {
            $table->index('data'); // Used in date range checks
        });

        // Assuming 'pedido_cars' is the table for car rentals
        Schema::table('pedido_cars', function (Blueprint $table) {
            $table->index('pickup_data'); // Used in date range checks
        });

        // Assuming 'pedido_tickets' is the table for tickets
        Schema::table('pedido_tickets', function (Blueprint $table) {
            $table->index('data'); // Used in date range checks
        });
    }

    /**
     * Reverse the migrations.
     *
     * Removes the indexes added in the up() method.
     *
     * @return void
     */
    public function down()
    {
        // Drop indexes from pedido_gerals table
        Schema::table('pedido_gerals', function (Blueprint $table) {
            $table->dropIndex(['status']); // Laravel automatically names indexes like tablename_column_index
            $table->dropIndex(['lead_name']);
            $table->dropIndex(['user_id']);
        });

        // Drop indexes from pedido_produto table
        Schema::table('pedido_produto', function (Blueprint $table) {
            $table->dropIndex(['pedido_geral_id']);
            $table->dropIndex(['produto_id']);
        });

        // Drop indexes from related product tables (VERIFY TABLE NAMES!)
        Schema::table('pedido_quartos', function (Blueprint $table) {
            $table->dropIndex(['checkin']);
        });

        Schema::table('pedido_transfers', function (Blueprint $table) {
            $table->dropIndex(['data']);
        });

        Schema::table('pedido_games', function (Blueprint $table) {
            $table->dropIndex(['data']);
        });

        Schema::table('pedido_cars', function (Blueprint $table) {
            $table->dropIndex(['pickup_data']);
        });

        Schema::table('pedido_tickets', function (Blueprint $table) {
            $table->dropIndex(['data']);
        });
    }
}