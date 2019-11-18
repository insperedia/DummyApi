<?php

namespace app\models;

use app\classes\Storage;
use Exception;
use Yii;
use yii\base\Model;
use yii\web\IdentityInterface;


class User extends Model implements IdentityInterface
{
    const JWT_SECRET_ASSIGNATURE = 'da39a3ee5e6b4b0d3255bfef95601890afd80709';

    var $id;
    var $username;
    var $password;
    var $token;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%users}}';
    }


    public static function findByUsername($username)
    {
        foreach (Storage::$users as $user)
        {
            if ($user['username'] == $username) {
                $userObj = new self();
                $userObj->load($user, "");
                return $userObj;
            }

        }
        return null;
    }

    public static function GetByAuthToken()
    {
        if (Yii::$app->request->headers->get("authorization")) {
            $bearer_token = Yii::$app->request->headers->get("authorization");
            $token = str_replace("Bearer ", "", $bearer_token);
            return User::findIdentityByAccessToken($token);
        } else {
            throw new Exception("function GetByAuthToken failed");
        }
    }


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['username', 'password', 'token', 'id'], 'safe'],

        ];
    }

    /**
     * {@inheritdoc}
     */

    public function fields()
    {
        $fields = parent::fields();
        unset($fields['auth_key'], $fields['password_hash']);

        return $fields;
    }

    /**
     * Finds an identity by the given ID.
     * @param string|int $id the ID to be looked for
     * @return IdentityInterface|null the identity object that matches the given ID.
     * Null should be returned if such an identity cannot be found
     * or the identity is not in an active state (disabled, deleted, etc.)
     */
    public static function findIdentity($id)
    {

        return isset(Storage::$users[$id]) ? Storage::$users[$id] : null;
    }

    /**
     * Finds an identity by the given token.
     * @param mixed $token the token to be looked for
     * @param mixed $type the type of the token. The value of this parameter depends on the implementation.
     * For example, [[\yii\filters\auth\HttpBearerAuth]] will set this parameter to be `yii\filters\auth\HttpBearerAuth`.
     * @return IdentityInterface|null the identity object that matches the given token.
     * Null should be returned if such an identity cannot be found
     * or the identity is not in an active state (disabled, deleted, etc.)
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
       foreach (Storage::$users as $user)
       {
           if ($user['token'] == $token) {
               $userObj = new self();
               $userObj->load($user, "");
               return $userObj;
           }

       }
       return null;
    }

    /**
     * Returns an ID that can uniquely identify a user identity.
     * @return string|int an ID that uniquely identifies a user identity.
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Returns a key that can be used to check the validity of a given identity ID.
     *
     * The key should be unique for each individual user, and should be persistent
     * so that it can be used to check the validity of the user identity.
     *
     * The space of such keys should be big enough to defeat potential identity attacks.
     *
     * This is required if [[User::enableAutoLogin]] is enabled. The returned key will be stored on the
     * client side as a cookie and will be used to authenticate user even if PHP session has been expired.
     *
     * Make sure to invalidate earlier issued authKeys when you implement force user logout, password change and
     * other scenarios, that require forceful access revocation for old sessions.
     *
     * @return string a key that is used to check the validity of a given identity ID.
     * @see validateAuthKey()
     */
    public function getAuthKey()
    {
       return false;
    }

    /**
     * Validates the given auth key.
     *
     * This is required if [[User::enableAutoLogin]] is enabled.
     * @param string $authKey the given auth key
     * @return bool whether the given auth key is valid.
     * @see getAuthKey()
     */
    public function validateAuthKey($authKey)
    {
        return false;
    }


}
