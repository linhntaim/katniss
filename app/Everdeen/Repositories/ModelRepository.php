<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2016-12-04
 * Time: 17:58
 */

namespace Katniss\Everdeen\Repositories;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

abstract class ModelRepository
{
    #region Static
    private static $currentTransactionConnections = [];
    private static $hasTransaction = false;

    public static function transactionStart($connection = null)
    {
        if (empty($connection)) {
            $connection = config('database.default');
        }
        if (!in_array($connection, self::$currentTransactionConnections)) {
            DB::connection($connection)->beginTransaction();
            self::$currentTransactionConnections[] = $connection;
            self::$hasTransaction = true;
        }
    }

    public static function transactionComplete()
    {
        if (!self::$hasTransaction) return;
        foreach (self::$currentTransactionConnections as $connection) {
            DB::connection($connection)->commit();
        }
        self::$currentTransactionConnections = [];
        self::$hasTransaction = false;
    }

    public static function transactionStop()
    {
        if (!self::$hasTransaction) return;
        foreach (self::$currentTransactionConnections as $connection) {
            DB::connection($connection)->rollBack();
        }
        self::$currentTransactionConnections = [];
        self::$hasTransaction = false;
    }
    #endregion

    protected $model;

    public function __construct($id = null)
    {
        $this->model($id);
    }

    public function model($id = null)
    {
        if (!empty($id)) {
            $this->model = $id instanceof Model ? $id : $this->getById($id);
        }
        return $this->model;
    }

    public function clearModel()
    {
        $this->model = null;
    }

    public abstract function getById($id);

    public function getAll()
    {
        return [];
    }
}