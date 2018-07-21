<?php

/**
 * Класс - генератор контента
 */
abstract class nomvcOutputGenerator extends nomvcBaseController {

    const MODE_HTML	= 'html';
    const MODE_CSV	= 'csv';
    const MODE_XLS	= 'xls';
    const MODE_PDF	= 'pdf';

    /** наследуется из nomvcBaseController */
    protected function makeUrl() {
        return $this->parentController->makeUrl();
    }	

    /** наследуется из nomvcBaseController */
    public function init() {}

    /** наследуется из nomvcBaseController */
    public function run() {
        $controller = $this->parentController;
        foreach ($this->templateData as $key => $var) {
            $$key = $var;
        }
        ob_start();
        $template = $this->context->getDir('app_templates')."/{$this->mode}/{$this->template}.php";
        if (!file_exists($template)) {
            $template = $this->context->getDir('templates')."/{$this->mode}/{$this->template}.php";
        }
        require($template);
        return ob_get_clean();
    }
    
    /** подготовка шаблона к выводу */
    public function prepare($template, $templateData = array(), $mode = self::MODE_HTML) {
        $this->template = $template;
        $this->templateData = $templateData;
        $this->mode = $mode;
        return $this;
    }
    
}


function rus_date() {
// Перевод
    $translate = array(
        "am" => "дп",
        "pm" => "пп",
        "AM" => "ДП",
        "PM" => "ПП",
        "Monday" => "Понедельник",
        "Mon" => "Пн",
        "Tuesday" => "Вторник",
        "Tue" => "Вт",
        "Wednesday" => "Среда",
        "Wed" => "Ср",
        "Thursday" => "Четверг",
        "Thu" => "Чт",
        "Friday" => "Пятница",
        "Fri" => "Пт",
        "Saturday" => "Суббота",
        "Sat" => "Сб",
        "Sunday" => "Воскресенье",
        "Sun" => "Вс",
        "January" => "Января",
        "Jan" => "Янв",
        "February" => "Февраля",
        "Feb" => "Фев",
        "March" => "Марта",
        "Mar" => "Мар",
        "April" => "Апреля",
        "Apr" => "Апр",
        "May" => "Мая",
        "May" => "Мая",
        "June" => "Июня",
        "Jun" => "Июн",
        "July" => "Июля",
        "Jul" => "Июл",
        "August" => "Августа",
        "Aug" => "Авг",
        "September" => "Сентября",
        "Sep" => "Сен",
        "October" => "Октября",
        "Oct" => "Окт",
        "November" => "Ноября",
        "Nov" => "Ноя",
        "December" => "Декабря",
        "Dec" => "Дек",
        "st" => "ое",
        "nd" => "ое",
        "rd" => "е",
        "th" => "ое"
    );
    // если передали дату, то переводим ее
    if (func_num_args() > 1) {
        $timestamp = func_get_arg(1);
        return strtr(date(func_get_arg(0), $timestamp), $translate);
    } else {
// иначе текущую дату
        return strtr(date(func_get_arg(0)), $translate);
    }
}