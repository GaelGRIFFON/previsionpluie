<?php

/* This file is part of Jeedom.
 *
 * Jeedom is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Jeedom is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Jeedom. If not, see <http://www.gnu.org/licenses/>.
 */

/* * ***************************Includes********************************* */
require_once dirname(__FILE__) . '/../../../../core/php/core.inc.php';

class previsionpluie extends eqLogic {

    public static function pull() {
		foreach (eqLogic::byType('previsionpluie') as $previsionpluie) {
			$previsionpluie->getInformations();
			$mc = cache::byKey('previsionpluieWidgetmobile' . $previsionpluie->getId());
			$mc->remove();
			$mc = cache::byKey('previsionpluieWidgetdashboard' . $previsionpluie->getId());
			$mc->remove();
			$previsionpluie->toHtml('mobile');
			$previsionpluie->toHtml('dashboard');
			$previsionpluie->refreshWidget();
		}
	}

	public function postUpdate() {
		log::add('previsionpluie', 'debug', 'postUpdate');
		
		$previsionpluieCmd = $this->getCmd(null, 'prevTexte');
		if (!is_object($previsionpluieCmd)) {
			$previsionpluieCmd = new previsionpluieCmd();
		}
		$previsionpluieCmd->setName(__('Previsions Textuelles', __FILE__));
		$previsionpluieCmd->setEqLogic_id($this->id);
		$previsionpluieCmd->setLogicalId('prevTexte');
		$previsionpluieCmd->setType('info');
		$previsionpluieCmd->setSubType('other');
		$previsionpluieCmd->setEventOnly(1);
		$previsionpluieCmd->setIsVisible(1);
		$previsionpluieCmd->save();

		$previsionpluieCmd = $this->getCmd(null, 'lastUpdate');
		if (!is_object($previsionpluieCmd)) {
			$previsionpluieCmd = new previsionpluieCmd();
		}
		$previsionpluieCmd->setName(__('Dernière mise à jour', __FILE__));
		$previsionpluieCmd->setEqLogic_id($this->id);
		$previsionpluieCmd->setLogicalId('lastUpdate');
		$previsionpluieCmd->setType('info');
		$previsionpluieCmd->setSubType('other');
		$previsionpluieCmd->setEventOnly(1);
		$previsionpluieCmd->setIsVisible(1);
		$previsionpluieCmd->save();

		for($i=0; $i <= 11; $i++){

			$previsionpluieCmd = $this->getCmd(null, 'prev' . $i*5);
			if (!is_object($previsionpluieCmd)) {
				$previsionpluieCmd = new previsionpluieCmd();
			}
			$previsionpluieCmd->setName(__('Prévision à ' . ($i*5) . '-' . ($i*5+5), __FILE__));
			$previsionpluieCmd->setEqLogic_id($this->id);
			$previsionpluieCmd->setLogicalId('prev' . $i*5);
			$previsionpluieCmd->setType('info');
			$previsionpluieCmd->setSubType('other');
			$previsionpluieCmd->setEventOnly(1);
			$previsionpluieCmd->setIsVisible(1);
			$previsionpluieCmd->save();

			$previsionpluieCmd = $this->getCmd(null, 'prevColor' . $i*5);
			if (!is_object($previsionpluieCmd)) {
				$previsionpluieCmd = new previsionpluieCmd();
			}
			$previsionpluieCmd->setName(__('Color - Prévision à ' . ($i*5) . '-' . ($i*5+5), __FILE__));
			$previsionpluieCmd->setEqLogic_id($this->id);
			$previsionpluieCmd->setLogicalId('prevColor' . $i*5);
			$previsionpluieCmd->setType('info');
			$previsionpluieCmd->setSubType('other');
			$previsionpluieCmd->setEventOnly(1);
			$previsionpluieCmd->setIsVisible(1);
			$previsionpluieCmd->save();

			$previsionpluieCmd = $this->getCmd(null, 'prevText' . $i*5);
			if (!is_object($previsionpluieCmd)) {
				$previsionpluieCmd = new previsionpluieCmd();
			}
			$previsionpluieCmd->setName(__('Text - Prévision à ' . ($i*5) . '-' . ($i*5+5), __FILE__));
			$previsionpluieCmd->setEqLogic_id($this->id);
			$previsionpluieCmd->setLogicalId('prevText' . $i*5);
			$previsionpluieCmd->setType('info');
			$previsionpluieCmd->setSubType('other');
			$previsionpluieCmd->setEventOnly(1);
			$previsionpluieCmd->setIsVisible(1);
			$previsionpluieCmd->save();
		}
	}
	
	public function postSave() {
		log::add('previsionpluie', 'debug', 'postSave');
		
		foreach (eqLogic::byType('previsionpluie') as $previsionpluie) {
			$previsionpluie->getInformations();
		}
	}
	
