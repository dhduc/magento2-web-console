<?php
/**
 * Created by PhpStorm.
 * User: vagrant
 * Date: 4/29/16
 * Time: 12:58 AM
 */
namespace M2Console\Controllers;

use Magento\Framework\App\Bootstrap;
use Magento\Framework\App\Filesystem\DirectoryList;

class CodeRunner extends AbstractController
{
    protected function execute()
    {
        $baseDir = empty($_COOKIE['BASE_DIR']) ? false : $_COOKIE['BASE_DIR'];

        if (!$baseDir) {
            echo 'Please select a Magento 2 project';
            exit();
        }

        require $baseDir . '/app/bootstrap.php';

        $areaCodes = ['frontend', 'adminhtml'];
        $areaCode = empty($_COOKIE['AREA_CODE']) ? $areaCodes[0] : $_COOKIE['AREA_CODE'];

        $params = $_SERVER;
        $params[Bootstrap::INIT_PARAM_FILESYSTEM_DIR_PATHS] = [
            DirectoryList::PUB         => [DirectoryList::URL_PATH => ''],
            DirectoryList::MEDIA       => [DirectoryList::URL_PATH => 'media'],
            DirectoryList::STATIC_VIEW => [DirectoryList::URL_PATH => 'static'],
            DirectoryList::UPLOAD      => [DirectoryList::URL_PATH => 'media/upload'],
        ];

        $bootstrap = \Magento\Framework\App\Bootstrap::create(BP, $params);
        $objectManager = $bootstrap->getObjectManager();
        $objectManager->get('Magento\Framework\App\State')->setAreaCode($areaCode);
        $objectManager->configure($objectManager->get('\Magento\Framework\ObjectManager\ConfigLoaderInterface')->load($areaCode));
        $area = $objectManager->create(
            'Magento\Framework\App\AreaInterface',
            ['areaCode' => $areaCode]
        );
        $area->load(\Magento\Framework\App\Area::PART_DESIGN);

        $code = $_POST['code'];

        $output = eval($code);

        if ($output === '') {
            $output = '[empty string]';
        } elseif ($output === null) {
            $output = '[null]';
        } elseif (is_bool($output)) {
            $output = $output ? '[boolean true]' : '[boolean false]';
        } elseif (is_scalar($output) || is_array($output)) {
            $output = print_r($output, true);
        } elseif (is_object($output)) {
            $output = sprintf('[object of type %s]', get_class($output));
        } else {
            $output = '[unknown type]';
        }

        echo $output;
        exit();
    }
}