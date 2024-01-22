<?php

namespace Elavon\Converge2\Request\PagedListQuery;

use Elavon\Converge2\DataObject\C2ApiFieldName;

class TransactionListQueryBuilder extends AbstractPagedListQueryBuilder
{

    public function whereType()
    {
        return $this->where(C2ApiFieldName::TYPE);
    }

    public function whereCreatedAt()
    {
        return $this->where(C2ApiFieldName::CREATED_AT);
    }

    public function whereIsHeldForReview()
    {
        return $this->where(C2ApiFieldName::IS_HELD_FOR_REVIEW);
    }

    public function whereBatch()
    {
        return $this->where(C2ApiFieldName::BATCH);
    }

}