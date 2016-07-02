<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2016-07-02
 * Time: 11:43
 */

namespace Katniss\Models\Helpers\Database;

use Illuminate\Database\MySqlConnection as BaseMySqlConnection;

class MySqlConnection extends BaseMySqlConnection
{
    public function getSchemaBuilder()
    {
        $this->schemaGrammar = new MySqlGrammar();
        $schemaBuilder = parent::getSchemaBuilder();
        $schemaBuilder->blueprintResolver(function ($table, $callback) {
            return new Blueprint($table, $callback);
        });

        return $schemaBuilder;
    }
}