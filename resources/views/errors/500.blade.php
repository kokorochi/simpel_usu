@extends('layouts.lay_error')
<!-- START @ERROR PAGE -->
@section('content')

    <div class="error-wrapper">
        <h1>500!</h1>
        <h3>Telah terjadi kesalahan pada sistem</h3>
        @if(isset($error))
            <h4>{{ $error }}<br/> <br/></h4>
        @else
            <h4>Mohon hubungi LP untuk keterangan lebih lanjut<br/> <br/></h4>
        @endif
    </div>

@stop
<!--/ END ERROR PAGE -->
