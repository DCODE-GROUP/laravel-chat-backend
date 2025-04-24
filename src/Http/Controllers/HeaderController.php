<?php

namespace Dcodegroup\LaravelChat\Http\Controllers;

use Dcodegroup\LaravelChat\Models\Chat;
use Illuminate\Http\Request;

class HeaderController
{
    public function __invoke(Request $request, Chat $chat) {}
}
