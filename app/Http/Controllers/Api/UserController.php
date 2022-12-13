<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\History;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class UserController extends Controller
{

    public function regenerate(Request $request) {

        $validator = Validator::make($request->all(),
            [
                'key' => ['required', 'string', 'max:30', 'min:30']
            ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }

        $key = $request->get('key');
        $user = User::where('unique_link', $key)
            ->where('link_created', '>', Carbon::now()->subWeek())->first();

        if ($user) {
            $user->unique_link = Str::random(10).Str::random(10).Str::random(10);
            $user->link_created = Carbon::now();
            $user->save();
            return response()->json(['link' => $user->unique_link]);
        }
        else {
            return response()->json(['error' => 'Пользователь не найден'], 401);
        }
    }

    public function deactivate(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'key' => ['required', 'string', 'max:30', 'min:30']
            ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }

        $key = $request->get('key');
        $user = User::where('unique_link', $key)
            ->where('link_created', '>', Carbon::now()->subWeek())->first();

        if ($user) {
            $user->unique_link = '';
            $user->link_created = Carbon::now();
            return response()->json(['link' => '']);
        }
        else {
            return response()->json(['error' => 'Пользователь не найден'], 401);
        }



    }

    public function lucky(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'key' => ['required', 'string', 'max:30', 'min:30'],
                'lucky' => ['required', 'numeric', 'max:1000', 'min:1'],
            ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }

        $key = $request->get('key');
        $user = User::where('unique_link', $key)
            ->where('link_created', '>', Carbon::now()->subWeek())->first();

        if ($user) {
            $id = $user->id;
            $history = new History();
            $history->user_id = $id;
            $history->number = $request->get('lucky');
            $history->save();
            return response()->json(['status' => true]);
        }
        else {
            return response()->json(['error' => 'Пользователь не найден'], 401);
        }



    }

    public function history(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'key' => ['required', 'string', 'max:30', 'min:30'],
            ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }

        $key = $request->get('key');
        $user = User::where('unique_link', $key)
            ->where('link_created', '>', Carbon::now()->subWeek())->first();

        if ($user) {
            $history = History::where('user_id',$user->id)->orderBy('created_at','DESC')->take(3)->get();
            return response()->json(['data' => json_decode($history)]);
        }
        else {
            return response()->json(['error' => 'Пользователь не найден'], 401);
        }



    }
}
