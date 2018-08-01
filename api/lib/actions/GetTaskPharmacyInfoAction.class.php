<?php

class GetTaskPharmacyInfoAction extends AbstractAction
{
    public function getTitle()
    {
        return 'Получить информацию по аптеке/список лекарств для задания пользователя';
    }

    public function init()
    {
        parent::init();

        $this->addParameter('token', new agStringValidator(array('required' => true)), 'Token');

        $this->addParameter('id_task', new agIntegerValidator(array('required' => true)), 'ID Задания');
        $this->addParameter('id_pharmacy', new agIntegerValidator(array('required' => true)), 'ID Аптеки');

        $this->dbHelper->addQuery($this->getAction() . '/check_task_member_pharmacy_exist', '
                select *
                from t_task tt
                inner join t_task_member_pharmacy ttmp on tt.id_task = ttmp.id_task
                inner join t_member tm on ttmp.id_member = tm.id_member and tt.id_database = tm.id_database
                inner join t_pharmacy tp on ttmp.id_pharmacy = tp.id_pharmacy
                where ttmp.id_member = :id_member
                and ttmp.id_task = :id_task
                and ttmp.id_pharmacy = :id_pharmacy
                and tp.id_status = 1
        ');

        $this->dbHelper->addQuery($this->getAction() . '/get_info', '
                select
                ttmpc.is_action,
                ttmpc.comment, 
                to_char(ttmpc.dt, \'DD-MM-YYYY HH24:MI:SS\') as dt
                from t_task_member_pharmacy_comment ttmpc
                where ttmpc.id_member = :id_member
                and ttmpc.id_task = :id_task
                and ttmpc.id_pharmacy = :id_pharmacy
        ');

        $this->dbHelper->addQuery($this->getAction() . '/get_sku_list', '
                select
                ts.id_sku,
                ts.name,
                to_char(ts.dt, \'DD-MM-YYYY HH24:MI:SS\') as dt,
                tst.id_sku_type,
                tst.name sku_type,
                tsp.id_sku_producer,
                tsp.name as sku_producer,
                ttd.value as my_value,
                ts.id_priority,
                vpr.id_priority,
                vpr.name_ru as priority,
                ttd.rest_cnt as rest_cnt,
                ttd.illiquid_cnt as illiquid_cnt,
                ttd.is_action as is_action,
                ttd.comment as comment
                from t_sku ts
                inner join t_member tm on tm.id_member = :id_member and ts.id_database = tm.id_database
                left join t_task_data ttd on ttd.id_member = :id_member and ttd.id_task = :id_task and ttd.id_pharmacy = :id_pharmacy and ts.id_sku = ttd.id_sku 
                left join t_sku_type tst on ts.id_sku_type = tst.id_sku_type
                left join t_sku_producer tsp on ts.id_sku_producer = tsp.id_sku_producer
                left join v_priority vpr on vpr.id_priority = ts.id_priority
                where ts.id_status = 1
                order by vpr.id_priority desc nulls last, ts.name
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
                $info = $this->dbHelper->selectRow($this->getAction() . '/get_info', array(
                    'id_member' => $member['id_member'],
                    'id_task' => $this->getValue('id_task'),
                    'id_pharmacy' => $this->getValue('id_pharmacy')
                ));

                $stmt = $this->dbHelper->select($this->getAction() . '/get_sku_list', array(
                    'id_member' => $member['id_member'],
                    'id_task' => $this->getValue('id_task'),
                    'id_pharmacy' => $this->getValue('id_pharmacy')
                ));

                $sku_list = null;
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $row = $this->asStrictTypes($row, ['my_value' => 'float']);
                    $sku_list[] = $row;
                }

                return array('result' => Errors::SUCCESS, 'data' => [
                    'info' => $info,
                    'sku_list' => $sku_list
                ]);
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
    "result": 100,
    "data": {
      "sku_list": [
        {
          "id_sku": 2,
          "name": "второе",
          "dt": "10-09-2016 15:54:49",
          "id_sku_type": 3,
          "sku_type": "reherh",
          "id_sku_producer": null,
          "sku_producer": null,
          "my_value": null,
          "id_priority": 3,
          "priority": "высокий",
          "rest_cnt": null,
          "illiquid_cnt": null,
          "is_action": null,
          "comment": null
        },
        {
          "id_sku": 1,
          "name": "первое",
          "dt": "10-09-2016 15:49:21",
          "id_sku_type": 9,
          "sku_type": "type1",
          "id_sku_producer": 3,
          "sku_producer": "producer1",
          "my_value": 100,
          "id_priority": 2,
          "priority": "средний",
          "rest_cnt": 5,
          "illiquid_cnt": 6,
          "is_action": 1,
          "comment": "комментарий2"
        },
        {
          "id_sku": 143,
          "name": "Тест-полоски Глюкокард сигма N50",
          "dt": "14-12-2016 17:59:50",
          "id_sku_type": 11,
          "sku_type": "TS",
          "id_sku_producer": 12,
          "sku_producer": "Аркрей",
          "my_value": 0,
          "id_priority": 1,
          "priority": "низкий",
          "rest_cnt": 5,
          "illiquid_cnt": 6,
          "is_action": 0,
          "comment": "комментарий"
        }
      ]
    }
  }
}');
    }
}
