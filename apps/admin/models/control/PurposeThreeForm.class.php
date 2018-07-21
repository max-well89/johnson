<?php

class PurposeThreeForm extends nomvcAbstractForm {
    public function init() {
        parent::init();

        $disabled = $this->context->getUser()->getAttribute('id_restaurant')?true:false;

        $this->addWidget(new nomvcInputHiddenWidget('id_purpose', 'id_purpose'));
        $this->addValidator('id_purpose', new nomvcIntegerValidator(array('required' => false)));

        if (!$disabled) {
            $this->addWidget(new nomvcCheckboxFromDbWidget('Форматы ресторанов', 'restaurant_types', array(
                'helper' => $this->context->getDbHelper(),
                'values' => array(
                    'table' => 'T_RESTAURANT_TYPE',
                    'key' => 'id_restaurant_type',
                    'fields' => array('все' => 'name'),
                    'order' => 'id_restaurant_type'
                )
            ), array()));
            $this->addValidator('restaurant_types', new nomvcArrayValidator(array('required' => false)));

            $this->addWidget(new nomvcSelectFromMultipleDbWidget('Рестораны', 'restaurants', array(
                'helper' => $this->context->getDbHelper(),
                'table' => 'V_RESTAURANT',
                'key' => 'id_restaurant',
                'multiple' => true,
                'field_group' => 'restaurant_type'
            ), array()));
            $this->addValidator('restaurants', new nomvcArrayValidator(array('required' => false)));
        }

        $this->addWidget(new nomvcSelectFromMultipleDbWidget('Тип цели', 'id_purpose_type', array(
            'helper' => $this->context->getDbHelper(),
            'table' => 'V_PURPOSE_TYPE_THREE',
            'order' => 'id_purpose_type',
            'required' => true
        ), array('disabled' => $disabled)));
        $this->addValidator('id_purpose_type', new nomvcValueInDbValidator(array(
            'required' => true,
            'helper' => $this->context->getDbHelper(),
            'table' => 'V_PURPOSE_TYPE_THREE',
            'key' => 'id_purpose_type'
        )));
/*
        $this->addWidget(new nomvcInputTextWidget('Порог №1 (%)', 'percent_one', array(), array('placeholder' => 'Порог №1(в процентах)', 'disabled' => $disabled)));
        $this->addValidator('percent_one', new nomvcIntegerValidator(array('required' => false)));

        $this->addWidget(new nomvcInputTextWidget('Порог №1 (баллы)', 'point_one', array(), array('placeholder' => 'Порог №1(в баллах)', 'disabled' => $disabled)));
        $this->addValidator('point_one', new nomvcIntegerValidator(array('required' => false)));

        $this->addWidget(new nomvcInputTextWidget('Порог №2 (%)', 'percent_two', array(), array('placeholder' => 'Порог №2(в процентах)', 'disabled' => $disabled)));
        $this->addValidator('percent_two', new nomvcIntegerValidator(array('required' => false)));

        $this->addWidget(new nomvcInputTextWidget('Порог №2 (баллы)', 'point_two', array(), array('placeholder' => 'Порог №2(в баллах)', 'disabled' => $disabled)));
        $this->addValidator('point_two', new nomvcIntegerValidator(array('required' => false)));

        $this->addWidget(new nomvcInputTextWidget('Порог №3 (%)', 'percent_three', array(), array('placeholder' => 'Порог №3(в процентах)', 'disabled' => $disabled)));
        $this->addValidator('percent_three', new nomvcIntegerValidator(array('required' => false)));

        $this->addWidget(new nomvcInputTextWidget('Порог №3 (баллы)', 'point_three', array(), array('placeholder' => 'Порог №3(в баллах)', 'disabled' => $disabled)));
        $this->addValidator('point_three', new nomvcIntegerValidator(array('required' => false)));
*/
        $this->addWidget(new nomvcInputDateTimePickerWidget('Период с', 'dt_from', array(), array('disabled' => $disabled)));
        $this->addValidator('dt_from', new nomvcDateValidator(array('in_format' => DateHelper::HTMLT_FORMAT)));

        $this->addWidget(new nomvcInputDateTimePickerWidget('Период по', 'dt_to', array(), array('disabled' => $disabled)));
        $this->addValidator('dt_to', new nomvcDateValidator(array('in_format' => DateHelper::HTMLT_FORMAT)));

        $this->addWidget(new nomvcSelectFromMultipleDbWidget('Месяц для статистики', 'id_month', array(
            'helper' => $this->context->getDbHelper(),
            'table' => 'T_MONTH',
            'order' => 'id_month',
            'required' => false
        ), array('disabled' => $disabled)));
        $this->addValidator('id_month', new nomvcValueInDbValidator(array(
            'required' => false,
            'helper' => $this->context->getDbHelper(),
            'table' => 'T_MONTH',
            'key' => 'id_month'
        )));
        
        $this->addWidget(new nomvcTextareaWidget('Текстовое описание цели', 'description', array(), array('disabled' => $disabled)));
        $this->addValidator('description', new nomvcStringValidator(array('required' => false,'min' => 2, 'max' => 200)));
        /*
                $this->addWidget(new nomvcSelectFromMultipleDbWidget('Единица измерения', 'id_measure_type', array(
                    'helper' => $this->context->getDbHelper(),
                    'table' => 'V_MEASURE_TYPE',
                    'order' => 'name',
                    'required' => false
                ), array('disabled' => $disabled)));
                $this->addValidator('id_measure_type', new nomvcValueInDbValidator(array('required' => false, 'helper' => $this->context->getDbHelper(), 'table' => 'V_MEASURE_TYPE', 'key' => 'id_measure_type')));
        */
        if ($disabled) {
            $this->addWidget(new nomvcSelectFromMultipleDbWidget('Статус', 'actual_result_id_status', array(
                'helper' => $this->context->getDbHelper(),
                'table' => 'V_PURPOSE_TYPE_THREE_STATUS',
                'order' => 'name',
                'required' => false,
                'key' => 'id_status'
            ), array()));
            $this->addValidator('actual_result_id_status', new nomvcValueInDbValidator(array(
                'required' => false, 
                'helper' => $this->context->getDbHelper(), 
                'table' => 'V_PURPOSE_TYPE_THREE_STATUS', 
                'key' => 'id_status'
            )));
            
 //           $this->addWidget(new nomvcInputTextWidget('Фактический показатель', 'actual_result_val', array(), array('type' => 'number')));
   //         $this->addValidator('actual_result_val', new nomvcNumberValidator(array('required' => false)));
        }

        if ($this->isBined == true) {
            $dbHelper = $this->context->getDbHelper();

            if (!$disabled && $this->getValue('id_purpose')){
                $this->addWidget(new nomvcActualResultsThreeWidget('Фактические показатели', 'actual_results', array(
                    //'path-upload' => 'purpose-two-form/post'
                )));
                $this->addValidator('actual_results', new nomvcArrayValidator(array('required' => false)));
            }

            if ($this->getValue('id_purpose')) {
                $dbHelper->addQuery('select_status', '
                    select id_status
                    from V_PURPOSE 
                    where id_purpose = :id_purpose');
                $id_status = $dbHelper->selectValue('select_status', array(
                    'id_purpose' => $this->getValue('id_purpose')
                ));

                $dbHelper->addQuery('select_check_status', '
                    select is_approve
                    from T_PURPOSE_ACTUAL_RESULT
                    where id_purpose = :id_purpose
                    and id_restaurant = :id_restaurant');
                $check_status = $dbHelper->selectValue('select_check_status', array(
                    'id_purpose' => $this->getValue('id_purpose'),
                    'id_restaurant' => $this->context->getUser()->getAttribute('id_restaurant')
                ));

                if ($disabled) {
                    if ($id_status == 3 || $check_status == 1) {
                        $this->getWidget('actual_result_id_status')->setAttribute('disabled', true);
                    }
                }
                else{
                    if ($id_status == 3) {
//                        $this->getWidget('dt_to')->setAttribute('disabled', true);
                    }
                }
            }

            // new hotelka
            if ($disabled) {
                $this->unsetWidget('dt_from');

                $this->unsetWidget('dt_to');

                $this->unsetWidget('id_month');
            }
        }
    }
}
