<?php

class JoinRequestDenyFormController extends AbstractFormController {
    /** @var int ID пользователя */
    private $id_object = 0;
    /** @var array данные объекта */
    private $member;
    /** @var dbHelper экземпляр хелпера данных */
    private $dbHelper;
    /** @var string название формы */
    protected $formId = "join-request";

    protected function init() {
        $this->dbHelper = $this->context->getDbHelper();
        $this->id_object = $this->context->getRequest()->getParameter('id');
    }

    /** Открытие формы */
    protected function processGetForm() {
        $buttons = array();
        //$buttons[] = $this->getButton('save');

        $buttons[] = $this->getButton('cancel');

        $buttonNo = new nomvcButtonWidget('Отклонить', 'access', array('type' => 'button', 'icon' => 'glyphicon glyphicon-ban-circle'), array('onclick' => "TableFormActions.postForm('{$this->formId}-deny');", 'class' => 'btn btn-danger'));
        $buttons[] = $buttonNo->renderControl(null);

        $buttonYes =  new nomvcButtonWidget('Одобрить', 'save', array('type' => 'button', 'icon' => 'ok'), array('onclick' => "TableFormActions.postForm('{$this->formId}');"));
        $buttons[] = $buttonYes->renderControl(null);

        $this->object = $this->getObject();

        $form = new JoinRequestForm($this->context, array('id' => $this->formId));
        $form->bind($this->object);
        $formTitle = 'Заявка на участие';

        return json_encode(array(
            'title' => $formTitle,
            'form' => $form->render($this->formId),
            'buttons' => implode('', $buttons)
        ));
    }

