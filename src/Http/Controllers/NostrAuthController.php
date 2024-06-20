<?php

namespace Kriptonix\LaravelNostrAuth\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Models\User;
use Kriptonix\LaravelNostrAuth\App\Models\Event;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;

class NostrAuthController extends Controller
{
    public function nostrLogin(Request $request)
    {
        $event = new Event();
        $event->setId($request['id']);
        $event->setPublicKey($request['pubkey']);
        $event->setSignature($request['sig']);
        $event->setKind($request['kind']);
        $event->setContent($request['content']);
        $event->setCreatedAt($request['created_at']);

        $verify = $event->verify($event);

        if ($verify) {
            $user = User::where('pubkey', $request['pubkey'])->first();

            if (!$user) {
                // Create a new user
                $user = User::create([
                    'name' => 'Nostr User', // Or any other logic to create a user name
                    'email' => $request['pubkey'] . '@nostr.io', // Nostr doesn't use email, so just use a placeholder
                    'password' => Hash::make(rand()), // You can set any random password since it won't be used
                    'pubkey' => $request['pubkey'],
                ]);
            }

            // Log the user in
            Auth::login($user);

            $request->session()->regenerate();

            return response()->json(['redirect' => config('nostr-auth.redirect_after_login')]);
        } else {
            return response()->json(['message' => 'Invalid signature'], 401)->header('Content-Type', 'application/json');
        }
    }
}
