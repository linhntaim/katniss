<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2016-12-19
 * Time: 22:48
 */

namespace Katniss\Everdeen\Http\Controllers\WebApi;

use Closure;
use Illuminate\Database\Eloquent\Collection;
use Katniss\Everdeen\Http\Controllers\WebApiController;
use Katniss\Everdeen\Http\Request;
use Katniss\Everdeen\Repositories\ConversationRepository;
use Katniss\Everdeen\Themes\ConversationTheme;
use Katniss\Everdeen\Themes\Queue\JsQueue;
use Katniss\Everdeen\Themes\Theme;

class ConversationController extends WebApiController
{
    protected $conversationRepository;

    public function __construct()
    {
        $this->middleware(function (Request $request, Closure $next) {
            Theme::$overridden = new ConversationTheme();
            $app = app();
            $app['home_theme'] = $app->share(function () {
                return Theme::$overridden;
            });
            return $next($request);
        })->only('show');

        parent::__construct();

        $this->conversationRepository = new ConversationRepository();

        $this->middleware('device');
    }

    public function show(Request $request, $id)
    {
        if ($request->has('messages')) {
            return $this->messages($request, $id);
        }

        return $this->responseFail();
    }

    protected function messages(Request $request, $id)
    {
        $conversation = $this->conversationRepository->model($id);
        $users = collect([]);
        $devices = collect([]);
        if ($conversation->isPublic) {
            $this->conversationRepository->updateCurrentDevice();
            $users = $conversation->users()->get();
            $devices = $conversation->devices()->get();
        } elseif ($conversation->isDirect) {
            $users = $conversation->users;
            if (!$request->isAuth
                || $users->count() != 2
                || $users->where('id', $request->authUser->id)->count() <= 0
            ) {
                abort(404);
            }
        }

        if ($request->isAuth) {
            $users = $this->changeFirstPosition($users, $request->authUser->id);
        } else {
            $devices = $this->changeFirstPosition($devices, deviceRealId());
        }

        $jsQueue = new JsQueue();
        $jsQueue->add('global-vars', [
            'ORTC_SERVER' => env('ORTC_SERVER'),
            'ORTC_CLIENT_ID' => session()->getId(),
            'ORTC_CLIENT_KEY' => env('ORTC_CLIENT_KEY'),
            'ORTC_CLIENT_SECRET' => env('ORTC_CLIENT_SECRET'),
            'CONVERSATION_ID' => $conversation->id,
            'CONVERSATION_CHANNEL' => $conversation->channel->code,
            'CURRENT_DEVICE_ID' => deviceId(),
            'CONVERSATION_USERS' => json_encode($users->pluck('url_avatar_thumb', 'id')->all()),
            'CONVERSATION_DEVICES' => json_encode($devices->pluck('pivot.color', 'id')->all()),
        ], JsQueue::TYPE_VAR, ['CONVERSATION_USERS', 'CONVERSATION_DEVICES']);
        $jsQueue = $jsQueue->flush(false);

        return view('conversation', [
            'conversation' => $conversation,
            'conversation_users' => $users,
            'conversation_devices' => $devices,
            'js_queue' => $jsQueue,
        ]);
    }

    protected function changeFirstPosition(Collection $collection, $itemId)
    {
        if ($collection->count() <= 0) return $collection;

        $searchIndex = $collection->search(function ($item) use ($itemId) {
            return $item->id == $itemId;
        });
        if ($searchIndex === false) return $collection;

        $collection->prepend($collection->splice($searchIndex, 1)->first());
        return $collection;
    }
}