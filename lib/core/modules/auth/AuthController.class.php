<?php

/**
 * Контроллер авторизации
 */
class AuthController extends nomvcBaseController
{

    // инициализация модуля
    public function run()
    {
        // готовим форму
        $form = new LoginForm($this->context, array('method' => 'post', 'action' => '/login'));
        $panelClass = 'panel panel-default';
        // анализируем запрос
        $request = $this->context->getRequest();
        if ($request->isPost()) {
            // валидируем форму
            if ($form->validate($request->getParameter('login'))) {
                $user = $this->context->getUser();
                // пытаемся авторизоваться
                if ($user->signin($form->getValue('login'), $form->getValue('password'))) {
                    $this->redirect('/');    // если всё успешно - переходим на главную страницу
                }
            }
            // если путь не корректен -
            $form->setErrorMessage('Указаны некорректные логин/пароль');
            $panelClass = 'panel panel-danger';
        }
        // отрисовываем форму входа
        $generator = new OutputGenerator($this->context, $this);
        return $generator->prepare('auth', array(
            'content' => $form->render('login'),
            'panelClass' => $panelClass,
        ))->run();
    }

    // выполняем модуль

    protected function init()
    {

    }

    protected function makeUrl()
    {
        return '';
    }

}
