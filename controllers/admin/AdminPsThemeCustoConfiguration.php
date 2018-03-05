<?php
/**
* 2007-2018 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Open Software License (OSL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/osl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
* @author PrestaShop SA <contact@prestashop.com>
* @copyright 2007-2018 PrestaShop SA
* @license http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
* International Registered Trademark & Property of PrestaShop SA
**/

class AdminPsThemeCustoConfigurationController extends ModuleAdminController
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Initialize the content by adding Boostrap and loading the TPL
     *
     * @param none
     * @return none
     */
    public function initContent()
    {
        parent::initContent();
        $oContext = Context::getContext();

        $this->module->setMedia();
        $this->setTemplate( $this->module->template_dir.'page.tpl');
        $this->context->smarty->assign(array(
            'bootstrap'         =>  1,
            'configure_type'    => 'configuration',
            'modulesList'       => $this->getModulesByHook('displayHome'),
            'modulesPage'       => $oContext->link->getAdminLink('AdminModules'),
        ));

        Media::addJsDef(array(
            'wireframeUri' => '/modules/psthemecusto/views/img/wireframe/wireframe_'
        ));
    }

    /**
     * Initialize the content by adding Boostrap and loading the TPL
     *
     * @param string $sHookName
     * @return array $aModulesList
     */
    public function getModulesByHook($sHookName)
    {
        $oContext = Context::getContext();
        $sSql = '   SELECT m.id_module, m.name, hm.position, m.active
                    FROM `'._DB_PREFIX_.'hook_module` hm
                    INNER JOIN `'._DB_PREFIX_.'hook` h ON h.id_hook = hm.id_hook
                    INNER JOIN `'._DB_PREFIX_.'module` m ON m.id_module = hm.id_module
                    WHERE 1
                    AND h.name = "'.pSQL($sHookName).'"
                    ORDER BY hm.position ASC';
        $aModulesList = Db::getInstance()->executeS($sSql);
        foreach ($aModulesList as $aModule) {
            $aModuleInstance = Module::getInstanceByName($aModule['name']);
            $aModuleFinalList[$aModule['position']]['name'] = $aModuleInstance->name;
            $aModuleFinalList[$aModule['position']]['displayName'] = $aModuleInstance->displayName;
            $aModuleFinalList[$aModule['position']]['description'] = $aModuleInstance->description;
            $aModuleFinalList[$aModule['position']]['controller_name'] = (isset($aModuleInstance->controller_name)? $aModuleInstance->controller_name : '');
            $aModuleFinalList[$aModule['position']]['url'] = $oContext->link->getAdminLink('AdminModules', true, false, array('configure' => $aModuleInstance->name));
            $aModuleFinalList[$aModule['position']]['logo'] = '/modules/'.$aModuleInstance->name.'/logo.png';
            unset($aModuleInstance);
        }
        return $aModuleFinalList;
    }

}