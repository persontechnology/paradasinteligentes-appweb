@extends('layouts.app')

@section('breadcrumb')
{{ Breadcrumbs::render('poisicion-vehiculos.index') }}
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