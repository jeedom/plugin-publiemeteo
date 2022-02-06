<?php
if (!isConnect('admin')) {
    throw new Exception('{{401 - Accès non autorisé}}');
}
// Déclaration des variables obligatoires
$plugin = plugin::byId('publiemeteo'); // Obtenir l'identifiant du plugin
sendVarToJS('eqType', $plugin->getId());
$eqLogics = eqLogic::byType($plugin->getId());
?>

<div class="row row-overflow">
    <!-- Page d'accueil du plugin -->
    <div class="col-xs-12 eqLogicThumbnailDisplay">
        <legend><i class="fas fa-cog"></i> {{Gestion}}</legend>
        <div class="eqLogicThumbnailContainer">
            <div class="cursor eqLogicAction logoSecondary" data-action="gotoPluginConf">
                <i class="fas fa-wrench"></i><br>
                <span>{{Configuration}}</span>
            </div>
        </div>
    </div>
</div>
<!-- Page de présentation de l'équipement -->
<div class="col-xs-12 eqLogic">
    <!-- barre de gestion de l'équipement -->
    <br />
    <div class="input-group pull-right" style="display:inline-flex">
        <span class="input-group-btn">
            <a class="btn btn-sm btn-success eqLogicAction bt_savepubliemeteo roundedLeft roundedRight" id=""><i class="fas fa-check-circle"></i> {{Sauvegarder}}</a><br>
        </span>
    </div>

    <ul class="nav nav-tabs" role="tablist">
        <li role="presentation"><a href="#commandtab" aria-controls="profile" role="tab" data-toggle="tab"><i class="fas fa-list-alt"></i> {{Indicateurs Météos}}</a></li>
    </ul>
    <div class="form-group">
        <table class="tab-pane" id="cmdList">
            <thead>
                <tr>

                    <th style="min-width:200px;width:350px;">{{Indicateur}}</th>
                    <th style="min-width:700px;width:1550px;">{{Commande associé}}
                    </th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach (publiemeteo::getIndicateurList() as $indice => $description) {
                    $cmd = cmd::byId(config::byKey($indice, 'publiemeteo'));
                ?>
                    <tr class=" publiemeteocmd" data-id="<?php echo $indice; ?>">
                        <td>
                            <?php echo $description; ?>
                        <td>
                            <input type="hidden" id="cmdSupportId_<?php echo $indice; ?>" disabled value="<?php if (is_object($cmd)) print($cmd->getId()); ?>"><input class="form-control" id="cmdSupportHumanName_<?php echo $indice; ?>" placeholder="Commande Info associée" style="width: 80%; display : inline-block;" disabled value="<?php if (is_object($cmd)) print($cmd->getHumanName()); ?>">
                        </td>
                        <td>
                            <a class="btn btn-default listCmdActionOther roundedRight" title=" Rechercher une commande" data-input="<?php echo $indice; ?>"><i class="fas fa-list-alt"></i></a>
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

<?php
include_file('desktop', 'publiemeteo', 'js', 'publiemeteo');
include_file('core', 'plugin.template', 'js');
?>