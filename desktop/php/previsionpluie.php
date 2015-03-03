<?php
if (!isConnect('admin')) {
    throw new Exception('{{401 - Accès non autorisé}}');
}

global $listCmdPrevisionPluie;

sendVarToJS('eqType', 'previsionpluie');

?>

<div class="row row-overflow">
    <div class="col-lg-2">
        <div class="bs-sidebar">
            <ul id="ul_eqLogic" class="nav nav-list bs-sidenav">
                <a class="btn btn-default eqLogicAction" style="width : 100%;margin-top : 5px;margin-bottom: 5px;" data-action="add"><i class="fa fa-plus-circle"></i> {{Ajouter un équipement}}</a>
                <li class="filter" style="margin-bottom: 5px;"><input class="filter form-control input-sm" placeholder="{{Rechercher}}" style="width: 100%"/></li>
                <?php
                foreach (eqLogic::byType('previsionpluie') as $eqLogic) {
                    echo '<li class="cursor li_eqLogic" data-eqLogic_id="' . $eqLogic->getId() . '"><a>' . $eqLogic->getHumanName() . '</a></li>';
                }
                ?>
            </ul>
        </div>
    </div>
    <div class="col-lg-10 eqLogic" style="border-left: solid 1px #EEE; padding-left: 25px;display: none;">
       <div class="row">
            <div class="col-lg-8">
 
	<form class="form-horizontal">
            <fieldset>
                <legend>{{Général}}</legend>
                <div class="form-group">
                    <label class="col-lg-4 control-label">{{Nom de l'équipement Prévision Pluie}}</label>
                    <div class="col-lg-3">
                        <input type="text" class="eqLogicAttr form-control" data-l1key="id" style="display : none;" />
                        <input type="text" class="eqLogicAttr form-control" data-l1key="name" placeholder="{{Nom de l'équipement Prévision Pluie}}"/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-lg-4 control-label" >{{Objet parent}}</label>
                    <div class="col-lg-3">
                        <select id="sel_object" class="eqLogicAttr form-control" data-l1key="object_id">
                            <option value="">{{Aucun}}</option>
                            <?php
                            foreach (object::all() as $object) {
                                echo '<option value="' . $object->getId() . '">' . $object->getName() . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-lg-4 control-label">{{Catégorie}}</label>
                    <div class="col-lg-8">
                        <?php
                        foreach (jeedom::getConfiguration('eqLogic:category') as $key => $value) {
                            echo '<label class="checkbox-inline">';
                            echo '<input type="checkbox" class="eqLogicAttr" data-l1key="category" data-l2key="' . $key . '" />' . $value['name'];
                            echo '</label>';
                        }
                        ?>

                    </div>
                </div>
                <div class="form-group">
                    <label class="col-lg-4 control-label" >{{Activer}}</label>
                    <div class="col-lg-1">
                        <input type="checkbox" class="eqLogicAttr" data-l1key="isEnable" checked/>
                    </div>
                    <label class="col-lg-4 control-label" >{{Visible}}</label>
                    <div class="col-lg-1">
                        <input type="checkbox" class="eqLogicAttr" data-l1key="isVisible" checked/>
                    </div>
				</div>
                <div class="form-group">
                    <label class="col-lg-4 control-label" >{{Ville}}</label>
                    <div class="col-lg-2">
                        <input class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="villeNom" type="text" placeholder="{{Ville}}" id="mfVilleNom" disabled>
                    </div>
                    <div class="col-lg-2">
                        <input class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="ville" type="text" placeholder="{{ID Ville}}" id="mfVilleId" disabled>
                    </div>
                    <div class="col-lg-2">
                       <a class="btn btn-default" id='btnSearchCity'><i class="fa fa-search"></i> {{Trouver la ville}}</a>
                    </div>
                </div>
            </fieldset> 
        </form>
            </div>
            <div class="col-lg-4">
                <form class="form-horizontal">
                    <fieldset>
                        <legend>{{Informations}}</legend>

                    </fieldset> 
                </form>
            </div>
        </div>
        <legend>Commandes</legend>
        <table id="table_cmd" class="table table-bordered table-condensed">
            <thead>
                <tr>
                    <th style="width: 5%;">{{Id}}</th>
					<th style="width: 25%;">{{Nom}}</th>
					<th style="width: 60%;">{{Valeur}}</th>
					<th style="width: 10%;">{{Afficher}}</th>
                </tr>
            </thead>
            <tbody>

            </tbody>
        </table>

        <form class="form-horizontal">
            <fieldset>
                <div class="form-actions">
                    <a class="btn btn-danger eqLogicAction" data-action="remove"><i class="fa fa-minus-circle"></i> {{Supprimer}}</a>
                    <a class="btn btn-success eqLogicAction" data-action="save"><i class="fa fa-check-circle"></i> {{Sauvegarder}}</a>
                </div>
            </fieldset>
        </form>

    </div>
</div>



<?php include_file('desktop', 'previsionpluie', 'js', 'previsionpluie'); ?>
<?php include_file('core', 'plugin.template', 'js'); ?>
