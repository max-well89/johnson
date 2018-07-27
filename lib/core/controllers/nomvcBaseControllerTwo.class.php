<?php

class nomvcBaseControllerTwo extends nomvcBaseController
{

    protected $baseUrl;

    public function run()
    {
        parent::run();
    }


//    protected function getJoinRequestData($id_join_request){
//        $data = [];
//
//        try{
//            $stmt = $this->context->getDb()->prepare('
//                  select
//                  surname,
//                  name,
//                  patronymic,
//                  TO_CHAR(dt_birthday, \'DD.MM.YYYY\') AS dt_birthday,
//                  inn,
//                  passport_1,
//                  passport_2,
//                  TO_CHAR(dt_passport_give, \'DD.MM.YYYY\')  AS dt_passport_give,
//                  subdivision_code,
//                  whom_give_out,
//                  address_register,
//                  msisdn,
//                  email,
//                  msisdn_two,
//                  code_tt,
//                  city,
//                  street,
//                  dealer,
//                  name_of_sales,
//                  trademark,
//                  length_of_service_1,
//                  length_of_service_2,
//                  bank_name,
//                  bank_bik,
//                  bank_inn,
//                  bank_kpp,
//                  bank_account,
//                  owner_fio,
//                  owner_account,
//                  owner_card_number,
//                  owner_type_card,
//                  is_resident,
//                  resident_type_doc,
//                  resident_doc_description,
//                  passport_1_path,
//                  passport_2_path,
//                  inn_path,
//                  blank_1_path,
//                  blank_2_path,
//                  other_path
//            from T_JOIN_REQUEST
//            where id_join_request = :id_join_request');
//            $stmt->bindValue('id_join_request', $id_join_request);
//            $stmt->execute();
//
//            if ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
//                foreach ($row as $key => $val) {
//                    if (!empty($val) && in_array($key, array(
//                            'passport_1_path',
//                            'passport_2_path',
//                            'inn_path',
//                            'blank_1_path',
//                            'blank_2_path',
//                            'other_path'))
//                    ){
//                        $row[$key] = $this->url_file.$val;
//                    }
//                }
//
//                $data = $row;
//            }
//
//        }
//        catch(exception $e){}
//
//        return $data;
//    }
//
//    protected function updateFilePathInfo($id_join_request, $parameter, $value){
//        $sql = "update t_join_request set {$parameter} = :value where id_join_request = :id_join_request";
//
//        try
//        {
//            $stmt = $this->context->getDb()->prepare($sql);
//            $stmt->bindValue('id_join_request', $id_join_request);
//            $stmt->bindValue("value", $value);
//            $stmt->execute();
//            //var_dump($stmt->errorInfo()); exit;
//        }
//        catch (exeption $e){}
//
//        return true;
//    }
//
//
//    public function sendSms($msisdn, $message) {
//        $u = 'http://cab.websms.ru/http_in6.asp';
//        $login = 'dealerbonus';
//        $pass = '1EoVQ75zFG';
//
//        $ch = curl_init();
//        curl_setopt($ch, CURLOPT_URL, $u);
//        curl_setopt($ch, CURLOPT_HEADER, 0);
//        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
//        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//        curl_setopt($ch, CURLOPT_POST, 1);
//        curl_setopt($ch, CURLOPT_POSTFIELDS, 'Http_username='.urlencode($login).'&Http_password='.urlencode($pass).'&Phone_list='.$msisdn.'&Message='.urlencode($message));
//        $u = trim(curl_exec($ch));
//
//        //var_dump($u); exit;
//
//        curl_close($ch);
//        preg_match("/message_id\s*=\s*[0-9]+/i", $u, $arr_id );
//        $id = preg_replace("/message_id\s*=\s*/i", "", @strval($arr_id[0]));
//
//        if ($id)
//            return true;
//        else
//            return  false;
//    }
//
//    public function sendEmail($to = array(), $subject, $message) {
//        $mail = new HtmlMimeMail();
//        $mail->send_ex($to, null, null, 'emg@ias.su', $subject, $message);
//        return true;
//
//    }
//
//    protected function setDBContextParameter($var, $val) {
//        $dbHelper = $this->context->getDbHelper();
//        $dbHelper->addQuery(get_class($this) . '/set-context', 'begin project_context.set_parameter(:var, :val); end;');
//        $dbHelper->execute(get_class($this) . '/set-context', array('var' => $var, 'val' => $val));
//    }

