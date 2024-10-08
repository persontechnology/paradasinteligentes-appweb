@extends('layouts.app')

@section('breadcrumb')
{{ Breadcrumbs::render('rutas.index') }}
@endsection

@section('breadcrumb_elements')
<div class="collapse d-lg-block ms-lg-auto" id="breadcrumb_elements">
							
	<div class="d-lg-flex mb-2 mb-lg-0">
		<a href="{{ route('rutas.create') }}" class="d-flex align-items-center text-body py-2">
			<i class="ph-plus me-2"></i>
			Nuevo
		</a>

		{{-- <div class="dropdown ms-lg-3">
			<a href="#" class="d-flex align-items-center text-body dropdown-toggle py-2" data-bs-toggle="dropdown">
				<i class="ph-gear me-2"></i>
				<span class="flex-1">Settings</span>
			</a>

			<div class="dropdown-menu dropdown-menu-end w-100 w-lg-auto">
				<a href="#" class="dropdown-item">
					<i class="ph-shield-warning me-2"></i>
					Account security
				</a>
				<a href="#" class="dropdown-item">
					<i class="ph-chart-bar me-2"></i>
					Analytics
				</a>
				<a href="#" class="dropdown-item">
					<i class="ph-lock-key me-2"></i>
					Privacy
				</a>
				<div class="dropdown-divider"></div>
				<a href="#" class="dropdown-item">
					<i class="ph-gear me-2"></i>
					All settings
				</a>
			</div>
		</div> --}}
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