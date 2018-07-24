<?php

abstract class AbstractFormController extends nomvcBaseController
{

    protected $formId = 'unknown-form';
    protected $action_object;
    protected $path_upload;

    public function run()
    {
        $request = $this->getCurrentUriPart();
        switch ($request) {
            case 'get':
                return $this->processGetForm();    // получение формы
            case 'post':
                return $this->processSaveForm();   // сохранение формы
            case 'delete-confirm':
                return $this->processDeleteConfirmForm(); // подтверждение удаления формы
            case 'delete':
                return $this->processDeleteForm();   // удаление формы
            default:
                throw new nomvcPageNotFoundException('Page not found');
        }
    }

    abstract protected function processGetForm();

    abstract protected function processSaveForm();

    public function sendSms($msisdn, $message)
    {
        $u = 'http://cab.websms.ru/http_in6.asp';
        $login = 'dealerbonus';
        $pass = '1EoVQ75zFG';

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $u);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, 'Http_username=' . urlencode($login) . '&Http_password=' . urlencode($pass) . '&Phone_list=' . $msisdn . '&Message=' . urlencode($message));
        $u = trim(curl_exec($ch));

        //var_dump($u); exit;

        curl_close($ch);
        preg_match("/message_id\s*=\s*[0-9]+/i", $u, $arr_id);
        $id = preg_replace("/message_id\s*=\s*/i", "", @strval($arr_id[0]));

        if ($id)
            return true;
        else
            return false;
    }

    public function generateFileName($ext)
    {
        return sha3(rand(11111111111111, 99999999999999)) . $this->getExtensionFromType($ext);
    }

    public function getExtensionFromType($type, $default = '')
    {
        static $extensions = array(
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'video/mp4' => 'mp4',
            'video/quicktime' => 'mov',
            'application/pdf' => 'pdf',
            'application/msword',
            'application/vnd.oasis.opendocument.text',
            'application/vnd.ms-excel',
            'application/pdf',
            'text/html',
            'text/rtf',
            'text/csv',
            'image/vnd.adobe.photoshop',
            'application/zip',
            'application/vnd.ms-office',
            'application/octet-stream'
        );

        return !$type ? $default : (isset($extensions[$type]) ? '.' . $extensions[$type] : $default);
    }

    public function generatePassword()
    {
        return rand(11111111, 99999999);
    }

    public function base64_to_image($base64_string, $output_file)
    {
        $ifp = fopen($output_file, "wb");
        fwrite($ifp, base64_decode($base64_string));
        fclose($ifp);
        return ($output_file);
    }

    public function makeUrl()
    {
        return '';
    }

    protected function init()
    {
        $this->url_file = '/files/';
        $this->path_file = DIRNAME(__FILE__) . '/../../../web/files/';

        $this->path_upload = "/uploads/";
        $dbHelper = $this->context->getDbHelper();
    }

    protected function setDBContextParameter($var, $val)
    {
        try {
            $query = "select set_parameter(:name, :val);";
            $stmt = $this->context->getDb()->prepare($query);
            $stmt->bindValue('name', $var);
            $stmt->bindValue('val', $val);
            $stmt->execute();
        } catch (exception $e) {
        }
    }

    protected function getJoinRequestData($id_join_request)
    {
        $data = [];

        try {
            $stmt = $this->context->getDb()->prepare('
                  select
                  surname,
                  name,
                  patronymic,
                  TO_CHAR(dt_birthday, \'DD.MM.YYYY\') AS dt_birthday,
                  inn,
                  passport_1,
                  passport_2,
                  TO_CHAR(dt_passport_give, \'DD.MM.YYYY\')  AS dt_passport_give,
                  subdivision_code,
                  whom_give_out,
                  address_register,
                  msisdn,
                  email,
                  msisdn_two,
                  code_tt,
                  city,
                  street,
                  dealer,
                  name_of_sales,
                  trademark,
                  length_of_service_1,
                  length_of_service_2,
                  bank_name,
                  bank_bik,
                  bank_inn,
                  bank_kpp,
                  bank_account,
                  owner_fio,
                  owner_account,
                  owner_card_number,
                  owner_type_card,
                  is_resident,
                  resident_type_doc,
                  resident_doc_description,
                  passport_1_path,
                  passport_2_path,
                  inn_path,
                  blank_1_path,
                  blank_2_path,
                  other_path
            from T_JOIN_REQUEST 
            where id_join_request = :id_join_request');
            $stmt->bindValue('id_join_request', $id_join_request);
            $stmt->execute();

            if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                foreach ($row as $key => $val) {
                    if (!empty($val) && in_array($key, array(
                            'passport_1_path',
                            'passport_2_path',
                            'inn_path',
                            'blank_1_path',
                            'blank_2_path',
                            'other_path'))
                    ) {
                        $row[$key] = $this->url_file . $val;
                    }
                }

                $data = $row;
            }

        } catch (exception $e) {
        }

        return $data;
    }

    /**
     * Функция рендерит кнопки
     *
     * @param string $button тип кнопки, сейчас save, cancel, delete-confirm и delete
     * @param string $id ID записи, удаление которой нужно подтвердить
     * @return mixed
     */
    protected function getButton($button, $id = null)
    {
        switch ($button) {
            case 'save':
                $buttonObj = new nomvcButtonWidget(Context::getInstance()->translate('save'), 'save', array('type' => 'button', 'icon' => 'ok'), array('onclick' => "TableFormActions.postForm('{$this->formId}');"));
                break;
            case 'cancel':
                $buttonObj = new nomvcButtonWidget(Context::getInstance()->translate('cancel'), 'cancel', array('type' => 'button', 'icon' => 'cancel'), array('onclick' => "TableFormActions.closeForm('{$this->formId}');", 'class' => 'btn btn-warning'));
                break;
            //подтверждение удаления
            case 'delete-confirm':
                $buttonObj = new nomvcButtonWidget(Context::getInstance()->translate('delete'), 'delete', array('type' => 'button', 'icon' => 'trash'), array('data-toggle' => 'modal', 'onclick' => "TableFormActions.deleteConfirmObject('{$this->formId}', {$id});", 'class' => 'btn btn-danger'));
                break;
            //удаление
            case 'delete':
                $buttonObj = new nomvcButtonWidget(Context::getInstance()->translate('delete'), 'delete', array('type' => 'button', 'icon' => 'trash'), array('data-toggle' => 'modal', 'onclick' => "TableFormActions.deleteObject('{$this->formId}');", 'class' => 'btn btn-danger'));
                break;
        }

        if (isset($buttonObj))
            return $buttonObj->renderControl(null);
    }

    /** возвращает данные, переданные JS-ом */
    protected function getFormData($formName = null)
    {
        parse_str($this->context->getRequest()->getParameter('formdata', ""), $data);
        if ($formName == null) {
            return $data;
        } else {
            return isset($data[$formName]) ? $data[$formName] : array();
        }
    }

    /**
     * Получает фоты из базы и отдаёт их в массив
     * @param int $id_object ID объекта по которому получаем фоточки
     * @param string $prefix Модуль (уникальный кусочек в названии таблицы) по которому получаем фоточки
     * @param dbHelper $dbHelper Хелпер БД
     * @return mixed
     */
    protected function getPhotos($id_object, $prefix, $dbHelper)
    {
        if (!in_array($prefix, array('about', 'member', 'parking', 'news')))
            return null;

        $dbHelper->addQuery(get_class($this) . '/select-photos', "
            select id_{$prefix}_photo \"id_photo\", mime_type, is_preview
            from t_{$prefix}_photo where id_{$prefix} = :id_object order by id_{$prefix}_photo");

        $dbHelper->addQuery(get_class($this) . '/select-photo', "select file_bin from t_{$prefix}_photo where id_{$prefix}_photo = :id_photo");
        $stmt = $dbHelper->select(get_class($this) . '/select-photos', array("id_object" => $id_object));
        $photos = array();
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $row = array_change_key_case($row);
            if ($row['mime_type']) {
                $res = $dbHelper->selectValue(get_class($this) . '/select-photo', $row);
                if (is_resource($res))
                    $row['file_data'] = stream_get_contents($res);
            }
            $photos[] = $row;
        }
        return $photos;
    }

    /**
     * Сохраняет фотки объекта
     * @param array $values description
     * @param string $prefix description
     * @param dbHelper $dbHelper description
     * @return mixed
     */
    protected function setPhotos($values, $prefix, $dbHelper)
    {
        if (!in_array($prefix, array('about', 'member', 'parking', 'news')))
            return null;

        // получаем фото из БД
        $dbHelper->addQuery(get_class($this) . '/select-photos', "select id_{$prefix}_photo from t_{$prefix}_photo where id_{$prefix} = :id_{$prefix}");
        $stmt = $dbHelper->select(get_class($this) . '/select-photos', array("id_{$prefix}" => $values["id_{$prefix}"]));
        $photo_id_bd = array();
        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $photo_id_bd[$row[0]] = $row[0];
        }
        // формируем запрос на вставку фото
        $dbHelper->addQuery(get_class($this) . '/insert-photos', "insert into t_{$prefix}_photo(id_{$prefix}, mime_type, file_bin, is_preview)
                values(:id_{$prefix}, :mime_type, empty_blob(), :is_preview) returning file_bin into :file_bin");
        $dbHelper->addQuery(get_class($this) . '/update-photos-preview', "update t_{$prefix}_photo set is_preview = :is_preview where id_{$prefix}_photo = :id_photo");

        //var_dump(values["photos"]); exit;
        if (isset($values["photos"]) && is_array($values["photos"])) {
            foreach ($values["photos"] as $photo) {
                if (count($values["photos"]) == 1) {
                    $photo[4] = 1;
                }
                // если фото новое
                if ($photo[0] == "undefined") {
                    $blob_data = fopen($photo[3], 'r');
                    $dbHelper->execute(get_class($this) . '/insert-photos', array(
                        ':id_' . $prefix => $values['id_' . $prefix],
                        ':mime_type' => $photo[1],
                        ':is_preview' => $photo[4],
                        ':is_logo' => isset($photo[5]) ? $photo[5] : 0
                    ), array(), array("file_bin" => $blob_data));
                } else { //существующие фото исключить из поиска, обновить состояние флажка превью
                    if (in_array($photo[0], $photo_id_bd)) {
                        $dbHelper->execute(get_class($this) . '/update-photos-preview', array('id_photo' => $photo[0], 'is_preview' => $photo[4]));
                        if ($prefix == 'shop') {
                            $dbHelper->execute(get_class($this) . '/update-photos-logo', array('id_photo' => $photo[0], 'is_logo' => isset($photo[5]) ? $photo[5] : 0));
                        }
                        //чистим массив из таблицы, чтобы остались только записи под удаление
                        unset($photo_id_bd[$photo[0]]);
                    }
                }
            }
        }

        //оставшиеся в массиве $photo_id_bd записи соответствуют удалённым фото
        $dbHelper->addQuery(get_class($this) . '/delete-photos', "delete from t_{$prefix}_photo where id_{$prefix}_photo = :id_photo");
        foreach ($photo_id_bd as $id_photo) {
            $dbHelper->execute(get_class($this) . '/delete-photos', array(':id_photo' => $id_photo));
        }
    }

}
