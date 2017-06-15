<?php

namespace App\Api\V1\Controllers;

use App\Api\V1\Services\ResearchService;
use App\Http\Controllers\Controller;
use App\Member;
use App\Propose;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Input;

class ResearchController extends Controller {
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

    public function getByInput()
    {
        $data = [];
        $input = Input::get();

        if (! isset($input['nidn']) || ! isset($input['apps']))
            $data['messages'] = 'Input not found!';
        else
        {
            $proposes = Propose::where('created_by', $input['nidn'])->get();
            $members = Member::where('nidn', $input['nidn'])->get();
            $propose_as_members = new Collection();
            foreach ($members as $member)
            {
                $propose = $member->propose()->first();
                if (! is_null($propose))
                    $propose_as_members->push($propose);
            }

            $researches = new Collection();
            foreach ($proposes as $propose)
            {
                $research = $propose->research()->first();
                if (! is_null($research))
                {
                    $research->position = 'Ketua';
                    $researches->push($research);
                }
            }
            foreach ($propose_as_members as $propose)
            {
                $research = $propose->research()->first();
                if (! is_null($research))
                {
                    $research->position = 'Anggota';
                    $researches->push($research);
                }
            }

            $i = 0;
            foreach ($researches as $research)
            {
                $propose = $research->propose()->first();
                if ($propose->is_own == null)
                    $period = $propose->period()->first();
                else
                    $period = $propose->proposesOwn()->first();

                $flow_status = $propose->flowStatus()->orderBy('id', 'desc')->first();
                if ($flow_status->status_code == 'PS')
                    $status = 'Selesai';
                else
                    $status = 'On-Going';

                $data[$i]['penelitian'] = [
                    'judul'           => $propose->title,
                    'sumber_dana'     => $period->sponsor,
                    'skema'           => $period->scheme,
                    'jumlah_dana'     => $propose->final_amount,
                    'lama_penelitian' => $propose->time_period,
                    'tahun'           => $period->years,
                    'posisi'          => $research->position,
                    'status'          => $status,
                ];

                $propose_output_types = $propose->proposeOutputType()->get();
                foreach ($propose_output_types as $propose_output_type)
                {
                    $output_type = $propose_output_type->outputType()->first();
                    $data[$i]['luaran_usulan'][] = $output_type->output_name;
                }

                $research_output_generals = $research->researchOutputGeneral()->get();
                foreach ($research_output_generals as $research_output_general)
                {
                    $data[$i]['luaran'][] = [
                        'tahun'     => $research_output_general->year,
                        'deskripsi' => $research_output_general->output_description,
                        'url'       => $research_output_general->url_address,
                        'status'    => $research_output_general->status,
                    ];
                }
                $i++;
            }
            if ($researches->isEmpty())
                $data['messages'] = 'Tidak ada penelitian';
        }

        $data = json_encode($data, JSON_PRETTY_PRINT);

        return response($data, 200)->header('Content-Type', 'application/json');
    }
}
