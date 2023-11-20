<form action="{{ route('admin.attachfile.store') }}" method="POST"  enctype="multipart/form-data">
    @csrf
  <input type="file" id="file" name="file">
  <input type="submit">
</form>