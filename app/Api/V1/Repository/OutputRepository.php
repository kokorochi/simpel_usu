<?php

namespace App\Api\V1\Repository;

use App\ProposeOutputType;

class OutputRepository {
    public function getModel()
    {
        return new ProposeOutputType();
    }

    public function getAll(array $columns = null)
    {
        $data = $this->getModel();

        return $data->get($columns);
    }
}