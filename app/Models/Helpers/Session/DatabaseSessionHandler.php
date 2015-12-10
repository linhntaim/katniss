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
        $userId = null;
        $status = UserSession::STATUS_OFFLINE;
        $clientIp = clientIp();
        if (isAuth()) {
            $userId = authUser()->id;
            $status = UserSession::STATUS_ONLINE;
        }

        if ($this->exists) {
            $this->getQuery()->where('id', $sessionId)->update([
                'payload' => base64_encode($data),
                'last_activity' => time(),
                'user_id' => $userId,
                'status' => $status,
                'client_ip' => $clientIp,
            ]);
        } else {
            $this->getQuery()->insert([
                'id' => $sessionId,
                'payload' => base64_encode($data),
                'last_activity' => time(),
                'user_id' => $userId,
                'status' => $status,
                'client_ip' => $clientIp,
            ]);
        }

        $this->exists = true;
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