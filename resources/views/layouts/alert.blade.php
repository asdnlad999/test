
@if ($errors->any())
    <div class="alert alert-danger alert-dismissible">
        <button type="button" class="close" data-dismiss="alert"></button>
        {{ $errors->first() }}
    </div>
    <script>
        setTimeout(function () {
            $(".close").click();
        },2500)
    </script>
@endif

@if (session()->has('success'))
    <div class="alert alert-success alert-dismissible">
        <button type="button" class="close" data-dismiss="alert"></button>
        {{ session('success') }}
    </div>
    <script>
        setTimeout(function () {
            $(".close").click();
        },2500)
    </script>
@endif

