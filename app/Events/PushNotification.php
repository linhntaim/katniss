<?php

namespace Katniss\Events;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Queue\SerializesModels;

class PushNotification extends Event
{
    use SerializesModels;

    public $users;

    public $urlIndex;

    public $urlParams;

    public $messageIndex;

    public $messageParams;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($users, $urlIndex, $messageIndex, array $urlParams = [], array $messageParams = [])
    {
        parent::__construct();

        $this->users = $users instanceof Collection ? $users->toArray() : (array)$users;
        $this->urlIndex = $urlIndex;
        $this->messageIndex = $messageIndex;
        $this->messageParams = $messageParams;
    }
}
