<?php

namespace Dcodegroup\DCodeChat\Http\Controllers;

use Illuminate\Contracts\Auth\Access\Authorizable;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Response;

class UserAvatarController extends Controller
{
    /**
     * Get the chat avatar.
     *
     * @return \Illuminate\Http\Response
     */
    public function __invoke(\Dcodegroup\DCodeChat\Models\Chat $chat, Authorizable $user)
    {
        $selected = request()->input('selected', 'default');
        // Return the default SVG avatar for the chat user
        $fillColor = ($selected == 'selected') ? '#22c55e' : '#CCCCCC';
        $svg = '<svg xmlns="http://www.w3.org/2000/svg" fill="'.$fillColor.'" viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>';

        return Response::make($svg, 200, [
            'Content-Type' => 'image/svg+xml',
            'Cache-Control' => 'no-cache',
        ]);
    }
}
