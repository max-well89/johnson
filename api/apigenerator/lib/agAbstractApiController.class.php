<?php

/**
 * Класс - основа для реализации контроллеров API
 */
abstract class agAbstractApiController extends agAbstractController
{

    // коды глобальных ошибок
    const BAD_FORMAT = 1;
    const BAD_ACTION = 2;
    const FATAL_ERROR = 3;
    const AUTH_FAILED = 4;

    const LIMIT_IP_REQ_PER_TIME = 5;
    const LIMIT_IP_UUID_REQ_PER_TIME = 6;
    const LIMIT_IP_UUID_MSISDN_REQ_PER_TIME = 7;

    const LIMIT_IP_TOKEN_REQ_PER_TIME = 8;
    const LIMIT_TOKEN_REQ_PER_TIME = 9;

    const LIMIT_GET_TOKEN_PER_TIME = 10;
    const LIMIT_ERROR_CHECK_TOKEN_PER_TIME = 11;

    const EXIST_TOKEN_FOR_USER = 12;

    const TOKEN_ALREADY_VERIFY = 13;
    const TOKEN_EXPIRED = 14;
    const TOKEN_BLOCKED = 15;
    const INVALID_TOKEN = 16;

    /** Возвращает переданные пользователем авторизационные данные */
    public function getAuthData()
    {
        if (!isset($_SERVER['PHP_AUTH_USER'])) {
            throw new agGlobalException('auth required', self::AUTH_FAILED);
        }
        return array(
            'user' => $_SERVER['PHP_AUTH_USER'],
            'password' => $_SERVER['PHP_AUTH_PW']
        );
    }

    /** Возвращает не обработанное тело POST запроса */
    protected function getRawPostData()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $fh = fopen('php://input', 'r');
            $postData = '';
            while ($line = fgets($fh)) {
                $postData .= $line;
            }
            return $postData;
        }
        return null;
    }

}
