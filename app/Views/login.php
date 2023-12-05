<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Weight Bridge | Log in</title>

	<!-- Google Font: Source Sans Pro -->
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
	<!-- Font Awesome -->
	<link rel="stylesheet" href="/MockUp/plugins/fontawesome-free/css/all.min.css">
	<!-- icheck bootstrap -->
	<link rel="stylesheet" href="/MockUp/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
	<!-- Theme style -->
	<link rel="stylesheet" href="/MockUp/dist/css/adminlte.min.css">
</head>

<body class="hold-transition login-page">
	<div class="card">
	<div class="row">
	<button type="submit" class="btn btn-primary btn-block">SHUTDOWN</button>
	</div>
	<div class="card">
	<div class="row">
	<button type="submit" class="btn btn-primary btn-block">RESTART</button>
	</div>
	<div class="login-box">
		<div class="card">
			<div class="card-body login-card-body">
				<div class="login-logo">
					<a href="/login">
						<img src="/MockUp/icon/logo.png"><br>
						<b>PT. ASTRA AGRO LESTARI Tbk</b>
					</a>
				</div>
				
				<?php if (!empty(session()->getFlashdata('error'))) : ?>
					<div class="alert alert-warning alert-dismissible fade show" role="alert">
						<?php echo session()->getFlashdata('error'); ?>
					</div>
				<?php endif; ?>
				<form action="/login" method="post">
					<div class="input-group mb-3">
						<input name="email" type="email" class="form-control" placeholder="Email">
						<div class="input-group-append">
							<div class="input-group-text">
								<span class="fas fa-envelope"></span>
							</div>
						</div>
					</div>
					<div class="input-group mb-3">
						<input name="password" type="password" class="form-control" placeholder="Password">
						<div class="input-group-append">
							<div class="input-group-text">
								<span class="fas fa-lock"></span>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-4 offset-8">
							<button type="submit" class="btn btn-primary btn-block">Log In</button>
						</div>
						<!-- /.col -->
					</div>
				</form>
			</div>
		</div>
	</div>
</body>

</html>