    /** Сохранение формы, здесь вставка и редактирование */
    protected function processSaveForm() {
        $form = new JoinRequestForm($this->context, array('id' => $this->formId));

        $values = $this->getFormData($this->formId);
            
        var_dump($values); exit;
        
        if ($form->validate()) {
            $values = $form->getValues();
            
            //проверка пароля
            $errors = array();
            if($errors = $this->checkPassword($values)){
                return json_encode(array(
                    'result' => 'error',
                    'fields' => $errors,
                    'message' => ''));
            }
            /*
            if(empty($values["login"])) {
                $errors["login"] = "required";
                
                return json_encode(array(
                    'result' => 'error',
                    'fields' => $errors,
                    'message' => ''));
            }
            */

            //проверка на уникальность email-a
            if($errors = $this->checkUnique('login', $values)){
                return json_encode(array(
                    'result' => 'error',
                    'fields' => $errors,
                    'message' => ''));
            }

            //проверка на уникальность login
            if($errors = $this->checkUnique('msisdn', $values)){
                return json_encode(array(
                    'result' => 'error',
                    'fields' => $errors,
                    'message' => ''));
            }

            //проверка на уникальность email-a
            if($errors = $this->checkUnique('email', $values)){
                return json_encode(array(
                    'result' => 'error',
                    'fields' => $errors,
                    'message' => ''));
            }

            //подготовка массива данных
            $values_prepared = array();
            foreach ($values as $key => $value) {
                if(!is_array($value)) $values_prepared[$key] = $value;
            }

            //if ($this->context->getUser()->getAttribute('id_restaurant') != null){
            //    $values_prepared['id_restaurant'] = $this->context->getUser()->getAttribute('id_restaurant');
            //}             

            if(empty($values["id_member"])){
                //данные для вставки, новая новость
                $this->dbHelper->addQuery(get_class($this) . '/insert-member', "
                    insert into t_member(
                        name,
                        surname,
                        patronymic,
                        login, 
                        passwd, 
                        email, 
                        msisdn,
                        id_sex,
                        dt_birthday,
                        id_status
                    )
                    values (
                        :name,
                        :surname,
                        :patronymic,                     
                        :login, 
                        :passwd, 
                        :email, 
                        :msisdn,
                        :id_sex,
                        :dt_birthday,
                        :id_status
                    )
                    returning id_member into :id_member");
                $this->dbHelper->execute(get_class($this) . '/insert-member', $values_prepared, array(':id_member' => &$this->id_member));
                $values['id_member'] = $this->id_member;
            } else{
                //не пустой пароль
                $pass_str =  "";
                if(!empty($values_prepared["passwd"]))
                    $pass_str = "passwd = :passwd,";

                $this->id_member = $values["id_member"];

                //редактируем данные в таблице T_NEW
                $this->dbHelper->addQuery(get_class($this) . '/update-member', "
                    update t_member
                    set 
                    name = :name,
                    surname = :surname,
                    patronymic = :patronymic,
                    login = :login,
                    $pass_str
                    email = :email, 
                    msisdn = :msisdn,
                    id_sex = :id_sex,
                    dt_birthday = :dt_birthday,
                    id_status = :id_status
                    where id_member = :id_member");
                $this->dbHelper->execute(get_class($this) . '/update-member', $values_prepared);
            }

            //обработка фоток
            $this->setPhotos($values, "member", $this->dbHelper);

            //роли
            $this->setRoles($values);

            return json_encode(array('result' => 'success'));
        } else {
            return json_encode(array(
                'result' => 'error',
                'fields' => $form->getValueErrors(),
                'message' => ''
            ));
        }
    }


    private function getRoles() {
        if(empty($this->id_member))
            return NULL;
        $this->dbHelper->addQuery(get_class($this) . '/select-roles', "
            select mbrl.id_role
            from t_member_role mbrl 
            inner join t_role rl on mbrl.id_role = rl.id_role
            where id_member = :id_member
            order by rl.order_by_roles");
        $id_role = $this->dbHelper->selectValue(get_class($this) . '/select-roles', array(':id_member' => $this->id_member));

        return $id_role;
    }

    private function setRoles($values) {
        if(empty($this->id_member))
            return NULL;

        //чистим старую
        $this->dbHelper->addQuery(get_class($this) . '/delete-roles', "
        delete 
        from t_member_role 
        where id_member = :id_member");
        $this->dbHelper->execute(get_class($this) . '/delete-roles', array(':id_member' => $this->id_member));

        //вставляем новую роль, а она одна, так что всё просто
        $this->dbHelper->addQuery(get_class($this) . '/insert-roles', "
        insert into t_member_role(
            id_member, 
            id_role
        ) 
        values(
            :id_member, 
            :id_role
        )");
        $this->dbHelper->execute(get_class($this) . '/insert-roles', array("id_member" => $this->id_member, "id_role" => $values["id_role"]));
    }

    /** Подтверждение удаления, открываем форму */
    protected function processDeleteConfirmForm() {
        $buttons = array();
        $buttons[] = $this->getButton('delete');
        $buttons[] = $this->getButton('cancel');

        $this->member = $this->getObject();

        //вяжем данные
        $form = new MemberDeleteConfirmForm($this->context, array('id' => $this->formId));
        $form->bind($this->member);

        $formTitle = 'Вы действительно хотите удалить пользователя';

        return json_encode(array(
            'title' => $formTitle,
            'form' => $form->render($this->formId),
            'buttons' => implode('', $buttons)
        ));
    }

    /** Удаление пользователя */
    protected function processDeleteForm() {
        $this->dbHelper = $this->context->getDbHelper();
        $values_keys = $this->context->getRequest()->getParameter('formkey', array());

        if (isset($values_keys["id_member"])) {
            $this->dbHelper->addQuery(get_class($this) . '/delete-member', "
                delete 
                from t_member 
                where id_member = :id_member
            ");
            $this->dbHelper->execute(get_class($this) . '/delete-member', array('id_member' => $values_keys["id_member"]));
            return json_encode(array('result' => 'success'));
        } else {
            return json_encode(array(
                'result' => 'error',
                'fields' => array("id_tag" => "required"),
                'message' => ''
            ));
        }
    }


    /** Пользователи. Формируем объект данных для привязки и валидации в форму */
    protected function getObject() {
        if (empty($this->id_object)) {
            return array();
        }

        $this->dbHelper->addQuery(get_class($this) . '/select-object', "
            select 
            *
            from t_join_request 
            where id_join_request = :id_object");
        $object = array_change_key_case($this->dbHelper->selectRow(get_class($this) . '/select-object', array(':id_object' => $this->id_object), PDO::FETCH_ASSOC));

        //$member["regions"] = $this->getGeoObjects();

        //$member["id_role"] = $this->getRoles();

        //$member["photos"] = $this->getPhotos($this->id_member, "member", $this->dbHelper);

        return $object;
    }

    private function checkUnique($param, $values){
        $errors = [];

        $this->dbHelper->addQuery(get_class($this) . '/select-member', "
            select *
            from t_member 
            where $param = :$param
            and (id_member != :id_member or :id_member is null)");
        if($this->dbHelper->selectRow(get_class($this) . '/select-member', array(
            ':id_member' => $values['id_member'],
            ":$param" => $values[$param]
        ), PDO::FETCH_ASSOC)){
            $errors = array("$param" => "invalid");
        }

        return $errors;
    }

    private function checkPassword($values){
        $errors = array();

        //вставка - должны быть заполнены оба
        if(empty($values["id_member"])){
            //if(empty($values["passwd"])) 
            //    $errors["passwd"] = "required";

            //if(empty($values["passwd_confirm"])) 
            //    $errors["passwd_confirm"] = "required";
        }
        //обновление
        else{
            //не заполнено подтверждение
            //if(!empty($values["passwd"]) && empty($values["passwd_confirm"])) 
            //    $errors["passwd_confirm"] = "required";

            //не заполнен пароль
            //elseif(empty($values["passwd"]) && !empty($values["passwd_confirm"])) 
            //    $errors["passwd"] = "required";
        }

        if(empty($values["passwd"]) && !empty($values["passwd_confirm"]))
            $errors["passwd"] = "required";

        if(!empty($values["passwd"]) && empty($values["passwd_confirm"]))
            $errors["passwd_confirm"] = "required";

        //пароль и подтверждение не совпадают
        if(!empty($values["passwd"]) && !empty($values["passwd_confirm"]) && $values["passwd"] != $values["passwd_confirm"])
            $errors = array("passwd" => "invalid", "passwd_confirm" => "invalid");

        return $errors;
    }
}
