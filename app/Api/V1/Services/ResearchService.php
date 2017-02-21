<?php

namespace App\Api\V1\Services;

use App\Api\V1\Repository\ProposeRepository;
use App\Api\V1\Repository\ResearchRepository;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Database\DatabaseManager;

class ResearchService {

    private $database;
    private $dispatcher;
    private $researchRepository;
    private $proposeRepository;

    public function __construct(
        DatabaseManager $databaseManager,
        Dispatcher $dispatcher,
        ResearchRepository $researchRepository,
        ProposeRepository $proposeRepository
    )
    {
        $this->database = $databaseManager;
        $this->dispatcher = $dispatcher;
        $this->researchRepository = $researchRepository;
    }

    public function getAll()
    {
        return $this->researchRepository->getAll();
    }

    public function getAllWithDetail()
    {
        $research_columns = [
            'id','propose_id'
        ];
        $propose_columns = [
            "id",
              "period_id",
              "is_own",
              "faculty_code",
              "areas_of_expertise",
              "title",
              "total_amount",
              "final_amount",
              "time_period",
              "student_involved",
              "address",
              "file_propose_ori",
              "file_propose_final_ori",
        ];
        $researches = $this->researchRepository->getAll($research_columns);
        $count_research = count($researches);
        foreach ($researches as $research)
        {
            $research['propose'] = $research->propose()->first($propose_columns);
            $research['propose_output_types'] = $research['propose']->proposeOutputType()->get(['item', 'output_type_id']);
            $research['count_research_output'] = count($research['propose_output_types']);
            foreach ($research['propose_output_types'] as $propose_output_type)
            {
                $propose_output_type['output_name'] = $propose_output_type->outputType()->first(['output_name']);
            }
        }
        $researches['count_research'] = $count_research;
        return $researches;
    }
}