 	public function toHtml($_version = 'dashboard') 
	{
		$_version = jeedom::versionAlias($_version);
        $mc = cache::byKey('previsionpluieWidget' . $_version . $this->getId());
        if ($mc->getValue() != '') {
            return $mc->getValue();
        }
		$replace = array(
			'#id#' => $this->getId(),
			'#name#' => ($this->getIsEnable()) ? $this->getName() : '<del>' . $this->getName() . '</del>',
			'#background_color#' => $this->getBackgroundColor($_version),
			'#eqLink#' => $this->getLinkToConfiguration(),
			'#ville#' => $this->getConfiguration('ville')
		);
		
		$prevTexte = $this->getCmd(null,'prevTexte');
		$replace['#prevTexte#'] = (is_object($prevTexte)) ? $prevTexte->execCmd() : '';
		$replace['#prevTexte_display#'] = (is_object($prevTexte) && $prevTexte->getIsVisible()) ? "#prevTexte_display#" : "none";
		
		$lastUpdate = $this->getCmd(null,'lastUpdate');
		$replace['#lastUpdate#'] = (is_object($lastUpdate)) ? $lastUpdate->execCmd() : '';

		for($i=0; $i <= 11; $i++){
			$prev = $this->getCmd(null,'prev' . $i*5);
			$replace['#prev' . ($i*5) . '#'] = (is_object($prev)) ? $prev->execCmd() : '';

			$prev = $this->getCmd(null,'prevColor' . $i*5);
			$replace['#prev' . ($i*5) . 'Color#'] = (is_object($prev)) ? '#' . $prev->execCmd() : '#ffffff';

			$prev = $this->getCmd(null,'prevText' . $i*5);
			$replace['#prev' . ($i*5) . 'Text#'] = (is_object($prev)) ? $prev->execCmd() : 'Indéterminé';
		}


		$parameters = $this->getDisplay('parameters');
        if (is_array($parameters)) {
            foreach ($parameters as $key => $value) {
                $replace['#' . $key . '#'] = $value;
            }
        }
		
		return template_replace($replace, getTemplate('core', $_version, 'previsionpluie','previsionpluie'));
		cache::set('previsionpluieWidget' . $_version . $this->getId(), $html, 0);
        return $html;
	}

    public function getInformations() {
    	$prevPluieJson = file_get_contents('http://www.meteofrance.com/mf3-rpc-portlet/rest/pluie/' . $this->getConfiguration('ville')); 
		$prevPluieData = json_decode($prevPluieJson, true); 

    	$prevTexte = "";
    	foreach($prevPluieData['niveauPluieText'] as $prevTexteItem){
    		$prevTexte .= '<br>' . $prevTexteItem;
    	}

    	log::add('previsionpluie', 'debug', 'DataCadran: ' . $prevPluieData['dataCadran'][0]['niveauPluie']);

		$prevTexteCmd = $this->getCmd(null,'prevTexte');
			if(is_object($prevTexteCmd)){
				log::add('previsionpluie', 'debug', 'prevTexte: ' . $prevTexte);
				$prevTexteCmd->event($prevTexte);
			}

		$lastUpdateCmd = $this->getCmd(null,'lastUpdate');
			if(is_object($lastUpdateCmd)){
				log::add('previsionpluie', 'debug', 'lastUpdate: ' . $prevPluieData['lastUpdate']);
				$lastUpdateCmd->event($prevPluieData['lastUpdate']);
			}

		for($i=0; $i <= 11; $i++){
			$prevCmd = $this->getCmd(null,'prev' . $i*5);
			if(is_object($prevCmd)){
				log::add('previsionpluie', 'debug', 'prev' . $i*5 . ': ' . $prevPluieData['dataCadran'][$i]['niveauPluie']);
				$prevCmd->event($prevPluieData['dataCadran'][$i]['niveauPluie']);
			}

			$prevCmd = $this->getCmd(null,'prevColor' . $i*5);
			if(is_object($prevCmd)){
				log::add('previsionpluie', 'debug', 'prevColor' . $i*5 . ': ' . $prevPluieData['dataCadran'][$i]['color']);
				$prevCmd->event($prevPluieData['dataCadran'][$i]['color']);
			}

			$prevCmd = $this->getCmd(null,'prevText' . $i*5);
			if(is_object($prevCmd)){
				log::add('previsionpluie', 'debug', 'prevText' . $i*5 . ': ' . $prevPluieData['dataCadran'][$i]['niveauPluieText']);
				$prevCmd->event($prevPluieData['dataCadran'][$i]['niveauPluieText']);
			}
		}
	}
}

class previsionpluieCmd extends cmd {

 /*     * *********************Methode d'instance************************* */
	public function execute($_options = null) {

	}
}

?>