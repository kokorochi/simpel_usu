<?php

namespace App\Api\V1\Repository;

use App\Research;

class ResearchRepository {
    public function getModel()
    {
        return new Research();
    }

    public function getAll(array $columns = null)
    {
        $research = $this->getModel();

        return $research->get($columns);

    }
}