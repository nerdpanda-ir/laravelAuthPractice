<?php namespace App\Lib ;?>
<?php
    use App\Models\AdminAccessToken;
    use Illuminate\Support\Carbon;
?>
<?php
final class AdminAccessTokenController
{
    private static string $token;
    private static AdminAccessToken $adminAccessToken;
    private static int $adminId;
    private static Carbon $expiredAt;
    public static function make(int $adminId , null|Carbon $expiredAt=null):bool
    {
        self::$adminId = $adminId;
        self::$expiredAt = Carbon::now();
        self::makeAdminAccessTokenInstance();
        self::makeUniqueToken();
        self::setExpiredAt($expiredAt);
        return self::applyTokenToDb();
    }
    private static function generateToken():void
    {
        self::$token = sha1(microtime());
    }
    private static function tokenIsNotExist():bool
    {
        return !self::$adminAccessToken->isExistToken(self::$token);
    }
    private static function makeAdminAccessTokenInstance():void
    {
        self::$adminAccessToken = new AdminAccessToken();
    }
    public static function getToken():string|null
    {
        if (isset(self::$token))
            return self::$token;
        return null;
    }
    private static function makeUniqueToken():void
    {
        do{
            self::generateToken();
        }while(!self::tokenIsNotExist());
    }
    private static function setExpiredAt(null|Carbon $expiredAt=null):void
    {
        if (is_null($expiredAt))
            self::$expiredAt->addSeconds(env('ADMIN_ACCESS_TOKEN_LIFE_TIME'));
        else
            self::$expiredAt=$expiredAt;
    }
    private static function applyTokenToDb():bool
    {
        self::$adminAccessToken->token = self::$token;
        self::$adminAccessToken->admin_id = self::$adminId;
        self::$adminAccessToken->created_at =Carbon::now();
        self::$adminAccessToken->expired_at = self::$expiredAt;
        return self::$adminAccessToken->save();
    }
}
