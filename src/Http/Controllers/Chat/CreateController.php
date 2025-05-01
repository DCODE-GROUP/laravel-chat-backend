<?php

namespace Dcodegroup\LaravelChat\Http\Controllers\Chat;

use Dcodegroup\LaravelChat\Events\LaravelChatMessageCreated;
use Dcodegroup\LaravelChat\Http\Requests\Chat\CreateRequest;
use Dcodegroup\LaravelChat\Models\Chat;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controller;

class CreateController extends Controller
{
    public function __invoke(CreateRequest $request): RedirectResponse
    {
        //        dd('got to top of controller');
        $chat = Chat::query()->byRelation($request->input('chattable_type'), $request->input('chattable_id'))->first();

        if ($chat instanceof Chat) {
            //            dd('should not be here');

            return redirect()->route(config('laravel-chat.route_name').'.chat.show', $chat);
        }

        $chat = Chat::query()->create([
            'chattable_type' => $request->input('chattable_type'),
            'chattable_id' => $request->input('chattable_id'),
            'open' => true,
        ]);

        //        dd('here');

        event(new LaravelChatMessageCreated($chat));

        return redirect()->route(config('laravel-chat.route_name').'.chat.show', $chat);
    }
}
