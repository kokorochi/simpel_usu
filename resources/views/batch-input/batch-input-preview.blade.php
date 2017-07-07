@extends('layouts.lay_admin')

@section('content')
    <section id="page-content">
        <form action="{{url('batch-input/upload')}}" method="post" enctype="multipart/form-data">
            <table class="table table-stripped table-theme">
                <thead>
                <tr>
                    <th>Tahun</th>
                    <th>Jenis Penelitian</th>
                    <th>Skim</th>
                    <th>Sumber Dana</th>
                    <th>Jumlah Anggota</th>
                    <th>NIDN (Ketua)</th>
                    <th>Bidang Keahlihan (Ketua)</th>
                    <th>NIDN (Anggota 1)</th>
                    <th>Bidang Keahlihan (Anggota 1)</th>
                    <th>NIDN (Anggota 2)</th>
                    <th>Bidang Keahlihan (Anggota 2)</th>
                    <th>NIDN (Anggota 3)</th>
                    <th>Bidang Keahlihan (Anggota 3)</th>
                    <th>NIDN (Anggota 4)</th>
                    <th>Bidang Keahlihan (Anggota 4)</th>
                    <th>NIDN (Anggota 5)</th>
                    <th>Bidang Keahlihan (Anggota 5)</th>
                    <th>Luaran 1</th>
                    <th>Luaran 2</th>
                    <th>Luaran 3</th>
                    <th>Fakultas</th>
                    <th>Judul</th>
                    <th>Jumlah Dana</th>
                    <th>Jangka Waktu</th>
                    <th>Jumlah Mahasiswa</th>
                    <th>Alamat</th>
                </tr>
                </thead>
                <tbody>
                @foreach($excel_results as $excel_result)
                    <tr>
                        <td><input name="years[]" type="text" value="{{$excel_result['years']}}"></td>
                        <td><input name="research_type[]" type="text" value="{{$excel_result['research_type']}}">
                        </td>
                        <td><input name="scheme[]" type="text" value="{{$excel_result['scheme']}}"></td>
                        <td><input name="sponsor[]" type="text" value="{{$excel_result['sponsor']}}"></td>
                        <td><input name="count_member[]" type="text" value="{{$excel_result['count_member']}}"></td>
                        <td><input name="created_by[]" type="text" value="{{$excel_result['created_by']}}"></td>
                        <td><input name="areas_of_expertise[]" type="text"
                                   value="{{$excel_result['areas_of_expertise']}}"></td>
                        <td><input name="nidn_1[]" type="text" value="{{$excel_result['nidn_1']}}"></td>
                        <td><input name="areas_of_expertise_1[]" type="text"
                                   value="{{$excel_result['areas_of_expertise_1']}}"></td>
                        <td><input name="nidn_2[]" type="text" value="{{$excel_result['nidn_2']}}"></td>
                        <td><input name="areas_of_expertise_2[]" type="text"
                                   value="{{$excel_result['areas_of_expertise_2']}}"></td>
                        <td><input name="nidn_3[]" type="text" value="{{$excel_result['nidn_3']}}"></td>
                        <td><input name="areas_of_expertise_3[]" type="text"
                                   value="{{$excel_result['areas_of_expertise_3']}}"></td>
                        <td><input name="nidn_4[]" type="text" value="{{$excel_result['nidn_4']}}"></td>
                        <td><input name="areas_of_expertise_4[]" type="text"
                                   value="{{$excel_result['areas_of_expertise_4']}}"></td>
                        <td><input name="nidn_5[]" type="text" value="{{$excel_result['nidn_5']}}"></td>
                        <td><input name="areas_of_expertise_5[]" type="text"
                                   value="{{$excel_result['areas_of_expertise_5']}}"></td>
                        <td><input name="output_type_id_1[]" type="text"
                                   value="{{$excel_result['output_type_id_1']}}"></td>
                        <td><input name="output_type_id_2[]" type="text"
                                   value="{{$excel_result['output_type_id_2']}}"></td>
                        <td><input name="output_type_id_3[]" type="text"
                                   value="{{$excel_result['output_type_id_3']}}"></td>
                        <td><input name="faculty_code[]" type="text" value="{{$excel_result['faculty_code']}}"></td>
                        <td><textarea name="title[]" cols="30" rows="2">{{$excel_result['title']}}</textarea></td>
                        <td><input name="total_amount[]" type="text" value="{{$excel_result['total_amount']}}"></td>
                        <td><input name="time_period[]" type="text" value="{{$excel_result['time_period']}}"></td>
                        <td><input name="student_involved[]" type="text"
                                   value="{{$excel_result['student_involved']}}"></td>
                        <td><input name="address[]" type="text" value="{{$excel_result['address']}}"></td>
                    </tr>
                @endforeach
                </tbody>
            </table>

            {{ csrf_field() }}

            <div class="form-footer">
                <div class="col-sm-offset-3">
                    <button type="submit" class="btn btn-success btn-slideright submit">Submit</button>
                </div>
            </div>
        </form>
    </section>
@endsection