<?php
if (!isConnect('admin')) {
	throw new Exception('{{401 - Accès non autorisé}}');
}
?>

<div class="tab-content">
	<div role="tabpanel" class="tab-pane active" id="configpubliemeteo">
		<br/>
			<a class="btn btn-success pull-right bt_savepubliemeteo" id=""><i class="fa fa-floppy-o"></i> Sauvegarder</a><br>
		<br>
		<div class="form-group">
			<label class="col-lg-12 control-label">Indicateurs Météos</label>
			<table class="table table-bordered table-condensed" id="cmdList">
				<thead>
					<tr>
						<th>{{Indicateur}}</th>
						<th>{{Commande associé}}</th>
						<th></th>
					</tr>
				</thead>
				<tbody>
					<?php
					foreach (publiemeteo::getIndicateurList() as $indice => $description) {
						$cmd = cmd::byId(config::byKey($indice, 'publiemeteo'));
						?>
						<tr class="publiemeteocmd" data-id="<?php echo $indice; ?>">
							<td><?php echo $description; ?>
							<td><input type="hidden"" id="cmdSupportId_<?php echo $indice; ?>" disabled value="<?php if ( is_object($cmd) ) print($cmd->getId()); ?>"><input class="form-control" id="cmdSupportHumanName_<?php echo $indice; ?>" placeholder="Commande Info associée" style="width: 80%; display : inline-block;" disabled value="<?php if ( is_object($cmd) ) print($cmd->getHumanName()); ?>"></td>
							<td>
							<a class="btn btn-default cursor listEquipement" data-input="<?php echo $indice; ?>"><i class="fa fa-list-alt"></i></a>
							<a class="btn btn-default cursor delEquipement" data-input="<?php echo $indice; ?>"><i class="fa fa-trash-o"></i></a>
							</td>
						</tr>
						<?php
					}
					?>
				</tbody>
			</table>
		</div>
	</div>
</div>

<?php include_file('desktop', 'publiemeteo', 'js', 'publiemeteo');?>