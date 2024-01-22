<?php

namespace Elavon\Converge2\Response;

use Elavon\Converge2\DataObject\DataGetter\PagedListDataGetterTrait;
use Elavon\Converge2\DataObject\Resource\Batch;

class BatchPagedListResponse extends Response implements PagedListResponseInterface
{
    use PagedListDataGetterTrait;

    protected function castObjectFields()
    {
        $this->castItems(Batch::class);
    }
}
