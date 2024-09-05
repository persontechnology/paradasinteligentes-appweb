
<!DOCTYPE html>
<html lang="es" dir="ltr">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<title>{{ config('app.name','') }}</title>

	<!-- Global stylesheets -->
	<link href="{{ asset('assets/fonts/inter/inter.css') }}" rel="stylesheet" type="text/css">
	<link href="{{ asset('assets/icons/phosphor/styles.min.css') }}" rel="stylesheet" type="text/css">
	<link href="{{ asset('assets/css/all.min.css') }}" id="stylesheet" rel="stylesheet" type="text/css">
	<!-- /global stylesheets -->

	<!-- Theme JS files -->
	<script src="{{ asset('assets/js/jquery/jquery.min.js') }}"></script>

	<!-- Core JS files -->
	<script src="{{ asset('assets/demo/demo_configurator.js') }}"></script>
	<script src="{{ asset('assets/js/bootstrap/bootstrap.bundle.min.js') }}"></script>
	<!-- /core JS files -->

	<!-- Theme JS files -->
	<script src="{{ asset('assets/js/app.js') }}"></script>
	<!-- /theme JS files -->

</head>

<body>




	<!-- Page content -->
	<div class="page-content">

		<!-- Main content -->
		<div class="content-wrapper">

			<!-- Inner content -->
			<div class="content-inner">

				<!-- Page header -->
				<div class="page-header">
					<div class="page-header-content d-lg-flex">
						<div class="d-flex">
							<h1 class="page-title mb-0">
							PARADA: <strong>{{ $parada->nombre }}</strong>
							</h1>

							<a href="#page_header" class="btn btn-light align-self-center collapsed d-lg-none border-transparent rounded-pill p-0 ms-auto" data-bs-toggle="collapse">
								<i class="ph-caret-down collapsible-indicator ph-sm m-1"></i>
							</a>
						</div>

						<div class="collapse d-lg-block my-lg-auto ms-lg-auto" id="page_header">
							<div class="hstack gap-3 mb-3 mb-lg-0">
								<p>
                                    {{ $fecha }}
                                    <br>
                                    <strong id="tiempoActual">16.19 pm</strong>

                                </p>
                                
							</div>
						</div>
					</div>
				</div>
				<!-- /page header -->


				<!-- Content area -->
				<div class="content pt-0">

					<!-- Basic card -->
					<div class="card">
						
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table text-center">
                                        <thead>
                                            <tr class="bg-primary text-white">
                                                <th scope="col"><h1>LINEA</h1></th>
                                                <th scope="col"><h1>BUS</h1></th>
                                                <th scope="col"><h1>DISTANCIA</h1></th>
                                                <th scope="col"><h1>T.ESTIMADO</h1></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td><h1>1</h1></td>
                                                <td><h1>15412151</h1></td>
                                                <td class="bg-success text-white"><h1>LLEGADA</h1></td>
                                                <td><h1>00 min.</h1></td>
                                            </tr>
                                            <tr>
                                                <td><h1>1</h1></td>
                                                <td><h1>15412151</h1></td>
                                                <td class="bg-info text-white"><h1>ARRIBANDO</h1></td>
                                                <td><h1>1 min.</h1></td>
                                            </tr>
                                            <tr>
                                                <td><h1>1</h1></td>
                                                <td><h1>15412151</h1></td>
                                                <td class="bg-warning text-white"><h1>619 m.</h1></td>
                                                <td><h1>15 min.</h1></td>
                                            </tr>
                                           
                                        </tbody>
                                    </table>
                                </div>
                                
                            </div>
						
					</div>
					<!-- /basic card -->


				</div>
				<!-- /content area -->


				<!-- Footer -->
				<div class="navbar navbar-sm navbar-footer border-top">
					<div class="container-fluid">
						<span>&copy; 2022 <a href="https://themeforest.net/item/limitless-responsive-web-application-kit/13080328">Limitless Web App Kit</a></span>

						<ul class="nav">
							<li class="nav-item">
								<a href="https://kopyov.ticksy.com/" class="navbar-nav-link navbar-nav-link-icon rounded" target="_blank">
									<div class="d-flex align-items-center mx-md-1">
										<i class="ph-lifebuoy"></i>
										<span class="d-none d-md-inline-block ms-2">Support</span>
									</div>
								</a>
							</li>
							<li class="nav-item ms-md-1">
								<a href="https://themes.kopyov.com/limitless/demo/Documentation/index.html" class="navbar-nav-link navbar-nav-link-icon rounded" target="_blank">
									<div class="d-flex align-items-center mx-md-1">
										<i class="ph-file-text"></i>
										<span class="d-none d-md-inline-block ms-2">Docs</span>
									</div>
								</a>
							</li>
							<li class="nav-item ms-md-1">
								<a href="https://themeforest.net/item/limitless-responsive-web-application-kit/13080328?ref=kopyov" class="navbar-nav-link navbar-nav-link-icon text-primary bg-primary bg-opacity-10 fw-semibold rounded" target="_blank">
									<div class="d-flex align-items-center mx-md-1">
										<i class="ph-shopping-cart"></i>
										<span class="d-none d-md-inline-block ms-2">Purchase</span>
									</div>
								</a>
							</li>
						</ul>
					</div>
				</div>
				<!-- /footer -->

			</div>
			<!-- /inner content -->

		</div>
		<!-- /main content -->

	</div>
	<!-- /page content -->


	<!-- Demo config -->
	<div class="offcanvas offcanvas-end" tabindex="-1" id="demo_config">
		<div class="position-absolute top-50 end-100 visible">
			<button type="button" class="btn btn-primary btn-icon translate-middle-y rounded-end-0" data-bs-toggle="offcanvas" data-bs-target="#demo_config">
				<i class="ph-gear"></i>
			</button>
		</div>

		<div class="offcanvas-header border-bottom py-0">
			<h5 class="offcanvas-title py-3">Demo configuration</h5>
			<button type="button" class="btn btn-light btn-sm btn-icon border-transparent rounded-pill" data-bs-dismiss="offcanvas">
				<i class="ph-x"></i>
			</button>
		</div>

		<div class="offcanvas-body">
			<div class="fw-semibold mb-2">Color mode</div>
			<div class="list-group mb-3">
				<label class="list-group-item list-group-item-action form-check border-width-1 rounded mb-2">
					<div class="d-flex flex-fill my-1">
						<div class="form-check-label d-flex me-2">
							<i class="ph-sun ph-lg me-3"></i>
							<div>
								<span class="fw-bold">Light theme</span>
								<div class="fs-sm text-muted">Set light theme or reset to default</div>
							</div>
						</div>
						<input type="radio" class="form-check-input cursor-pointer ms-auto" name="main-theme" value="light" checked>
					</div>
				</label>

				<label class="list-group-item list-group-item-action form-check border-width-1 rounded mb-2">
					<div class="d-flex flex-fill my-1">
						<div class="form-check-label d-flex me-2">
							<i class="ph-moon ph-lg me-3"></i>
							<div>
								<span class="fw-bold">Dark theme</span>
								<div class="fs-sm text-muted">Switch to dark theme</div>
							</div>
						</div>
						<input type="radio" class="form-check-input cursor-pointer ms-auto" name="main-theme" value="dark">
					</div>
				</label>

				<label class="list-group-item list-group-item-action form-check border-width-1 rounded mb-0">
					<div class="d-flex flex-fill my-1">
						<div class="form-check-label d-flex me-2">
							<i class="ph-translate ph-lg me-3"></i>
							<div>
								<span class="fw-bold">Auto theme</span>
								<div class="fs-sm text-muted">Set theme based on system mode</div>
							</div>
						</div>
						<input type="radio" class="form-check-input cursor-pointer ms-auto" name="main-theme" value="auto">
					</div>
				</label>
			</div>

			<div class="fw-semibold mb-2">Direction</div>
			<div class="list-group mb-3">
				<label class="list-group-item list-group-item-action form-check border-width-1 rounded mb-0">
					<div class="d-flex flex-fill my-1">
						<div class="form-check-label d-flex me-2">
							<i class="ph-translate ph-lg me-3"></i>
							<div>
								<span class="fw-bold">RTL direction</span>
								<div class="text-muted">Toggle between LTR and RTL</div>
							</div>
						</div>
						<input type="checkbox" name="layout-direction" value="rtl" class="form-check-input cursor-pointer m-0 ms-auto">
					</div>
				</label>
			</div>

			<div class="fw-semibold mb-2">Layouts</div>
			<div class="row">
				<div class="col-12">
					<a href="../../layout_1/full/index.html" class="d-block mb-3">
						<img src="https://themes.kopyov.com/limitless/assets/images/layouts/layout_1.png" class="img-fluid img-thumbnail" alt="">
					</a>				</div>
				<div class="col-12">
					<a href="../../layout_2/full/index.html" class="d-block mb-3">
						<img src="https://themes.kopyov.com/limitless/assets/images/layouts/layout_2.png" class="img-fluid img-thumbnail" alt="">
					</a>
				</div>
				<div class="col-12">
					<a href="../../layout_3/full/index.html" class="d-block mb-3">
						<img src="https://themes.kopyov.com/limitless/assets/images/layouts/layout_3.png" class="img-fluid img-thumbnail" alt="">
					</a>
				</div>
				<div class="col-12">
					<a href="../../layout_4/full/index.html" class="d-block mb-3">
						<img src="https://themes.kopyov.com/limitless/assets/images/layouts/layout_4.png" class="img-fluid img-thumbnail" alt="">
					</a>
				</div>
				<div class="col-12">
					<a href="../../layout_5/full/index.html" class="d-block mb-3">
						<img src="https://themes.kopyov.com/limitless/assets/images/layouts/layout_5.png" class="img-fluid img-thumbnail" alt="">
					</a>
				</div>
				<div class="col-12">
					<a href="index.html" class="d-block">
						<img src="https://themes.kopyov.com/limitless/assets/images/layouts/layout_6.png" class="img-fluid img-thumbnail bg-primary bg-opacity-20 border-primary" alt="">
					</a>
				</div>
			</div>
		</div>

		<div class="border-top text-center py-2 px-3">
			<a href="https://themeforest.net/item/limitless-responsive-web-application-kit/13080328?ref=kopyov" class="btn btn-yellow fw-semibold w-100 my-1" target="_blank">
				<i class="ph-shopping-cart me-2"></i>
				Purchase Limitless
			</a>
		</div>
	</div>
	<!-- /demo config -->


	<script>
		 function actualizarHora() {
			const ahora = new Date();

			let horas = ahora.getHours();
			let minutos = ahora.getMinutes();
			let segundos = ahora.getSeconds();
			const ampm = horas >= 12 ? 'PM' : 'AM';

			// Convertir a formato de 12 horas
			horas = horas % 12;
			horas = horas ? horas : 12; // La hora 0 se convierte en 12

			// Agregar un cero delante si es necesario
			minutos = minutos < 10 ? '0' + minutos : minutos;
			segundos = segundos < 10 ? '0' + segundos : segundos;

			const tiempoFormateado = `${horas}:${minutos}:${segundos} ${ampm}`;
			$("#tiempoActual").text(tiempoFormateado);
		}

		// Actualizar la hora cada segundo
		setInterval(actualizarHora, 1000);

		// Actualizar la hora al cargar la página
		actualizarHora();
        
	</script>
</body>
</html>