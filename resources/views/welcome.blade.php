@extends('layouts.guest')
@section('content')
<div class="login-form">
	<div class="card mb-0 animated pulse">
		<div class="card-body">
			<div class="text-center mb-3">
				<div class="d-inline-flex align-items-center justify-content-center mb-4 mt-2">
					<img src="{{ asset('assets/images/logo_icon.svg') }}" class="h-48px" alt="">
				</div>
				<h5 class="mb-0">{{ config('app.name') }}</h5>
				<span class="d-block text-muted">Aplicación web de paradas inteligentes</span>
			</div>
		</div>
	</div>
</div>
	
@endsection
