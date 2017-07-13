<?php
namespace App\Http\Controllers\User;

use Auth;
use Socialite;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\User;

class UserController extends Controller
{ 
	/**
     * ユーザーをTwitterの認証ページにリダイレクトする
     *
     * @return Response
     */
    public function redirectToProvider()
    {
        return Socialite::driver('twitter')->redirect();
    }
	
    /**
     * Twitterからユーザー情報を取得する
     *
     * @return Response
     */
    public function loginOrRegister()
    {
        $twitterUser = Socialite::driver('twitter')->user();
		
		$user = User::where('twitter_id', $twitterUser->id)->first();
		if(empty($user)){
			$user = User::create([
				'twitter_id'	=> $twitterUser->id,
				'twitter_name'	=> $twitterUser->name,
				'avatar'		=> $twitterUser->avatar,
			]);
		}
		else{
			$user->update([
				'twitter_name'	=> $twitterUser->name,
				'avatar'		=> $twitterUser->avatar,
			]);
		}
		
		Auth::login($user);
		
		dd($user);
    }
}