    public function base64_to_image($base64_string, $output_file)
    {
        $ifp = fopen($output_file, "wb");
        fwrite($ifp, base64_decode($base64_string));
        fclose($ifp);
        return ($output_file);
    }

//    public function generatePassword(){
//        return substr(sha3($this->crypto_rand_secure(1111111111, 9999999999)), 0, 6);
//    }
//
//    protected function checkUniqueCarNumberCode($car_number, $car_code){
//        $this->dbHelper->addQuery(get_class($this) . '/check_unique_car', "
//            select *
//            from t_member_car
//            where upper(car_number) = upper(:car_number)
//            and car_code = :car_code
//            "
//        );
//
//        if($this->dbHelper->selectRow(get_class($this) . '/check_unique_car', array(
//            'car_number' => $car_number,
//            'car_code' => $car_code
//        ), PDO::FETCH_ASSOC)){
//            return false;
//        }
//
//        return true;
//    }
//
//    protected function sendPassword($id_member, $msisdn, $password, $is_restore = false){
//        //предпроверка
//        $this->dbHelper->addQuery(get_class($this).'/get_cnt_last_code', "
//        select
//        count(*) as cnt,
//        ABS((max(dt_send) - sysdate)*24*60*60) as code_time_remained
//        from T_SMS_OUT
//        where msisdn = :msisdn
//        and dt_send > sysdate - (:code_time_remained/24/60/60)
//        ");
//
//        $row = $this->dbHelper->selectRow(get_class($this).'/get_cnt_last_code', array(
//            'msisdn' => $msisdn,
//            'code_time_remained' => 120
//        ));
//
//        if (isset($row['cnt']) && $row['cnt'] > 0){
//            return false;
//        }
//
//        $message = "Ваш пароль для входа: $password";
//
//        $stmt = $this->context->getDb()->prepare('begin send_sms(:id_member, :msisdn, :message, :password, :is_restore); end;');
//        $stmt->bindValue('id_member', $id_member);
//        $stmt->bindValue('msisdn', $msisdn);
//        $stmt->bindValue('message', $message);
//        $stmt->bindValue('password', $password);
//        $stmt->bindValue('is_restore', $is_restore);
//
//        return $stmt->execute();
//    }
//
//    public function updateMemberPassword($id_member, $password, $has_verify = false){
//        $this->dbHelper->addQuery(get_class($this).'/update_member_password', '
//            update t_member
//            set passwd = nvl(:passwd, passwd),
//            dt_verify = decode(:has_verify, 1, sysdate, 0, null, dt_verify),
//            has_verify = nvl(:has_verify, has_verify)
//            where id_member = :id_member
//        ');
//
//        return $this->dbHelper->execute(get_class($this).'/update_member_password', array(
//            'id_member' => $id_member,
//            'passwd' => $password,
//            'has_verify' => $has_verify
//        ));
//    }
//
//    protected function checkUniqueMemberParam($id_member = false, $param, $value){
//        $this->dbHelper->addQuery(get_class($this) . "/check_member_param_$param", "
//            select *
//            from t_member
//            where $param = :$param
//            and (
//              id_member != :id_member
//              or
//              :id_member is null
//            )
//            ");
//        if($this->dbHelper->selectRow(get_class($this) . "/check_member_param_$param", array(
//            'id_member' => $id_member,
//            ":$param" => $value
//        ), PDO::FETCH_ASSOC)){
//            return false;
//        }
//
//        return true;
//    }

//    public static function crypto_rand_secure($min = 0, $max = 9) {
//        $range = $max - $min;
//        if ($range == 0) return $min; // not so random...
//        $log = log($range, 2);
//        $bytes = (int) ($log / 8) + 1; // length in bytes
//        $bits = (int) $log + 1; // length in bits
//        $filter = (int) (1 << $bits) - 1; // set all lower bits to 1
//        do {
//            $rnd = hexdec(bin2hex(openssl_random_pseudo_bytes($bytes, $s)));
//            $rnd = $rnd & $filter; // discard irrelevant bits
//        } while ($rnd >= $range);
//        return $min + $rnd;
//    }
//
//    public function generateFileName($ext){
//        return sha3($this->crypto_rand_secure(11111111111111, 99999999999999)).$this->getExtensionFromType($ext);
//    }

