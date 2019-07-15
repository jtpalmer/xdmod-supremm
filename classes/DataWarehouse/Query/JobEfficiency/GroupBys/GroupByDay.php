<?php

namespace DataWarehouse\Query\JobEfficiency\GroupBys;

class GroupByDay extends \DataWarehouse\Query\JobEfficiency\GroupBy
{
    public static function getLabel()
    {
         return 'Day';
    }

    public function __construct()
    {
        parent::__construct(
            'day',
            array(),
            "select distinct gt.id,
                date(gt.day_start) as long_name,
                date(gt.day_start) as short_name,
                gt.day_start_ts as start_ts
             from  modw.days gt
             where 1
             order by gt.id asc",
            array()
        );
        $this->setAvailableOnDrilldown(false);
    }

    public function applyTo(\DataWarehouse\Query\Query &$query, \DataWarehouse\Query\Model\Table $data_table, $multi_group = false)
    {
        $id_field = new \DataWarehouse\Query\Model\TableField($query->getDataTable(), "day_id", $this->getIdColumnName($multi_group));
        $name_field = new \DataWarehouse\Query\Model\FormulaField('date('.$query->getDateTable()->getAlias().".day_start)", $this->getLongNameColumnName($multi_group));
        $shortname_field = new \DataWarehouse\Query\Model\FormulaField('date('.$query->getDateTable()->getAlias().".day_start)", $this->getShortNameColumnName($multi_group));
        $value_field = new \DataWarehouse\Query\Model\TableField($query->getDateTable(), "day_start_ts");
        $query->addField($id_field);
        $query->addField($name_field);
        $query->addField($shortname_field);
        $query->addField($value_field);

        $query->addGroup($id_field);

        $this->addOrder($query, $multi_group);
    }

    public function addOrder(\DataWarehouse\Query\Query &$query, $multi_group = false, $dir = 'asc', $prepend = false)
    {
        $orderField = new \DataWarehouse\Query\Model\OrderBy(new \DataWarehouse\Query\Model\TableField($query->getDataTable(), "day_id"), $dir, $this->getName());
        if ($prepend === true) {
            $query->prependOrder($orderField);
        } else {
            $query->addOrder($orderField);
        }
    }

    public function pullQueryParameters(&$request)
    {
        $parameters = array();

        return $parameters;
    }

    public function pullQueryParameterDescriptions(&$request)
    {
        $parameters = array();

        return $parameters;
    }
}
