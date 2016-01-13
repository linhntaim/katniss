<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2015-12-06
 * Time: 16:43
 */

namespace Katniss\Models\Helpers\Session;

use Illuminate\Session\DatabaseSessionHandler as BaseDatabaseSessionHandler;
use Illuminate\Support\Facades\DB;
use Katniss\Models\UserSession;

class DatabaseSessionHandler extends BaseDatabaseSessionHandler
{
    /**
     * Create a new database session handler instance.
     *
     * @param  \Illuminate\Database\ConnectionInterface $connection
     * @param  string $table
     * @return void
     */
    public function __construct()
    {
        parent::__construct(DB::connection(config('session.connection')), config('session.table'));
    }

    /**
     * {@inheritdoc}
     */
    public function write($sessionId, $data)
    {
        parent::write($sessionId, $data);
    }

    /**
     * {@inheritdoc}
     */
    public function destroy($sessionId)
    {
        $this->getQuery()->where('id', $sessionId)->delete();
    }

    /**
     * {@inheritdoc}
     */
    public function gc($lifetime)
    {
        $this->getQuery()->where('last_activity', '<=', time() - $lifetime)->delete();
    }
}