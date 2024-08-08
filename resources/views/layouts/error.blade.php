@foreach (['success','error','info','alert'] as $msg)
    @if (Session::has($msg))
    
        <script>
            new Noty({
                text: "{{ Session::get($msg) }}",
                type: "alert"
            }).show();
        </script>
        
    @endif
@endforeach

@if ($errors->any())
    <div class="alert bg-danger text-white alert-dismissible fade show">
        @foreach ($errors->all() as $error)
            <span class="fw-semibold">{{ $error }}</span><br>
        @endforeach
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
    </div>
@endif