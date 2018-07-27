<?php

/**
 * Контроллер для взаимодействия с использованием JSON в качестве транспорта
 */
class agJsonApiController extends agAbstractApiController
{

    public function exec()
    {
        try {
            $this->user = $this->context->getUser();
            $this->user->auth();
            $this->getRequest();
            $this->prepareAction();
            header('Content-Type: application/json');
            return json_encode(array(
                'response' => $this->action->execute()
            ));
        } catch (agActionException $ex) {
            header('Content-Type: application/json');
            return $this->makeErrorResponse($ex->getCode(), $ex->getMessage(), 'result');
        } catch (agGlobalException $ex) {
            header('Content-Type: application/json');
            return $this->makeErrorResponse($ex->getCode(), $ex->getMessage());
        } catch (Exception $ex) {
            header('Content-Type: application/json');
            return $this->makeErrorResponse(self::FATAL_ERROR, $ex->getMessage());
        }
    }

    /**
     * Получение и предварительная обработка запроса
     */
    protected function getRequest()
    {
        $request = $this->getRawPostData();
        $this->context->getLogger()->setInput($request);
        if ($request == null) {
            throw new agGlobalException('Не найдены POST данные', self::BAD_FORMAT);
        }
        $request = json_decode($request);
        if ($request == null) {
            throw new agGlobalException('POST данные не соответствуют спецификации JSON', self::BAD_FORMAT);
        }
        if (!isset($request->request)) {
            throw new agGlobalException('JSON не содержит обязательный параметр request', self::BAD_FORMAT);
        }
        $this->request = $request->request;

        if (!isset($this->request->action)) {
            throw new agGlobalException('Не указана команда', self::BAD_ACTION);
        }
    }

    /**
     * Подготовка экшена
     */
    protected function prepareAction()
    {
        $actionClass = self::toCamelCase('_' . $this->request->action) . 'Action';
        $this->context->getLogger()->setAction($actionClass);
        $this->action = new $actionClass($this->context);
        if (!$this->user->hasAccessAction($this->action)) {
            throw new agGlobalException(sprintf('Команда "%s" не доступна пользователю "%s"',
                $this->request->action, $this->user->getLogin()), self::BAD_ACTION);
        }
        if (!isset($this->request->params)) {
            $params = array();
        } else {
            $params = get_object_vars($this->request->params);
        }
        $this->action->validate($params);
    }

    /**
     * Возвращает сгенерированный ответ для ошибок
     *
     * $code    код ошибки
     * $note    описание ошибки
     * $type    тип ошибки - result (по умолчанию) / error
     */

    protected function makeErrorResponse($code, $note, $type = 'result', $field_name = false)
    {
        $type = 'result';

        if ($field_name)
            return json_encode(array(
                'response' => array(
                    $type => $code,
                    $type . '_note' => $note,
                    'field_name' => $field_name
                )
            ));
        else
            return json_encode(array(
                'response' => array(
                    $type => $code,
                    $type . '_note' => $note,
                )
            ));
    }

}
