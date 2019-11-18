<?php
namespace  app\classes\responses;

class AuthResponse
{
    const RESULT_OK = "ok";
    const RESULT_NOT_REGISTERED = "not_registered";
    const NOT_AUTHENTICATED = "not_authenticated";
    const RESULT_USER_EXISTS = "result_user_exists";
    const RESULT_REGISTERED = "result_registered" ;

    var $result;
    var $token;
}