    public function getExtensionFromType($type, $default = '')
    {
        static $extensions = array(
            'image/jpeg' => 'jpg',
            'image/jpg' => 'jpg',
            'image/png' => 'png',
            'video/mp4' => 'mp4',
            'video/mpeg' => 'mp4',
            'video/quicktime' => 'mov',

            'application/pdf' => 'pdf',
            'application/msword' => 'doc',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => 'docx',
            'text/plain' => 'txt',

            'application/vnd.oasis.opendocument.text',
            'application/vnd.ms-excel',
            'application/pdf',
            'text/html',
            'text/rtf',
            'text/csv',
            'image/vnd.adobe.photoshop',
            'application/zip',
            'application/vnd.ms-office'
        );

        return !$type ? $default : (isset($extensions[$type]) ? '.' . $extensions[$type] : $default);
    }

    function cut_paragraph($string, $your_desired_width = 100)
    {
        //$string = strip_tags($string);
        $string = substr($string, 0, $your_desired_width);
        $string = rtrim($string, "!,.-");
        $string = substr($string, 0, strrpos($string, ' '));

        return $string;
    }


//    protected function updateMemberSessionData(){
//        $user = $this->context->getUser();
//
//        try {
//            $this->dbHelper->addQuery(get_class($this).'/select_member_info', 'select * from T_MEMBER where id_member = :id_member');
//            $userInfo = $this->dbHelper->selectRow(get_class($this).'/select_member_info', array('id_member' => $user->getAttribute('id_member')));
//
//            if ($userInfo)
//                foreach ($userInfo as $key => $val) {
//                    $user->setAttribute(strtolower($key), $val);
//                }
//
//            $user->setAttribute('member_photo_default', $this->getMemberPhotoDefault($userInfo['id_member']));
//        }
//        catch(exception $e){}
//    }

    protected function init()
    {
        $this->translateHelper = $this->context->getTranslateHelper();
        $this->dbHelper = $this->context->getDbHelper();

        $this->baseUrl = '/admin';

        $this->url_file = '/files/';
        $this->path_file = NOMVC_BASEDIR . '/web/files/';
//        $this->dbHelper->addQuery(get_class($this).'/save_member', '
//                    insert into t_member (surname, name, patronymic, msisdn, email)
//                    values(:surname, :name, :patronymic, :msisdn, :email) returning id_member into :id_member');
//
//        $this->dbHelper->addQuery(get_class($this).'/save_member_role', '
//                    insert into t_member_role (id_member, id_role) values(:id_member, :id_role)');
//
//        $this->dbHelper->addQuery(get_class($this).'/save_car', '
//                    insert into t_member_car (id_member, car_number, car_code, car_brand, note)
//                    values(:id_member, upper(:car_number), upper(:car_code), :car_brand, :note)');
//
//        $this->dbHelper->addQuery(get_class($this).'/update_car_note', '
//                    update t_member_car
//                    set note = :note
//                    where id_member = :id_member
//                    and id_member_car = :id_member_car
//        ');
//
//        $this->dbHelper->addQuery(get_class($this).'/check_car_member_cnt', '
//                    select count(*) as cnt
//                    from t_member_car
//                    where id_member = :id_member
//                    and
//                    (
//                      id_member_car != :id_member_car
//                      or :id_member_car is null
//                    )
//        ');
//
//        $this->dbHelper->addQuery(get_class($this).'/delete_car_member', '
//                    delete from t_member_car where id_member = :id_member and id_member_car = :id_member_car
//        ');
    }

//    protected function includeCarPopups($data){
//        $data['add_car_form'] = new MemberCarForm($this->context, array('method' => 'post', 'action' => '/profile/car'));
//        $data['edit_car_form'] = new MemberCarEditForm($this->context, array('method' => 'post', 'action' => '/profile/car'));
//        $data['delete_car_form'] = new MemberCarDeleteForm($this->context, array('method' => 'post', 'action' => '/profile/car'));
//
//        $data['with_car_popups'] = true;
//
//        return $data;
//    }

