<?php
/**
 * Description of NewsTable
 *
 * @author sefimov
 */
class AbstractMapObjectTable extends nomvcAbstractTable {

    public function init($options = array()) {
        parent::init($options);
        $user = $this->context->getUser();
        $this->setDBContextParameter('id_member', $user->getAttribute('id_member'));
    }
    
    protected function setDBContextParameter($var, $val) {
        try {
            $query = "select set_parameter(:name, :val);";
            $stmt = $this->context->getDb()->prepare($query);
            $stmt->bindValue('name', $var);
            $stmt->bindValue('val', $val);
            $stmt->execute();
        }
        catch(exception $e){}
    }
    
    public function doAction() {
        // готовимся внимать тому, чего от нас хотят
        $uri = $this->controller->getNextUri();
        $action = explode('/', $uri);
        $id_map = isset($action[1]) ? $action[1] : '';
        if (preg_match('/^\d++$/', $id_map)) {
            $filters = $this->filterForm->getDefaults();
            $filters['id_map'] = $id_map;
            $this->applyFilters($filters);
        } else {
            parent::doAction();
        }
    }
    
    protected function setFilters($filters) {
        if (!isset($filters['id_map'])) {
            $filters_old = $this->getFilters();
            if (isset($filters_old['id_map'])) {
                $filters['id_map'] = $filters_old['id_map'];
            }
        }
        return parent::setFilters($filters);
    }
}
