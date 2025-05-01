<?php

namespace Dcodegroup\LaravelChat\Http\Controllers\Chat;

use Dcodegroup\LaravelChat\Models\Chat;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class ShowController extends Controller
{
    public function __invoke(Request $request, Chat $chat) {}
}
