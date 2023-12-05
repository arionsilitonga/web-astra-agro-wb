<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-success elevation-4">
	<!-- Brand Logo -->
	<a href="./home.php" class="brand-link">
		<img src="./MockUp/icon/logo.png" alt="AAL Logo" class="brand-image img-circle elevation-3" style="opacity: .8;">
		<span class="brand-text font-weight-light">PT Astra Agro Lestari</span>
	</a>

	<!-- Sidebar -->
	<div class="sidebar">
		<!-- Sidebar user -->
		<div class="user-panel mt-3 pb-3 mb-3 d-flex">
			<div class="image"><i class="fas fa-user img-circle elevation-2 p-2" style="background-color: white;"></i></div>
			<div class="info">
				<a href="#" class="d-block"><?= session()->get('name') ?></a>
				<a href="/change-password">Change Password</a>
			</div>
		</div>

		<?=$this->include('layouts/side-menu')?>
	</div>
</aside>