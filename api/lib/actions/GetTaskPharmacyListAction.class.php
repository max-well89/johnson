<?php

class GetTaskPharmacyListAction extends AbstractAction
{
    public function getTitle()
    {
        return 'Получить задание/список аптек для пользователя';
    }

    public function init()
    {
        parent::init();

        $this->addParameter('token', new agStringValidator(array('required' => true)), 'Token');

        $this->addParameter('id_task', new agIntegerValidator(array('required' => false)), 'ID Задания');

        $this->dbHelper->addQuery($this->getAction() . '/get_last_task', '
                select t0.*
                from (
                    select
                    tt.id_task,
                    tt.name,
                    to_char(tt.dt, \'DD-MM-YYYY HH24:MI:SS\') as dt,
                    to_char(tt.dt_task, \'DD-MM-YYYY HH24:MI:SS\') as dt_task
                    from t_task tt
                    inner join t_task_member_pharmacy ttmp on tt.id_task = ttmp.id_task
                    where ttmp.id_member = :id_member
                    and (
                        ttmp.id_task = :id_task
                        or
                        :id_task is null
                    )
                    order by tt.dt_task desc
                ) t0
                limit 1
        ');

        $this->dbHelper->addQuery($this->getAction() . '/get_pharmacy_list', '
                select
                ttmp.id_pharmacy,
                tp.name,
                to_char(tp.dt, \'DD-MM-YYYY HH24:MI:SS\') as dt,
                tp.address,
                tc.id_category,
                tc.name as category,
                tr.id_region,
                tr.name as region,
                tcy.id_city,
                tcy.name as city,
                ta.id_area,
                ta.name as area,
                (case when td.cnt > 0 then 1 else 0 end) AS is_sended1,
                (case when td2.cnt > 0 then 1 else 0 end) AS is_sended2
                from t_task tt
                inner join t_task_member_pharmacy ttmp on tt.id_task = ttmp.id_task
                inner join t_pharmacy tp on ttmp.id_pharmacy = tp.id_pharmacy
                left join t_category tc on tc.id_category = tp.id_category
                left join t_region tr on tr.id_region = tp.id_region
                left join t_city tcy on tcy.id_city = tp.id_city
                left join t_area ta on ta.id_area = tp.id_area
                left join (
                  select ttd.id_pharmacy, sum(distinct ttd.id_task_data) as cnt
                  from t_task_data ttd 
                  where ttd.id_task = :id_task
                  and ttd.id_member = :id_member
                  group by ttd.id_pharmacy
                ) td on tp.id_pharmacy = td.id_pharmacy
                left join (
                  select ttmpc.id_pharmacy, sum(distinct ttmpc.id_task_comment) as cnt
                  from t_task_member_pharmacy_comment ttmpc 
                  where ttmpc.id_task = :id_task
                  and ttmpc.id_member = :id_member
                  group by ttmpc.id_pharmacy
                ) td2 on tp.id_pharmacy = td2.id_pharmacy
                where ttmp.id_member = :id_member
                and ttmp.id_task = :id_task
                and tp.id_status = 1
        ');

    }

    public function execute()
    {
        $member = $this->authByToken();

        if (isset($member['id_member'])) {
            if ($task = $this->dbHelper->selectRow($this->getAction() . '/get_last_task', array('id_member' => $member['id_member'], 'id_task' => $this->getValue('id_task')))) {
                $stmt = $this->dbHelper->select($this->getAction() . '/get_pharmacy_list', array('id_member' => $member['id_member'], 'id_task' => $task['id_task']));

                $pharmacy_list = null;
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $row = $this->asStrictTypes($row, []);

                    if ($row['is_sended1'] == 1 || $row['is_sended2'] == 1) {
                        $row['is_sended'] = 1;
                    } else {
                        $row['is_sended'] = 0;
                    }

                    unset($row['is_sended1']);
                    unset($row['is_sended2']);

                    $pharmacy_list[] = $row;
                }

                return array('result' => Errors::SUCCESS, 'data' => [
                    'task' => $task,
                    'pharmacy_list' => $pharmacy_list
                ]);
            } else {
                return array('result' => Errors::SUCCESS, 'data' => ['task' => null, 'pharmacy_list' => null]);
            }
        } else
            $this->throwActionException(Errors::MEMBER_NOT_FOUND);

//        var_dump($this->context->getDb()->errorInfo()); exit;

        return array('result' => Errors::FAIL);
    }

    public function getResponseExample()
    {
        return json_decode('{
  "response": {
    "result": 100,
    "data": {
      "task": {
        "id_task": 4,
        "name": "четвертое",
        "dt": "11-09-2016 15:24:29",
        "dt_task": "14-09-2016 00:00:00"
      },
      "pharmacy_list": [
        {
          "id_pharmacy": 1,
          "name": "test1",
          "dt": "06-09-2016 23:49:17",
          "address": "test1",
          "id_category": 1,
          "category": "A",
          "id_region": 1,
          "region": "Москва",
          "id_city": 1,
          "city": "Москва",
          "id_area": 1,
          "area": "Западное Дегунино",
          "is_sended": 1
        },
        {
          "id_pharmacy": 2,
          "name": "тест2",
          "dt": "06-09-2016 23:47:20",
          "address": "тест2",
          "id_category": 2,
          "category": "B",
          "id_region": 1,
          "region": "Москва",
          "id_city": 1,
          "city": "Москва",
          "id_area": 1,
          "area": "Западное Дегунино",
          "is_sended": 0
        }
      ]
    }
  }
}');
    }
}
