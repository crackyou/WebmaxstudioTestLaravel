<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AuthController extends Controller
{

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'name' => ['required', 'string', 'max:30'],
                'phone' => ['required', 'numeric', 'unique:users', 'min:10']
            ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }

        $input = $request->all();
        $user = new User();

        $user->name = $input['name'];
        $user->phone = $input['phone'];
        $user->unique_link = Str::random(10).Str::random(10).Str::random(10);
        $user->link_created = Carbon::now();
        $user->save();

        return response()->json(['link' => $user->unique_link]);
    }

    public function check(Request $request)
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
            return response()->json(['data' => json_decode($user)]);
        }
        else {
            return response()->json(['error' => 'Пользователь не найден'], 401);
        }

    }

}
