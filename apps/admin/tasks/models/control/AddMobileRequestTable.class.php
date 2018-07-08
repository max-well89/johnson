<?php

class AddMobileRequestTable extends AbstractMapObjectTable {

    public function init($options = array()) {
        $options = array(
            'sort_by' => 'id_mobile_point',
            'sort_order' => 'desc'
        );

        parent::init($options);

        $this->setRowModelClass('AddMobileRequest');

        $this->addColumn('id_mobile_point', 'ID запроса', 'integer');
        $this->addColumn('learning_id', 'Learning ID', 'string');
        $this->addColumn('surname', 'Фамилия', 'string');
        $this->addColumn('name', 'Имя', 'string');
        $this->addColumn('restaurant', 'Название ресторана', 'string');
        $this->addColumn('city', 'Город', 'string');
        $this->addColumn('address', 'Адрес ресторана', 'string');
        $this->addColumn('position', 'Должность', 'string');
        $this->addColumn('dt', 'Дата отправки запроса', 'date', array('format' => DateHelper::HTMLDTS_FORMAT));
        $this->addColumn('purpose', 'Тип запроса', 'string');
        $this->addColumn('val', 'Стоимость в баллах', 'string');
        $this->addColumn('status', 'Статус запроса', 'string');

        $this->setFilterForm(new AddMobileRequestFilterForm($this->context));
    }

}
