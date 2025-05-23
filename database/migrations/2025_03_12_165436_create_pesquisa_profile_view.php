<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreatePesquisaProfileView extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("
            CREATE VIEW `pesquisaProfile` AS
            select 'Quartos' AS `tabela`,`pq`.`checkin` AS `dataCheckin`,`pq`.`pedido_produto_id` AS `pedido_produto_id`,`pg`.`id` AS `id`,`pg`.`type` AS `type`,`pg`.`lead_name` AS `lead_name`,`pg`.`responsavel` AS `responsavel`,`pg`.`referencia` AS `referencia`,`pg`.`created_at` AS `created_at`,`pg`.`updated_at` AS `updated_at`,`pg`.`user_id` AS `user_id`,`pg`.`valor` AS `valor`,`pg`.`profit` AS `profit`,`pg`.`status` AS `status`,`pg`.`deleted_at` AS `deleted_at` from ((`pedido_gerals` `pg` join `pedido_produto` `pp` on(`pg`.`id` = `pp`.`pedido_geral_id`)) join `pedido_quartos` `pq` on(`pp`.`id` = `pq`.`pedido_produto_id`)) where `pg`.`deleted_at` is null union select 'Transfers' AS `tabela`,`pq`.`data` AS `dataCheckin`,`pq`.`pedido_produto_id` AS `pedido_produto_id`,`pg`.`id` AS `id`,`pg`.`type` AS `type`,`pg`.`lead_name` AS `lead_name`,`pg`.`responsavel` AS `responsavel`,`pg`.`referencia` AS `referencia`,`pg`.`created_at` AS `created_at`,`pg`.`updated_at` AS `updated_at`,`pg`.`user_id` AS `user_id`,`pg`.`valor` AS `valor`,`pg`.`profit` AS `profit`,`pg`.`status` AS `status`,`pg`.`deleted_at` AS `deleted_at` from ((`pedido_gerals` `pg` join `pedido_produto` `pp` on(`pg`.`id` = `pp`.`pedido_geral_id`)) join `pedido_transfers` `pq` on(`pp`.`id` = `pq`.`pedido_produto_id`)) where `pg`.`deleted_at` is null union select 'Tickets' AS `tabela`,`pq`.`data` AS `dataCheckin`,`pq`.`pedido_produto_id` AS `pedido_produto_id`,`pg`.`id` AS `id`,`pg`.`type` AS `type`,`pg`.`lead_name` AS `lead_name`,`pg`.`responsavel` AS `responsavel`,`pg`.`referencia` AS `referencia`,`pg`.`created_at` AS `created_at`,`pg`.`updated_at` AS `updated_at`,`pg`.`user_id` AS `user_id`,`pg`.`valor` AS `valor`,`pg`.`profit` AS `profit`,`pg`.`status` AS `status`,`pg`.`deleted_at` AS `deleted_at` from ((`pedido_gerals` `pg` join `pedido_produto` `pp` on(`pg`.`id` = `pp`.`pedido_geral_id`)) join `pedido_tickets` `pq` on(`pp`.`id` = `pq`.`pedido_produto_id`)) where `pg`.`deleted_at` is null union select 'Golfs' AS `tabela`,`pq`.`data` AS `dataCheckin`,`pq`.`pedido_produto_id` AS `pedido_produto_id`,`pg`.`id` AS `id`,`pg`.`type` AS `type`,`pg`.`lead_name` AS `lead_name`,`pg`.`responsavel` AS `responsavel`,`pg`.`referencia` AS `referencia`,`pg`.`created_at` AS `created_at`,`pg`.`updated_at` AS `updated_at`,`pg`.`user_id` AS `user_id`,`pg`.`valor` AS `valor`,`pg`.`profit` AS `profit`,`pg`.`status` AS `status`,`pg`.`deleted_at` AS `deleted_at` from ((`pedido_gerals` `pg` join `pedido_produto` `pp` on(`pg`.`id` = `pp`.`pedido_geral_id`)) join `pedido_games` `pq` on(`pp`.`id` = `pq`.`pedido_produto_id`)) where `pg`.`deleted_at` is null union select 'Cars' AS `tabela`,`pq`.`pickup_data` AS `dataCheckin`,`pq`.`pedido_produto_id` AS `pedido_produto_id`,`pg`.`id` AS `id`,`pg`.`type` AS `type`,`pg`.`lead_name` AS `lead_name`,`pg`.`responsavel` AS `responsavel`,`pg`.`referencia` AS `referencia`,`pg`.`created_at` AS `created_at`,`pg`.`updated_at` AS `updated_at`,`pg`.`user_id` AS `user_id`,`pg`.`valor` AS `valor`,`pg`.`profit` AS `profit`,`pg`.`status` AS `status`,`pg`.`deleted_at` AS `deleted_at` from ((`pedido_gerals` `pg` join `pedido_produto` `pp` on(`pg`.`id` = `pp`.`pedido_geral_id`)) join `pedido_cars` `pq` on(`pp`.`id` = `pq`.`pedido_produto_id`)) where `pg`.`deleted_at` is null order by field(`status`,'In Progress','Waiting Confirmation','Edited','Confirmed','Cancelled'),`dataCheckin`
        ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('DROP VIEW IF EXISTS pesquisaProfile;');
    }
}