<?php
if (!isConnect('admin')) {
    throw new Exception('{{401 - Accès non autorisé}}');
}
$plugin = plugin::byId('publiemeteo'); // Obtenir l'identifiant du plugin
sendVarToJS('eqType', $plugin->getId());
$eqLogics = eqLogic::byType($plugin->getId());
?>

<div class="col-xs-12 eqLogic">
    <br />
    <div class="input-group pull-right" style="display:inline-flex">
        <span class="input-group-btn">
            <a class="btn btn-sm btn-success eqLogicAction bt_savepubliemeteo" id=""><i class="fas fa-check-circle"></i> {{Sauvegarder}}r</a><br>
        </span>
    </div>

    <ul class="nav nav-tabs" role="tablist">
        <li role="presentation"><a href="#commandtab" aria-controls="profile" role="tab" data-toggle="tab"><i class="fas fa-list-alt"></i> {{Indicateurs Météos}}</a></li>
    </ul>
</div>

<div class="tab-content">
    <div role="tabpanel" class="tab-pane active" id="configpubliemeteo">
        <div class="form-group">
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
                            <td>
                                <?php echo $description; ?>
                            <td>
                                <input type="hidden" id="cmdSupportId_<?php echo $indice; ?>" disabled value="<?php if (is_object($cmd)) print($cmd->getId()); ?>"><input class="form-control" id="cmdSupportHumanName_<?php echo $indice; ?>" placeholder="Commande Info associée" style="width: 80%; display : inline-block;" disabled value="<?php if (is_object($cmd)) print($cmd->getHumanName()); ?>">
                            </td>
                            <td>
                                <a class="btn btn-default listEquipement roundedRight" id="bt_selectTempCmd" data-input="<?php echo $indice; ?>"><i class="fas fa-list-alt"></i></a>
                                <a class="btn btn-default cursor delEquipement" data-input="<?php echo $indice; ?>"><i class="fas fa-trash"></i></a>
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

<?php
include_file('desktop', 'publiemeteo', 'js', 'publiemeteo');
include_file('core', 'plugin.template', 'js');
?>