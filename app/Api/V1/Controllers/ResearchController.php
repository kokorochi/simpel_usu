<?php

namespace App\Api\V1\Controllers;

use App\Api\V1\Services\ResearchService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ResearchController extends Controller
{
    private $researchService;

    public function __construct(ResearchService $researchService)
    {
        $this->researchService = $researchService;
    }

    public function getAll()
    {
        $data = $this->researchService->getAll();

        $data->prepend(count($data), 'iTotalRecords');
        $data = $data->toJson();

        return response($data, 200)->header('Content-Type', 'application/json');
    }

    public function getAllWithDetail()
    {
        $data = $this->researchService->getAllWithDetail();
        $data = $data->toJson();

        return response($data, 200)->header('Content-Type', 'application/json');
    }
}
