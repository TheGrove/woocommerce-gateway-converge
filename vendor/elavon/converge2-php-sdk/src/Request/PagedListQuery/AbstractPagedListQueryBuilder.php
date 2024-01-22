<?php

namespace Elavon\Converge2\Request\PagedListQuery;

use Elavon\Converge2\DataObject\C2ApiFieldName;
use Elavon\Converge2\DataObject\FilterOperator;

abstract class AbstractPagedListQueryBuilder
{

    protected $params = array();
    protected $whereField;

    protected function setParam($field, $value)
    {
        if (isset($this->params[$field])) {
            if (!is_array($this->params[$field])) {
                $this->params[$field] = (array)$this->params[$field];
            }
            $this->params[$field][] = $value;
        } else {
            $this->params[$field] = $value;
        }
    }

    protected function setFilterFromWhereField($op, $value)
    {
        if (is_string($this->whereField)) {
            $this->setFilter($this->whereField, $op, $value);
        }
        return $this;
    }

    protected function where($field)
    {
        $this->whereField = $field;
        return $this;
    }

    public function setFilter($field, $op, $value)
    {
        if (is_bool($value)) {
            $value = $value ? 'true' : 'false';
        }

        $this->setParam('filter', $field . '_' . $op . '_' . $value);
    }

    public function isEqualTo($value)
    {
        return $this->setFilterFromWhereField(FilterOperator::EQ, $value);
    }

    public function isNotEqualTo($value)
    {
        return $this->setFilterFromWhereField(FilterOperator::NE, $value);
    }

    public function isGreaterThanOrEqualTo($value)
    {
        return $this->setFilterFromWhereField(FilterOperator::GE, $value);
    }

    public function isGreaterThan($value)
    {
        return $this->setFilterFromWhereField(FilterOperator::GT, $value);
    }

    public function isLessThanOrEqualTo($value)
    {
        return $this->setFilterFromWhereField(FilterOperator::LE, $value);
    }

    public function isLessThan($value)
    {
        return $this->setFilterFromWhereField(FilterOperator::LT, $value);
    }


    public function setLimit($value)
    {
        $this->setParam(C2ApiFieldName::LIMIT, $value);
    }

    public function setPageToken($value)
    {
        $this->setParam(C2ApiFieldName::PAGE_TOKEN, $value);
    }

    public function getQueryString()
    {
        $query = array();
        foreach ($this->params as $name => $value) {
            if (is_scalar($value)) {
                $query[] = $name . '=' . $value;
            } else {
                foreach ($value as $item) {
                    if (is_scalar($item)) {
                        $query[] = $name . '=' . $item;
                    }
                }
            }
        }

        return implode('&', $query);
    }

    public function extractQueryStringFromUrl($url)
    {
        return parse_url($url, PHP_URL_QUERY);
    }
}