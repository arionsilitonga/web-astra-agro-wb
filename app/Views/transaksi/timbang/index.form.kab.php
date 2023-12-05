<input id="timbang-kabraw" type="hidden" name="kabraw" value="<?= $tr_wb['kabraw'] ?? '' ?>">
<label for="timbang-DataKAB">Data KAB</label>
<table id="timbang-DataKAB" style="width: 100%;">
	<thead>
		<tr>
			<th>No</th>
			<th>Afdeling</th>
			<th>Blok</th>
			<th>Janjang</th>
		</tr>
	</thead>
	<tbody>
		<?php
		$jjg = 0;
		foreach ($tr_kab as $index => $noc) {
			foreach ($noc as $field => $value) {
		?>
				<input type="hidden" name="noc[<?= $index ?>][<?= $field ?>]" value="<?= $value ?>" />
			<?php
			}
			?>
			<tr>
				<td><?= ($index + 1) ?></td>
				<td><?= $noc['nocafd'] ?></td>
				<td><?= $noc['nocblock'] ?></td>
				<td><?= $noc['jjg'] ?></td>
			</tr>
		<?php
			$jjg += $noc['jjg'];
		}; ?>
	</tbody>
	<tfoot>
		<tr>
			<td colspan="3">Total JJG</td>
			<td colspan="2" id="total-jjg"><?= $jjg ?></td>
		</tr>
	</tfoot>
</table>