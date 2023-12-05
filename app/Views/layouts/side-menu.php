<?php

use App\Filters\UsersAuthFilter;
use CodeIgniter\Router\Router;
use CodeIgniter\View\Parser;
use CodeIgniter\View\View;
use Config\Services;

/**
 * @var View $this
 * @var Parser $parser
 * @var Router $router
 */

$menu = UsersAuthFilter::$allAuths;

function drawMenu(array $items, $cur_controller, $cur_action, &$aktif){
	$aktif = false;
	$bklReturn = '';

	foreach ($items as $item) {
		$subItems = '';
		$liClass = '';
		if (isset($item['items'])) {
			$item['url'] = '#';
			$item['label'] .= ' <i class="right fas fa-angle-left"></i>';
			$subItemAktif = false;
			$subItems = drawMenu($item['items'], $cur_controller, $cur_action, $subItemAktif);
			if ($subItemAktif) {
				$item['active'] = 'active';
				$aktif = true;
				$liClass = 'menu-open';
			}
		} else {
			if (isset($item['controller'])) {
				$action = $item['action'] ?? 'index';
				$routeTo = $item['controller'] . '::' . $action;
				if (UsersAuthFilter::can($routeTo)) {
					$item['url'] = route_to($routeTo);
	
					if (($item['controller'] == $cur_controller) && ($item['action'] == $cur_action)) {
						$item['active'] = 'active';
						$aktif = true;
					}	
				} else {
					$item['url'] = '#';
				}
			}
		}

		//$parser = Services::parser();
		//$itmStr = $parser->setData($item)->render('layouts/side-menu-item', null, true);

		if ((($item['url'] ?? '#') != '#') || ($subItems != '')) {
			$label = '<p>'. ($item['label'] ?? '') .'</p>';
			$icon = '<i class="nav-icon fas fa-'. ($item['icon'] ?? '') .'"></i>';
			$itmStr = '<a href="'. ($item['url'] ?? '#') .'" class="nav-link '. ($item['active'] ?? '') .'">'. $icon . $label .'</a>';
			if ($subItems !== ''){
				$subItems = '<ul class="nav nav-treeview">' . $subItems . '</ul>';
			}
			$bklReturn .= "<li class='nav-item $liClass'>$itmStr $subItems</li>";	
		}
	}
	return $bklReturn;
}
?>

<!-- Sidebar Menu -->
<nav class="mt-2">
	<ul class="nav nav-pills nav-sidebar flex-column nav-child-indent" data-widget="treeview" role="menu" data-accordion="false">
	<?=drawMenu($menu, $this->controller, $this->method, $aktif)?>
	</ul>
</nav>