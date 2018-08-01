<?php

class BackendController extends nomvcBaseControllerTwo
{
    public function run()
    {
        $request = $this->getCurrentUriPart();

        switch ($request) {
            case 'member-form':
                $controller = new MemberFormController($this->context, $this);
                break;
            case 'member-table':
                $controller = new MemberTable($this->context, $this);
                break;
            case 'pharmacy-form':
                $controller = new PharmacyFormController($this->context, $this);
                break;
            case 'pharmacy-table':
                $controller = new PharmacyTable($this->context, $this);
                break;
            case 'sku-form':
                $controller = new SkuFormController($this->context, $this);
                break;
            case 'sku-table':
                $controller = new SkuTable($this->context, $this);
                break;
            case 'add-sku-type':
                try {
                    $name = $this->context->getRequest()->getParameter('element');
                    $stmt = $this->context->getDb()->prepare('insert into t_sku_type(name, id_database) values(:name, :id_database) returning id_sku_type');
                    $stmt->bindValue('name', $name);
                    $stmt->bindValue('id_database', $this->context->getUser()->getAttribute('id_database'));
                    $stmt->execute();

                    if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        return json_encode(array('result' => 'success', 'id' => $row['id_sku_type'], 'element' => $name), true);
                    }
                } catch (exception $e) {
                }
                return json_encode(array('result' => 'error'), true);
                break;
            case 'add-region':
                try {
                    $name = $this->context->getRequest()->getParameter('element');
                    $stmt = $this->context->getDb()->prepare('insert into t_region(name, id_database) values(:name, :id_database) returning id_region');
                    $stmt->bindValue('name', $name);
                    $stmt->bindValue('id_database', $this->context->getUser()->getAttribute('id_database'));
                    $stmt->execute();

                    if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        return json_encode(array('result' => 'success', 'id' => $row['id_region'], 'element' => $name), true);
                    }
                } catch (exception $e) {
                }
                return json_encode(array('result' => 'error'), true);
                break;
            case 'add-category':
                try {
                    $name = $this->context->getRequest()->getParameter('element');
                    $stmt = $this->context->getDb()->prepare('insert into t_category(name, id_database) values(:name, :id_database) returning id_category');
                    $stmt->bindValue('name', $name);
                    $stmt->bindValue('id_database', $this->context->getUser()->getAttribute('id_database'));
                    $stmt->execute();

                    if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        return json_encode(array('result' => 'success', 'id' => $row['id_category'], 'element' => $name), true);
                    }
                } catch (exception $e) {
                }
                return json_encode(array('result' => 'error'), true);
                break;
            case 'add-sku-producer':
                try {
                    $name = $this->context->getRequest()->getParameter('element');
                    $stmt = $this->context->getDb()->prepare('insert into t_sku_producer(name, id_database) values(:name, :id_database) returning id_sku_producer');
                    $stmt->bindValue('name', $name);
                    $stmt->bindValue('id_database', $this->context->getUser()->getAttribute('id_database'));
                    $stmt->execute();

                    if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        return json_encode(array('result' => 'success', 'id' => $row['id_sku_producer'], 'element' => $name), true);
                    }
                } catch (exception $e) {
                }
                return json_encode(array('result' => 'error'), true);
                break;
            case 'task-form':
                $controller = new TaskFormController($this->context, $this);
                break;
            case 'task-table':
                $controller = new TaskTable($this->context, $this);
                break;
            case 'task-detail-form':
                $controller = new TaskDetailFormController($this->context, $this);
                break;
            case 'task-detail-table':
                $controller = new TaskDetailTable($this->context, $this);
                break;
            case 'task-pharmacy-detail-form':
                $controller = new TaskPharmacyDetailFormController($this->context, $this);
                break;
            case 'task-pharmacy-detail-table':
                $controller = new TaskPharmacyDetailTable($this->context, $this);
                break;
            case 'push-form':
                $controller = new PushFormController($this->context, $this);
                break;
            case 'push-table':
                $controller = new PushTable($this->context, $this);
                break;
            default:
                return null;
        }

        return $controller->run();
    }

    public function makeUrl()
    {
        $request = $this->getCurrentUriPart();
        switch ($request) {
            case 'member-table':
                return "{$this->baseUrl}/stat/member";
            case 'pharmacy-table':
                return "{$this->baseUrl}/stat/pharmacy";
            case 'sku-table':
                return "{$this->baseUrl}/stat/sku";
            case 'task-table':
                return "{$this->baseUrl}/stat/task";
            case 'task-detail-table':
                return "{$this->baseUrl}/stat/task-detail";
            case 'task-pharmacy-detail-table':
                return "{$this->baseUrl}/stat/task-pharmacy-detail";
            case 'push-table':
                return "{$this->baseUrl}/stat/push";
            default:
                return "{$this->baseUrl}";
        }
    }

    protected function init()
    {
        parent::init();
    }

    /** возвращает данные, переданные JS-ом */
    protected function getFormData($formName = null)
    {
        parse_str($this->context->getRequest()->getParameter('formdata', array()), $data);
        if ($formName == null) {
            return $data;
        } else {
            return isset($data[$formName]) ? $data[$formName] : array();
        }
    }
}
