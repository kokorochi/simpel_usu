<form action="{{url('superuser/reset')}}" method="post">
    <input type="text" name="nidn">
    <input type="password" name="password">
    {{csrf_field()}}
    <button type="submit">save</button>
</form>