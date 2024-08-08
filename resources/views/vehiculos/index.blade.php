@extends('layouts.app')

@section('breadcrumb')
{{ Breadcrumbs::render('vehiculos.index') }}
@endsection

@section('breadcrumb_elements')
<div class="collapse d-lg-block ms-lg-auto" id="breadcrumb_elements">
							
	<div class="d-lg-flex mb-2 mb-lg-0">
		<a href="{{ route('vehiculos.create') }}" class="d-flex align-items-center text-body py-2">
			<i class="ph-plus me-2"></i>
			Nuevo
		</a>

	</div>
</div>
@endsection

@section('content')
    <div class="card">
        
        <div class="card-body">
            <div class="table-responsive">
                {{ $dataTable->table() }}
            </div>
        </div>
        
    </div>
    
@endsection

@push('scriptsHeader')

@endpush


@push('scriptsFooter')
{{ $dataTable->scripts() }}

@endpush