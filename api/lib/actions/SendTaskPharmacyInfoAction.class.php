<?php

class SendTaskPharmacyInfoAction extends AbstractAction
{
    public function getTitle()
    {
        return 'Отправить информацию по аптеке/лекарствам для задания пользователя';
    }

    public function init()
    {
        parent::init();

        $this->addParameter('token', new agStringValidator(array('required' => true)), 'Token');

        $this->addParameter('id_task', new agIntegerValidator(array('required' => true)), 'ID Задания');
        $this->addParameter('id_pharmacy', new agIntegerValidator(array('required' => true)), 'ID Аптеки');

        $this->addParameter('data', new agArrayValidator(array('required' => true)), 'Массив данных');

        $this->dbHelper->addQuery($this->getAction() . '/check_task_member_pharmacy_exist', '
                select *
                from t_task tt
                inner join t_task_member_pharmacy ttmp on tt.id_task = ttmp.id_task
                inner join t_pharmacy tp on ttmp.id_pharmacy = tp.id_pharmacy
                where ttmp.id_member = :id_member
                and ttmp.id_task = :id_task
                and ttmp.id_pharmacy = :id_pharmacy
                and tp.id_status = 1
        ');

        $this->dbHelper->addQuery($this->getAction() . '/update_status_sended', '
                update t_task_member_pharmacy 
                set
                    id_status = 1,
                    dt_status = CURRENT_TIMESTAMP
                where id_member = :id_member
                and id_task = :id_task
                and id_pharmacy = :id_pharmacy
        ');

        $this->dbHelper->addQuery($this->getAction() . '/check_info_exist', '
                select *
                from t_task_member_pharmacy_comment ttmpc
                where ttmpc.id_member = :id_member
                and ttmpc.id_task = :id_task
                and ttmpc.id_pharmacy = :id_pharmacy
        ');

        $this->dbHelper->addQuery($this->getAction() . '/insert_info', '
                insert into t_task_member_pharmacy_comment (id_member, id_task, id_pharmacy, is_action, comment)
                values (:id_member, :id_task, :id_pharmacy, :is_action, :comment)
        ');

        $this->dbHelper->addQuery($this->getAction() . '/update_info', '
                update t_task_member_pharmacy_comment
                set
                is_action = :is_action,
                comment = :comment 
                where id_member = :id_member
                and id_task = :id_task
                and id_pharmacy = :id_pharmacy
        ');

        $this->dbHelper->addQuery($this->getAction() . '/check_data_exist', '
                select id_task_data
                from t_task_data 
                where id_member = :id_member
                and id_task = :id_task
                and id_pharmacy = :id_pharmacy
                and id_sku = :id_sku
        ');

        $this->dbHelper->addQuery($this->getAction() . '/insert_data', '
                insert into t_task_data (
                    id_task, 
                    task_name, 
                    task_dt, 
                    id_sku,
                    sku_name,
                    sku_type_name,
                    sku_producer_name,
                    sku_id_sku_type,    
                    sku_id_sku_producer,    
                    sku_id_status,
                    sku_status_name,
                    id_pharmacy, 
                    pharmacy_name,                                                                            
                    pharmacy_member,
                    pharmacy_address,
                    pharmacy_category_name,
                    pharmacy_region_name,
                    pharmacy_city_name,
                    pharmacy_area_name,
                    pharmacy_id_crm,
                    pharmacy_id_category,
                    pharmacy_id_region,
                    pharmacy_id_city,
                    pharmacy_id_area,
                    id_member, 
                    member_name,
                    member_surname,
                    member_patronymic,
                    member_email,
                    member_msisdn,
                    member_role_name,
                    member_region_name,
                    member_city_name,
                    member_area_name, 
                    value,
                    rest_cnt,
                    illiquid_cnt,
                    is_action,
                    comment
                )
                values (
                    :id_task::bigint, 
                    (select name from t_task where id_task = :id_task limit 1),                      
                    (select dt_task from t_task where id_task = :id_task limit 1),                     
                    :id_sku::bigint,
                    (select name from t_sku where id_sku = :id_sku limit 1),                      
                    (select name from t_sku_type where id_sku_type = (select id_sku_type from t_sku where id_sku = :id_sku limit 1) limit 1),
                    (select name from t_sku_producer where id_sku_producer = (select id_sku_producer from t_sku where id_sku = :id_sku limit 1) limit 1),
                    (select id_sku_type from t_sku where id_sku = :id_sku limit 1),                     
                    (select id_sku_producer from t_sku where id_sku = :id_sku limit 1),                     
                    (select id_status from t_sku where id_sku = :id_sku limit 1),
                    (select name from v_sku_status where id_status = (select id_status from t_sku where id_sku = :id_sku limit 1) limit 1), 
                    :id_pharmacy::bigint, 
                    (select name from t_pharmacy where id_pharmacy = :id_pharmacy limit 1),
                    (select (COALESCE(tm.surname, \'\'::character varying)::text || \' \'::text || COALESCE(tm.name, \'\'::character varying)::text || \' \'::text || COALESCE(tm.patronymic, \'\'::character varying)::text) from t_member tm where tm.id_member = (select id_member from t_pharmacy where id_pharmacy = :id_pharmacy::bigint limit 1) limit 1),                    
                    (select address from t_pharmacy where id_pharmacy = :id_pharmacy limit 1),
                    (select name from t_category where id_category = (select id_category from t_pharmacy where id_pharmacy = :id_pharmacy limit 1) limit 1),
                    (select name from t_region where id_region = (select id_region from t_pharmacy where id_pharmacy = :id_pharmacy limit 1) limit 1),
                    (select name from t_city where id_city = (select id_city from t_pharmacy where id_pharmacy = :id_pharmacy limit 1) limit 1),                                       
                    (select name from t_area where id_area = (select id_area from t_pharmacy where id_pharmacy = :id_pharmacy limit 1) limit 1),
                    (select id_crm from t_pharmacy where id_pharmacy = :id_pharmacy limit 1),
                    (select id_category from t_pharmacy where id_pharmacy = :id_pharmacy limit 1),
                    (select id_region from t_pharmacy where id_pharmacy = :id_pharmacy limit 1),
                    (select id_city from t_pharmacy where id_pharmacy = :id_pharmacy limit 1),
                    (select id_area from t_pharmacy where id_pharmacy = :id_pharmacy limit 1),
                    :id_member::bigint,
                    (select name from t_member where id_member = :id_member limit 1),                      
                    (select surname from t_member where id_member = :id_member limit 1),                      
                    (select patronymic from t_member where id_member = :id_member limit 1),                      
                    (select email from t_member where id_member = :id_member limit 1),                      
                    (select msisdn from t_member where id_member = :id_member limit 1),                      
                    (select name from t_role where id_role = (select id_role from t_member where id_member = :id_member limit 1) limit 1),
                    (select name from t_region where id_region = (select id_region from t_member where id_member = :id_member limit 1) limit 1),
                    (select name from t_city where id_city = (select id_city from t_member where id_member = :id_member limit 1) limit 1),                               
                    (select name from t_area where id_area = (select id_area from t_member where id_member = :id_member limit 1) limit 1),
                    :value,
                    :rest_cnt,
                    :illiquid_cnt,
                    :is_action,
                    :comment
                )
        ');

        $this->dbHelper->addQuery($this->getAction() . '/update_data', '
                update t_task_data
                set
                    id_task = :id_task::bigint, 
                    task_name = (select name from t_task where id_task = :id_task limit 1),               
                    task_dt = (select dt_task from t_task where id_task = :id_task limit 1),                     
                    id_sku = :id_sku::bigint,
                    sku_name = (select name from t_sku where id_sku = :id_sku limit 1),                      
                    sku_type_name = (select name from t_sku_type where id_sku_type = (select id_sku_type from t_sku where id_sku = :id_sku limit 1) limit 1),
                    sku_producer_name = (select name from t_sku_producer where id_sku_producer = (select id_sku_producer from t_sku where id_sku = :id_sku limit 1) limit 1),
                    sku_id_sku_type    = (select id_sku_type from t_sku where id_sku = :id_sku limit 1),
                    sku_id_sku_producer = (select id_sku_producer from t_sku where id_sku = :id_sku limit 1),
                    sku_id_status = (select id_status from t_sku where id_sku = :id_sku limit 1),
                    sku_status_name = (select name from v_sku_status where id_status = (select id_status from t_sku where id_sku = :id_sku limit 1) limit 1),
                    id_pharmacy = :id_pharmacy::bigint, 
                    pharmacy_name = (select name from t_pharmacy where id_pharmacy = :id_pharmacy limit 1),
                    pharmacy_member = (select (COALESCE(tm.surname, \'\'::character varying)::text || \' \'::text || COALESCE(tm.name, \'\'::character varying)::text || \' \'::text || COALESCE(tm.patronymic, \'\'::character varying)::text) from t_member tm where tm.id_member = (select id_member from t_pharmacy where id_pharmacy = :id_pharmacy::bigint limit 1) limit 1),            
                    pharmacy_address = (select address from t_pharmacy where id_pharmacy = :id_pharmacy limit 1),
                    pharmacy_category_name = (select name from t_category where id_category = (select id_category from t_pharmacy where id_pharmacy = :id_pharmacy limit 1) limit 1),
                    pharmacy_region_name = (select name from t_region where id_region = (select id_region from t_pharmacy where id_pharmacy = :id_pharmacy limit 1) limit 1),
                    pharmacy_city_name = (select name from t_city where id_city = (select id_city from t_pharmacy where id_pharmacy = :id_pharmacy limit 1) limit 1),                                       
                    pharmacy_area_name = (select name from t_area where id_area = (select id_area from t_pharmacy where id_pharmacy = :id_pharmacy limit 1) limit 1),
                    pharmacy_id_crm = (select id_crm from t_pharmacy where id_pharmacy = :id_pharmacy limit 1),
                    pharmacy_id_category = (select id_category from t_pharmacy where id_pharmacy = :id_pharmacy limit 1),
                    pharmacy_id_region = (select id_region from t_pharmacy where id_pharmacy = :id_pharmacy limit 1),
                    pharmacy_id_city = (select id_city from t_pharmacy where id_pharmacy = :id_pharmacy limit 1),
                    pharmacy_id_area = (select id_area from t_pharmacy where id_pharmacy = :id_pharmacy limit 1),
                    id_member = :id_member::bigint,
                    member_name = (select name from t_member where id_member = :id_member limit 1),                      
                    member_surname = (select surname from t_member where id_member = :id_member limit 1),                      
                    member_patronymic = (select patronymic from t_member where id_member = :id_member limit 1),                      
                    member_email = (select email from t_member where id_member = :id_member limit 1),                      
                    member_msisdn = (select msisdn from t_member where id_member = :id_member limit 1),                      
                    member_role_name = (select name from t_role where id_role = (select id_role from t_member where id_member = :id_member limit 1) limit 1),
                    member_region_name = (select name from t_region where id_region = (select id_region from t_member where id_member = :id_member limit 1) limit 1),
                    member_city_name = (select name from t_city where id_city = (select id_city from t_member where id_member = :id_member limit 1) limit 1),                           
                    member_area_name = (select name from t_area where id_area = (select id_area from t_member where id_member = :id_member limit 1) limit 1),
                    value = :value,
                    rest_cnt = :rest_cnt,
                    illiquid_cnt = :illiquid_cnt,
                    is_action = :is_action,
                    comment = :comment
                where id_task_data = :id_task_data
        ');

    }

    public function execute()
    {
        $member = $this->authByToken();

        if (isset($member['id_member'])) {
            if ($this->dbHelper->selectRow($this->getAction() . '/check_task_member_pharmacy_exist', array(
                'id_member' => $member['id_member'],
                'id_task' => $this->getValue('id_task'),
                'id_pharmacy' => $this->getValue('id_pharmacy')
            ))) {
                $data = $this->getValue('data');
                $data = json_decode(json_encode($data), True);

                $has_info = false;
                $sku_list = array();
                if (is_array($data)) {
                    foreach ($data as $key => $val) {
                        if ($key == 'info') {
                            $has_info = true;
                            $is_action = @$val['is_action'] ? @$val['is_action'] : 0;
                            $comment = @$val['comment'] ? @$val['comment'] : '';
                        } elseif ($key == 'sku_list') {
                            $sku_list = $val;
                        }
                    }
                }

                if ($has_info) {
                    if ($this->dbHelper->selectRow($this->getAction() . '/check_info_exist', array(
                        'id_member' => $member['id_member'],
                        'id_task' => $this->getValue('id_task'),
                        'id_pharmacy' => $this->getValue('id_pharmacy')
                    ))
                    ) {
                        $this->dbHelper->selectRow($this->getAction() . '/update_info', array(
                            'id_member' => $member['id_member'],
                            'id_task' => $this->getValue('id_task'),
                            'id_pharmacy' => $this->getValue('id_pharmacy'),
                            'is_action' => $is_action,
                            'comment' => $comment
                        ));
                    } else {
                        $this->dbHelper->selectRow($this->getAction() . '/insert_info', array(
                            'id_member' => $member['id_member'],
                            'id_task' => $this->getValue('id_task'),
                            'id_pharmacy' => $this->getValue('id_pharmacy'),
                            'is_action' => $is_action,
                            'comment' => $comment
                        ));
                    }
                }

                $sql = '
                    select id_task_data
                    from t_task_data 
                    where id_member = :id_member
                    and id_task = :id_task
                    and id_pharmacy = :id_pharmacy
                    and id_sku = :id_sku
                    ';
                $stmt_check = $this->context->getDb()->prepare($sql);

                if (is_array($sku_list) && !empty($sku_list))
                    foreach ($sku_list as $sku) {
                        $stmt_check->bindValue('id_task', $this->getValue('id_task'));
                        $stmt_check->bindValue('id_pharmacy', $this->getValue('id_pharmacy'));
                        $stmt_check->bindValue('id_member', $member['id_member']);
                        $stmt_check->bindValue('id_sku', $sku['id_sku']);
                        $stmt_check->execute();
                        $row = $stmt_check->fetch();
                        $stmt_check->closeCursor();

                        $id_task_data = null;
                        if (isset($row['id_task_data']))
                            $id_task_data = $row['id_task_data'];

                        if (!empty($id_task_data)) {
                            $this->dbHelper->execute($this->getAction() . '/update_data', array(
                                'id_member' => $member['id_member'],
                                'id_task' => $this->getValue('id_task'),
                                'id_pharmacy' => $this->getValue('id_pharmacy'),
                                'id_sku' => $sku['id_sku'],
                                'value' => $sku['my_value'],
                                'rest_cnt' => @$sku['rest_cnt'],
                                'illiquid_cnt' => @$sku['illiquid_cnt'],
                                'is_action' => @$sku['is_action'],
                                'comment' => @$sku['comment'],
                                'id_task_data' => $id_task_data
                            ));
                        } else {
                            $this->dbHelper->execute($this->getAction() . '/insert_data', array(
                                'id_member' => $member['id_member'],
                                'id_task' => $this->getValue('id_task'),
                                'id_pharmacy' => $this->getValue('id_pharmacy'),
                                'id_sku' => $sku['id_sku'],
                                'value' => $sku['my_value'],
                                'rest_cnt' => @$sku['rest_cnt'],
                                'illiquid_cnt' => @$sku['illiquid_cnt'],
                                'is_action' => @$sku['is_action'],
                                'comment' => @$sku['comment'],
                            ));
                        }
                    }

                $this->dbHelper->selectRow($this->getAction() . '/update_status_sended', array(
                    'id_member' => $member['id_member'],
                    'id_task' => $this->getValue('id_task'),
                    'id_pharmacy' => $this->getValue('id_pharmacy')
                ));

                return array('result' => Errors::SUCCESS);
            } else
                $this->throwActionException(Errors::NO_DATA_FOUND);
        } else
            $this->throwActionException(Errors::MEMBER_NOT_FOUND);

        return array('result' => Errors::FAIL);
    }

    public function getResponseExample()
    {
        return json_decode('{
  "response": {
    "result": 100
  }
}');
    }
}