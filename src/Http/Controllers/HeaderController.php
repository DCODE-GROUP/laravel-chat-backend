<?php

namespace Dcodegroup\DCodeChat\Http\Controllers;

use Dcodegroup\DCodeChat\Models\Chat;
use Illuminate\Http\Request;

class HeaderController
{
    public function __invoke(Request $request, Chat $chat) {}
}
