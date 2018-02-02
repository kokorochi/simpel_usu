<!DOCTYPE HTML>
<html>
<head>
<!-- START @META SECTION -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <link href="{{$assetUrl}}images/shortcut icon.png" rel="shortcut icon">
<!-- START @FONT STYLES -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Oswald:700,400" rel="stylesheet">
    <!--/ END FONT STYLES -->
<!-- START @GLOBAL MANDATORY STYLES -->
    <link href="{{$assetUrl}}global/plugins/bower_components/bootstrap/dist/css/bootstrap.min.css"
          rel="stylesheet">
    <!--/ END GLOBAL MANDATORY STYLES -->

<!-- START @THEME STYLES -->
    <link href="{{$assetUrl}}admin/css/custom.css" rel="stylesheet">
    <link href="{{$assetUrl}}admin/css/print-custom.css" rel="stylesheet">
</head>
<body class="page-session page-sound page-header-fixed page-sidebar-fixed">
<div id="print-confirmation-wrapper">
    <h3 class="text-center">
        Halaman Pengesahan<br/>
        @if(!is_null($period))
            {{$period->scheme}}
        @elseif(! is_null($propose_own))
            {{$propose_own->scheme}}
        @endif
    </h3>
    <table>
        <tbody>
        <!-- Dedication Title -->
        <tr>
            <td class="print-col-1">1.</td>
            <td class="print-col-2">Judul Penelitian</td>
            <td class="print-col-3">:</td>
            <td class="print-col-4">{{ $propose->title }}</td>
        </tr>
        <!-- End Dedication Title -->

        <!-- Head Detail -->
        <tr>
            <td class="print-col-1">2.</td>
            <td class="print-col-2">Ketua Tim Pengusul</td>
            <td class="print-col-3"></td>
            <td class="print-col-4"></td>
        </tr>
        <tr>
            <td class="print-col-1"></td>
            <td class="print-col-2">a. Nama</td>
            <td class="print-col-3">:</td>
            <td class="print-col-4">
                @if($lecturer->front_degree !== '-' &&
                    $lecturer->front_degree !== '' &&
                    $lecturer->front_degree !== null)
                    @php($lecturer->full_name = $lecturer->front_degree . ' ' . $lecturer->full_name)
                @endif
                @if($lecturer->behind_degree !== '-' &&
                    $lecturer->behind_degree !== '' &&
                    $lecturer->behind_degree !== null)
                    @php($lecturer->full_name = $lecturer->full_name . ', ' . $lecturer->behind_degree)
                @endif
                {{$lecturer->full_name}}
            </td>
        </tr>
        <tr>
            <td class="print-col-1"></td>
            <td class="print-col-2">b. NIP</td>
            <td class="print-col-3">:</td>
            <td class="print-col-4">{{$lecturer->number_of_employee_holding}}</td>
        </tr>
        <tr>
            <td class="print-col-1"></td>
            <td class="print-col-2">c. NIDN</td>
            <td class="print-col-3">:</td>
            <td class="print-col-4">{{$lecturer->employee_card_serial_number}}</td>
        </tr>
        <tr>
            <td class="print-col-1"></td>
            <td class="print-col-2">d. Jabatan/Golongan</td>
            <td class="print-col-3">:</td>
            <td class="print-col-4">{{$lecturer->position}}</td>
        </tr>
        <tr>
            <td class="print-col-1"></td>
            <td class="print-col-2">e. Program Studi</td>
            <td class="print-col-3">:</td>
            <td class="print-col-4">{{$lecturer->study_program}}</td>
        </tr>
        <tr>
            <td class="print-col-1"></td>
            <td class="print-col-2">f. Bidang Keahlian</td>
            <td class="print-col-3">:</td>
            <td class="print-col-4">{{$propose->areas_of_expertise}}</td>
        </tr>
        <tr>
            <td class="print-col-1"></td>
            <td class="print-col-2">g. Alamat Kantor/Telp/Faks</td>
            <td class="print-col-3">:</td>
            <td class="print-col-4">{{$propose->address}}</td>
        </tr>
        <!-- End Head Detail -->

        <!-- Member Detail -->
        <tr>
            <td class="print-col-1">3.</td>
            <td class="print-col-2">Anggota Tim Pengusul</td>
            <td class="print-col-3"></td>
            <td class="print-col-4"></td>
        </tr>
        <tr>
            <td class="print-col-1"></td>
            <td class="print-col-2">a. Jumlah Anggota</td>
            <td class="print-col-3">:</td>
            <td class="print-col-4">Dosen {{$members->count()}} orang</td>
        </tr>
        @php($ctr_alpha = 'b')
        @foreach($members as $member)
            @if($member->external === null)
                @php
                    $this->client = new \GuzzleHttp\Client();
                    $response = $this->client->get('https://api.usu.ac.id/0.1/users/search?query='.$member->nidn);
                    $employees = json_decode($response->getBody());
                    $employees = $employees->response;

                    $user_id = $employees->data[0]->id;

                    $response_lect = $this->client->get('https://api.usu.ac.id/0.1/users/'.$user_id.'?fieldset=functional');
                    $employee = json_decode($response_lect->getBody());

                    $lecturer_member = new App\ModelSDM\Lecturer();
                    $lecturer_member->front_degree = $employee->data->front_degree;
                    $lecturer_member->behind_degree = $employee->data->behind_degree;
                    $lecturer_member->full_name = $employee->data->full_name;
                    $lecturer_member->employee_card_serial_number = $employee->data->nidn;

                    if(empty($employee->data->nidn)){
                        $lecturer_member->employee_card_serial_number = $employee->data->nip;
                    }else{
                        $lecturer_member->employee_card_serial_number = $employee->data->nidn;
                    }

                    $lecturer_member->study_program = $employee->data->study_program;

                    $lecturer_member->work_unit = $employees->data[0]->work_unit;
                    if(isset($employee->data->functional[0])){
                        $lecturer_member->position = $employee->data->functional[0]->functional_position;
                    }else{
                        $lecturer_member->position = "";
                    }

                @endphp
                {{-- @php($faculty = \App\ModelSDM\Faculty::where('faculty_code', $lecturer_member->work_unit)->first()) --}}
            @endif
            @php
                if($member->external === '1')
                {
                    $full_name = $member->external_name;
                }else
                {
                    $full_name = $member->full_name;
                }
            @endphp
            @if($member->external === null)
                @if($lecturer_member->front_degree !== '-' &&
                    $lecturer_member->front_degree !== '' &&
                    $lecturer_member->front_degree !== null)
                    @php($full_name = $lecturer_member->front_degree . ' ' . $lecturer_member->full_name)
                @endif
                @if($lecturer_member->behind_degree !== '-' &&
                    $lecturer_member->behind_degree !== '' &&
                    $lecturer_member->behind_degree !== null)
                    @php($full_name = $full_name . ', ' . $lecturer_member->behind_degree)
                @endif
            @endif
            <tr>
                <td class="print-col-1"></td>
                <td class="print-col-2"><u>{{$ctr_alpha++ . '. Anggota Peneliti (' . $member->item . ')'}}</u></td>
                {{--<td class="print-col-2">{{$ctr_alpha++ . '. Nama Anggota ' . $member->item . ' / bidang keahlian'}}</td>--}}
                <td class="print-col-3"></td>
                {{--<td class="print-col-4">{{$full_name . ' / ' . $member->areas_of_expertise}}</td>--}}
                <td class="print-col-4"></td>
            </tr>
            <tr>
                <td class="print-col-1"></td>
                <td class="print-col-2">1. Nama Lengkap</td>
                <td class="print-col-3">:</td>
                <td class="print-col-4">{{$full_name}}</td>
            </tr>
            @if($member->external === null)
                <tr>
                    <td class="print-col-1"></td>
                    <td class="print-col-2">2. NIP / NIDN</td>
                    <td class="print-col-3">:</td>
                    <td class="print-col-4">{{$lecturer_member->employee_card_serial_number}}</td>
                </tr>
                <tr>
                    <td class="print-col-1"></td>
                    <td class="print-col-2">3. Jabatan/Golongan</td>
                    <td class="print-col-3">:</td>
                    <td class="print-col-4">{{$lecturer_member->position}}</td>
                </tr>
                <tr>
                    <td class="print-col-1"></td>
                    <td class="print-col-2">4. Unit</td>
                    <td class="print-col-3">:</td>
                    <td class="print-col-4">{{$lecturer_member->work_unit}}</td>
                </tr>
            @endif
        @endforeach
        <tr>
            <td class="print-col-1"></td>
            <td class="print-col-2">{{$ctr_alpha . '. Mahasiswa yang terlibat'}}</td>
            <td class="print-col-3">:</td>
            <td class="print-col-4">{{$propose->student_involved . ' orang'}}</td>
        </tr>
        <!-- End Member Detail -->

        <!-- Output Type -->
        {{--@foreach($propose_output_types as $key => $propose_output_type)--}}
        {{--<tr>--}}
        {{--<td class="print-col-1">{{$key === 0 ? "5." : ""}}</td>--}}
        {{--<td class="print-col-2">{{$key === 0 ? "Luaran yang dihasilkan" : ""}}</td>--}}
        {{--<td class="print-col-3">{{$key === 0 ? ":" : ""}}</td>--}}
        {{--<td class="print-col-4">{{"- " . $propose_output_type->outputType()->first()->output_name}}</td>--}}
        {{--</tr>--}}
        {{--@endforeach--}}
        <!-- End Output Type -->

        <tr>
            <td class="print-col-1">4.</td>
            <td class="print-col-2">Jangka waktu Pelaksanaan</td>
            <td class="print-col-3">:</td>
            <td class="print-col-4">{{$propose->time_period . ' bulan'}}</td>
        </tr>

        <tr>
            <td class="print-col-1">5.</td>
            <td class="print-col-2">Biaya yang diperlukan</td>
            <td class="print-col-3">:</td>
            @if($propose->final_amount !== null)
                @php($propose->total_amount = $propose->final_amount)
            @endif
            <td class="print-col-4">{{'Rp. ' . number_format($propose->total_amount, 0, ',', '.')}}</td>
        </tr>

        <tr>
            <td class="print-col-1">6.</td>
            <td class="print-col-2">Sumber Dana</td>
            <td class="print-col-3">:</td>
            <td class="print-col-4">
                @if($propose->is_own === null)
                    {{$propose->period()->first()->sponsor}}
                @else
                    {{$propose->proposesOwn()->first()->scheme}}
                @endif
            </td>
        </tr>
        </tbody>
    </table>
    <table class="table-2">
        <tbody>
        <tr>
            <td class="print-col-1 text-center">Mengetahui</td>
            <td class="print-col-2 text-center">Medan, {{$today_date}}</td>
        </tr>
        <tr>
            <td class="print-col-1 text-center">
                @if($propose->faculty_code=="RS")
                    @if($sign_1 === 'dean') Direktur Utama,
                    @elseif($sign_1 === 'vice_dean_1') Direktur Pendidikan, Pelatihan, Penelitian dan Kerjasama,    
                    @endif
                @elseif($propose->faculty_code=="SPS")    
                    @if($sign_1 === 'dean') Direktur,
                    @elseif($sign_1 === 'vice_dean_1') Wakil Direktur I,
                    @elseif($sign_1 === 'vice_dean_2' ) Wakil Direktur II,
                    @endif
                @else
                    @if($sign_1 === 'dean') Dekan,
                    @elseif($sign_1 === 'vice_dean_1') Wakil Dekan 1,
                    @elseif($sign_1 === 'vice_dean_2') Wakil Dekan 2,
                    @elseif($sign_1 === 'vice_dean_3') Wakil Dekan 3,
                    @endif
                @endif
            </td>
            <td class="print-col-2 text-center">Ketua Tim Pengusul,</td>
        </tr>
        <tr>
            <td>&nbsp</td>
        </tr>
        <tr>
            <td>&nbsp</td>
        </tr>
        <tr>
            <td>&nbsp</td>
        </tr>
        <tr>
            <td class="print-col-1 text-center">{{$dean->full_name}}</td>
            <td class="print-col-2 text-center">{{$lecturer->full_name}}</td>
        </tr>
        <tr>
            <td class="print-col-1 text-center">{{'NIP. ' . $dean->number_of_employee_holding}}</td>
            <td class="print-col-2 text-center">{{'NIP. ' . $lecturer->number_of_employee_holding}}</td>
        </tr>
        </tbody>
    </table>
    <table class="table-2">
        <tbody>
        <tr>
            <td class="print-col-1 text-center">Menyetujui</td>
        </tr>
        <tr>
            <td class="print-col-1 text-center">Lembaga Penelitian</td>
        </tr>
        <tr>
            <td class="print-col-1 text-center">
                @if($sign_2 === 'head') Ketua,
                @elseif($sign_2 === 'secretary') Sekretaris,
                @endif
            </td>
        </tr>
        <tr>
            <td class="print-col-1 text-center">&nbsp</td>
        </tr>
        <tr>
            <td class="print-col-1 text-center">&nbsp</td>
        </tr>
        <tr>
            <td class="print-col-1 text-center">&nbsp</td>
        </tr>
        <tr>
            <td class="print-col-1 text-center">{{$lppm_head->full_name}}</td>
        </tr>
        <tr>
            <td class="print-col-1 text-center">{{'NIP. ' . $lppm_head->number_of_employee_holding }}</td>
        </tr>
        </tbody>
    </table>
</div>
<script>
    window.print();
</script>
</body>
</html>