    protected function getData()
    {
        $data = [];
        $data['user'] = $this->context->getUser();

        $loginForm = new LoginForm($this->context, array('method' => 'post', 'action' => '/login'));
        $loginForm->init();
        $data['login_form'] = $loginForm;

        $restoreForm = new RestoreForm($this->context, array('method' => 'post', 'action' => '/restore'));
        $restoreForm->init();
        $data['restore_form'] = $restoreForm;

        $data['context'] = $this->context;
//
//        $data['member_photo_default'] = $this->context->getUser()->getAttribute('member_photo_default');
//
        //var_dump($data['member_photo_default']['file_bin']); exit;

        return $data;
    }

//    protected function getParkingList(){
//        $data = [];
//
//        try {
//            $stmt = $this->context->getDb()->prepare('
//            select vp.*
//            from V_PARKING vp
//            where vp.id_status = 1
//            order by vp.id_parking
//            ');
//            $stmt->execute();
//
//            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
//                $data[] = $row;
//            }
//
//        } catch(exception $e){}
//
//        return $data;
//    }
//
//    protected function getMemberData(){
//        $data = [];
//
//        try {
//            $stmt = $this->context->getDb()->prepare('
//            select
//            tm.*,
//            vs.name as sex
//            from T_MEMBER tm
//            left join V_SEX vs on tm.id_sex = vs.id_sex
//            where tm.id_member = :id_member
//            ');
//            $stmt->bindValue('id_member', $this->context->getUser()->getAttribute('id_member'));
//            $stmt->execute();
//
//            $row = $stmt->fetch(PDO::FETCH_ASSOC);
//
//            $data = $row;
//
//            if (!empty($data['id_member'])){
//                //$data['images'] = $this->getMemberImages($data['id_member']);
//                $data['photo_default'] = $this->getMemberPhotoDefault($data['id_member']);
//            }
//        } catch(exception $e){}
//
//        return $data;
//    }
//
//    private function getMemberPhotoDefault($id_member){
//        $data = null;
//
//        try {
//            $stmt = $this->context->getDb()->prepare('
//            SELECT *
//            FROM
//            (
//                select
//                tmp.*
//                from T_MEMBER_PHOTO tmp
//                where tmp.id_member = :id_member
//                order by tmp.id_member_photo desc
//            )
//            where rownum <= 1
//            ');
//            $stmt->bindValue('id_member', $id_member);
//            $stmt->execute();
//
//            $row = $stmt->fetch(PDO::FETCH_ASSOC);
//            if (!empty($row['id_member_photo'])) {
//                $row['file_bin'] = $row['file_bin'] ? 'data:' . $row['mime_type'] . ';base64,' . base64_encode(stream_get_contents($row['file_bin'])) : null;
//                $data = $row;
//            }
//        } catch(exception $e){}
//
//        return $data;
//    }
//
//    private function getMemberImages($id_member){
//        $data = [];
//
//        try {
//            $stmt = $this->context->getDb()->prepare('
//            select
//            tmp.*
//            from T_MEMBER_PHOTO tmp
//            where tmp.id_member = :id_member
//            ');
//            $stmt->bindValue('id_member', $id_member);
//            $stmt->execute();
//
//            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
//                $row['file_bin'] = $row['file_bin']?'data:'.$row['mime_type'].';base64,'.base64_encode(stream_get_contents($row['file_bin'])):null;
//
//                $data[] = $row;
//            }
//        } catch(exception $e){}
//
//        return $data;
//    }
//
//    protected function getParkingData($id_parking = false){
//        $data = [];
//
//        try {
//            $stmt = $this->context->getDb()->prepare('
//            select *
//            from (
//                select vp.*
//                from V_PARKING vp
//                where vp.id_status = 1
//                and
//                (
//                    vp.id_parking = :id_parking
//                    or
//                    :id_parking  is null
//                )
//
//                order by vp.id_parking
//            )
//            where rownum <= 1
//            ');
//            $stmt->bindValue('id_parking', $id_parking);
//            $stmt->execute();
//
//            $row = $stmt->fetch(PDO::FETCH_ASSOC);
//
//            $data = $row;
//        } catch(exception $e){}
//
//        if (!empty($data['id_parking'])){
//            try {
//                $data['images'] = $this->getParkingImagesOne($data['id_parking']);
//            }
//            catch(exception $e){}
//        }
//
//        return $data;
//    }
//
//    protected function getParkingImagesOne($id_parking){
//        $data = [];
//
//        try {
//            $stmt = $this->context->getDb()->prepare('
//            select
//            tpp.*
//            from V_PARKING vp
//            inner join T_PARKING_PHOTO tpp on vp.id_parking = tpp.id_parking
//            where vp.id_status = 1
//            and vp.id_parking = :id_parking
//            ');
//            $stmt->bindValue('id_parking', $id_parking);
//            $stmt->execute();
//
//            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
//                $row['file_bin'] = $row['file_bin']?'data:'.$row['mime_type'].';base64,'.base64_encode(stream_get_contents($row['file_bin'])):null;
//
//                $data[] = $row;
//            }
//        } catch(exception $e){}
//        //var_dump($id_parking, $data); exit;
//        return $data;
//    }

    protected function getFormData($formId)
    {
        $data = [];

        if (isset($_POST[$formId])) {
            $data = $_POST[$formId];
        }

        return $data;
    }
}