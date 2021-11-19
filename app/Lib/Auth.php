<?php namespace App\Lib ; ?>
    <?php
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use App\Models\AdminAccessToken;
?>
    <?php
final class Auth
{
    private const SESSION_ACCESS_TOKEN_KEY = 'admin.accessToken';
    private static AdminAccessToken|null $adminAccessToken;
    private static bool $isFoundAccessTokenFromDb=false;
    public  static function hasAdminAccessToken():bool
    {
        return Session::has(static::SESSION_ACCESS_TOKEN_KEY);
    }
    public static function hasAdmin():bool
    {
        return Session::has('admin');
    }
    public static  function accessTokenIsValid():bool
    {
        $accessToken = static::getAccessToken();
        if (empty($accessToken))
            return false;
        if (is_null(static::getAccessTokenFromDataBase()))
            return false;
        return true;
    }
    public static  function getAccessToken():null|string
    {
        return Session::get(static::SESSION_ACCESS_TOKEN_KEY);
    }
    public static function getAccessTokenFromDataBase():null|AdminAccessToken
    {
        if (static::$isFoundAccessTokenFromDb)
            return static::$adminAccessToken;
        $accessToken = new AdminAccessToken();
        static::$adminAccessToken =
            $accessToken
                ->getTokenValidationResult(
                    static::getAccessToken() ,
                    [
                        'admins.nick' ,
                        'admins.thumbnail' ,
                        'admin_access_tokens.id'
                    ]
                );
        static::$isFoundAccessTokenFromDb = true;
        if (static::$adminAccessToken instanceof AdminAccessToken)
            Session::put('admin.data',static::$adminAccessToken->getAttributes());
        return static::$adminAccessToken;
    }
    public static function getSessionAccessTokenKey():string
    {
        return static::SESSION_ACCESS_TOKEN_KEY;
    }
    public static  function isLogIn():bool
    {
        if (static::hasAdminAccessToken() and static::accessTokenIsValid())
            return true;
        static::removeAdminFromSession();
        return false;
    }
    public static  function redirectToAdminDashResponse():RedirectResponse
    {
        return Redirect::route('admin.dashboard');
    }
    public static  function redirectToAdminDash():void
    {
        static::redirectToAdminDashResponse()->send();
    }
    public static function redirectToLogInResponse():RedirectResponse
    {
        return Redirect::route('admin.login');
    }
    public static function redirectToLogIn():void
    {
        static::redirectToLogInResponse()->send();
    }
    public static function removeAdminFromSession():void
    {
        if (static::hasAdmin())
            Session::forget('admin');
    }

}
