<?php

namespace Dcodegroup\DCodeChat\Http\Controllers;

use Illuminate\Http\Request;

class SearchController
{
    public function __invoke(Request $request)
    {
        $search = $request->input('query');
        $results = collect();

        if ($search) {
            $results = auth()->user()->load(['chats' => function ($query) use ($search) { // @phpstan-ignore-line
                $query->where('is_archived', false)
                    ->where(function ($query) use ($search) {
                        $query->where('chat_title', 'like', '%'.$search.'%')
                            ->orWhere('chat_description', 'like', '%'.$search.'%')
                            ->orWhereHas('messages', function ($query) use ($search) {
                                $query->where('message', 'like', '%'.$search.'%');
                            });
                    })
                    ->with(['messages' => function ($query) {
                        $query->where('is_archived', false)
                            ->orderBy('created_at', 'desc');
                    }]);
            }])->chats; // @phpstan-ignore-line
        }

        return response()->json($results);
    }
}
