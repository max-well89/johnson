<?php

abstract class AbstractAction extends agAbstractAction
{

    protected $dbHelper;

    protected $code_time_remained;
    protected $code_lifetime;

    protected $web_path;
    protected $media_image_path;

    protected $transferredParams;

    protected $emails_super_admin = ['max-well98@mail.ru'];
    protected $email_from = 'info@ias.su';

    public function getAction()
    {
        return preg_replace('/_action$/imu', '', agAbstractController::fromCamelCase(get_class($this)));
    }

    public function getAccessRoles()
    {
        return array('client');
    }

    public function makeUrlForImageDb($objType, $id_file)
    {
        return $this->url_image . 'obj=' . $objType . '&id=' . $id_file;
    }

    public function init()
    {
        $this->dbHelper = $this->context->getDbHelper();

        $this->dbHelper->addQuery($this->getAction() . '/get_member', '
                SELECT *
                from t_member
                where login = :msisdn
        ');


        // $this->url_image = 'http://'.$_SERVER['HTTP_HOST'].'/api/img.php?';
//        $this->url_image_preview = 'http://'.$_SERVER['HTTP_HOST'].'/images/preview/';

//        $this->url_video = 'http://'.$_SERVER['HTTP_HOST'].'/videos/';
//        $this->url_video_preview = 'http://'.$_SERVER['HTTP_HOST'].'/videos/preview/';

//        $this->url_file = 'http://'.$_SERVER['HTTP_HOST'].'/files/';
//        $this->url_file_preview = 'http://'.$_SERVER['HTTP_HOST'].'/files/preview/';

//        $this->path_image = DIRNAME(__FILE__).'/../../images/';
//        $this->path_image_preview = DIRNAME(__FILE__).'/../../images/preview/';

//        $this->path_video = DIRNAME(__FILE__).'/../../videos/';
//        $this->path_video_preview = DIRNAME(__FILE__).'/../../videos/preview/';
//
//        $this->path_file = DIRNAME(__FILE__).'/../../files/';
//        $this->path_file_preview = DIRNAME(__FILE__).'/../../files/preview/';
//        


//        $this->code_lifetime = $this->context->getConfigVal('code_lifetime');
//
//        $this->dbHelper->addQuery($this->getAction().'/set_context_param', 'begin project_context.set_parameter(:param, :val); end;');
//
        $this->token_life_time = $this->context->getConfigVal('token_life_time');

        $this->dbHelper->addQuery($this->getAction() . '/auth', '
            select
            tm.*,
            tl.name as default_language
            from t_member tm
            left join t_language tl on tm.id_language = tl.id_language
            where tm.login = :login
            and tm.passwd = :password
            and tm.id_status = 1
        ');

        $this->dbHelper->addQuery($this->getAction() . '/auth_by_id', '
            select *
            from t_member tm
            where tm.id_member = :id_member
            and tm.id_status = 1
        ');

        $this->dbHelper->addQuery($this->getAction() . '/check_token_exist', "
        select 
        id_member,
        (case when dt > (current_timestamp - interval '{$this->token_life_time} min') then 0 else 1 end) as has_expired 
        from t_security_log
        where token = :token
        and (
            login = :login
            or 
            :login is null
        )
        limit 1
        ");

        $this->dbHelper->addQuery($this->getAction() . '/insert_exist', "
        insert into t_security_log(id_member, login, net, token)
        values (:id_member, :login, :net, :token)
        returning id_security_log
        ");

        /************************ERRORS**********/

        //$this->addParameter('uuid', new agStringValidator(array('required' => true)), 'Универсальный идентификатор устройства');

        $this->registerActionException(Errors::FAIL, 'Ошибка');
        $this->registerActionException(Errors::NO_DATA_FOUND, 'Данные не найдены');
        $this->registerActionException(Errors::MEMBER_NOT_FOUND, 'Пользователь не найден');
        $this->registerActionException(Errors::TOKEN_EXPIRED, 'Истекло время жизни токена');
    }

    protected function generateToken($id_member, $login = false)
    {
        $token = hash('sha512', $this->crypto_rand_secure(1111111111, 9999999999) . strtotime('now'));

        if ($id = $this->dbHelper->selectValue($this->getAction() . '/insert_exist', array(
            'id_member' => $id_member,
            'login' => $login,
            'net' => (int)$this->getIp(),
            'token' => $token
        ))) {
            return $token;
        }

        return false;
    }

    protected function authByToken()
    {
        if ($member = $this->dbHelper->selectRow($this->getAction() . '/check_token_exist', array(
            'token' => $this->getValue('token'),
            'login' => $this->getValue('login')
        ))) {
            if ($member['has_expired'] == 1) {
                $this->throwActionException(Errors::TOKEN_EXPIRED);
            }

            return $this->dbHelper->selectRow($this->getAction() . '/auth_by_id', array(
                'id_member' => $member['id_member']
            ));
        }

        return false;
    }

    public function generatePassword()
    {
        return substr(sha3($this->crypto_rand_secure(1111111111, 9999999999)), 0, 6);
    }

    public static function crypto_rand_secure($min = 0, $max = 9)
    {
        $range = $max - $min;
        if ($range == 0) return $min; // not so random...
        $log = log($range, 2);
        $bytes = (int)($log / 8) + 1; // length in bytes
        $bits = (int)$log + 1; // length in bits
        $filter = (int)(1 << $bits) - 1; // set all lower bits to 1
        do {
            $rnd = hexdec(bin2hex(openssl_random_pseudo_bytes($bytes, $s)));
            $rnd = $rnd & $filter; // discard irrelevant bits
        } while ($rnd >= $range);
        return $min + $rnd;
    }

    public function auth()
    {
        $member = $this->dbHelper->selectRow($this->getAction() . '/auth', array(
            'login' => $this->getValue('login'),
            'password' => $this->getValue('password')
        ));

        if (empty($member['id_member'])) {
            $this->throwActionException(Errors::MEMBER_NOT_FOUND);
        }

        return $member;
    }


    protected function getMember()
    {
        $user = $this->dbHelper->selectRow($this->getAction() . '/get_member', array('msisdn' => $this->getValue('msisdn')));
        return $user;
    }


    public final function getGroupsDescription()
    {
        return array(
            'service' => 'Для сервисного приложения',
            'client' => 'Для клиентского приложения'
        );
    }

    private function RandomString()
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randstring = '';
        for ($i = 0; $i < 10; $i++) {
            $randstring = $characters[sfMoreSecure::crypto_rand_secure(0, strlen($characters) - 1)];
        }
        return $randstring;
    }

    public function generateFileName($ext)
    {
        return sha3($this->RandomString() . sfMoreSecure::crypto_rand_secure(111111111111, 999999999999999)) . $this->getExtensionFromType($ext);
    }

    public function base64_to_image($base64_string, $output_file)
    {
        $ifp = fopen($output_file, "wb");
        fwrite($ifp, base64_decode($base64_string));
        fclose($ifp);
        return ($output_file);
    }

    public function getExtensionFromType($type, $default = '')
    {
        static $extensions = array(
            'image/bmp' => 'bmp',
            'image/cewavelet' => 'wif',
            'image/cis-cod' => 'cod',
            'image/fif' => 'fif',
            'image/gif' => 'gif',
            'image/ief' => 'ief',
            'image/jp2' => 'jp2',
            'image/jpeg' => 'jpg',
            'image/jpm' => 'jpm',
            'image/jpx' => 'jpf',
            'image/pict' => 'pic',
            'image/pjpeg' => 'jpg',
            'image/png' => 'png',
            'image/targa' => 'tga',
            'image/tiff' => 'tif',
            'image/vn-svf' => 'svf',
            'image/vnd.dgn' => 'dgn',
            'image/vnd.djvu' => 'djvu',
            'image/vnd.dwg' => 'dwg',
            'image/vnd.glocalgraphics.pgb' => 'pgb',
            'image/vnd.microsoft.icon' => 'ico',
            'image/vnd.ms-modi' => 'mdi',
            'image/vnd.sealed.png' => 'spng',
            'image/vnd.sealedmedia.softseal.gif' => 'sgif',
            'image/vnd.sealedmedia.softseal.jpg' => 'sjpg',
            'image/vnd.wap.wbmp' => 'wbmp',
            'image/x-bmp' => 'bmp',
            'image/x-cmu-raster' => 'ras',
            'image/x-freehand' => 'fh4',
            'image/x-ms-bmp' => 'bmp',
            'image/x-png' => 'png',
            'image/x-portable-anymap' => 'pnm',
            'image/x-portable-bitmap' => 'pbm',
            'image/x-portable-graymap' => 'pgm',
            'image/x-portable-pixmap' => 'ppm',
            'image/x-rgb' => 'rgb',
            'image/x-xbitmap' => 'xbm',
            'image/x-xpixmap' => 'xpm',
            'image/x-xwindowdump' => 'xwd'
        );

        return !$type ? $default : (isset($extensions[$type]) ? '.' . $extensions[$type] : $default);
    }

    public function getTransferredParams()
    {
        return $this->transferredParams;
    }

    /**
     * Валидация входных параметров
     *
     * $params    входные параметры экшена
     */
    public function validate($params)
    {
        $values = array();

        $this->transferredParams = $params;

        foreach ($this->parameters as $name => $conf) {
            try {
                $values[$name] = $conf['validator']->clean(isset($params[$name]) ? $params[$name] : null);
            } catch (agInvalidValueException $ex) {
                // если связанный код ошибки не установлен - выбрасываем дефолтовое исключение
                if ($conf['errorcode'] && isset($this->exceptions[$conf['errorcode']])) {
                    $this->throwActionException($conf['errorcode']);
                } else {
                    throw new agActionException(str_replace('Invalid value', 'некорректное значение', sprintf('Ошибка в поле "%s": %s', $conf['description'], $ex->getMessage())), Errors::BAD_PARAMETER, null, $name);
                }
            }
        }
        $this->values = $values;
    }
}
