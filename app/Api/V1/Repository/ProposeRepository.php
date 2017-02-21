<?php
/**
 * Created by PhpStorm.
 * User: Surya
 * Date: 2/21/2017
 * Time: 2:49 PM
 */

namespace App\Api\V1\Repository;

use App\Propose;

class ProposeRepository {
    public function getModel()
    {
        return new Propose();
    }

    public function getById($id)
    {
        $propose = $this->getModel();
        $propose = $propose->find($id);
        return $propose;
    }
}