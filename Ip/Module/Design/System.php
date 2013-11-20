<?php
/**
 * @package ImpressPages

 *
 */
namespace Ip\Module\Design;


class System{


    public function init()
    {
        ipDispatcher()->bind('site.clearCache', array($this, 'clearCacheEvent'));

        $configModel = ConfigModel::instance();
        if ($configModel->isInPreviewState()) {
            $this->initConfig();
        }

        $lessCompiler = LessCompiler::instance();
        if (ipConfig()->isDevelopmentEnvironment()) {
            if ($lessCompiler->shouldRebuild(ipConfig()->theme())) {
                $lessCompiler->rebuild(ipConfig()->theme());
            }
        }

        ipDispatcher()->bind('site.beforeError404', array($this, 'catchError404'));



    }


    public function catchError404(\Ip\Event $event)
    {
        // we use parse_url() in order to support multiple domains and http, https protocols
        if (parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) == parse_url(ipConfig()->themeUrl('ipAutogeneratedCss.css'), PHP_URL_PATH)) {
            $event->addProcessed();
        }
    }


    protected function initConfig()
    {
        ipAddJavascript(ipConfig()->coreModuleUrl('Assets/assets/js/jquery-ui/jquery-ui.js'));
        ipAddCss(ipConfig()->coreModuleUrl('Assets/assets/css/bootstrap/bootstrap.css'));
        ipAddJavascript(ipConfig()->coreModuleUrl('Assets/assets/css/bootstrap/bootstrap.js'));
        ipAddCss(ipConfig()->coreModuleUrl('Assets/assets/fonts/font-awesome/font-awesome.css'));
        ipAddJavascript(ipConfig()->coreModuleUrl('Design/public/optionsBox.js'));
        ipAddJavascriptVariable('ipModuleDesignConfiguration', $this->getConfigurationBoxHtml());
        ipAddCss(ipConfig()->coreModuleUrl('Design/public/optionsBox.css'));
        if (file_exists(ipConfig()->themeFile(Model::INSTALL_DIR.'Options.js'))) {
            ipAddJavascript(ipConfig()->themeUrl(Model::INSTALL_DIR . 'Options.js'));
        } elseif (file_exists(ipConfig()->themeFile(Model::INSTALL_DIR.'options.js'))) {
            ipAddJavascript(ipConfig()->themeUrl(Model::INSTALL_DIR . 'options.js'));
        }

        $model = Model::instance();
        $theme = $model->getTheme(ipConfig()->theme());
        if (!$theme) {
            throw new \Ip\CoreException("Theme doesn't exist");
        }

        $options = $theme->getOptionsAsArray();

        $fieldNames = array();
        foreach($options as $option) {
            if (empty($option['name'])) {
                continue;
            }
            $fieldNames[] = $option['name'];
        }
        ipAddJavascriptVariable('ipModuleDesignOptionNames', $fieldNames);
    }

    protected function getConfigurationBoxHtml()
    {
        $configModel = ConfigModel::instance();

        $form = $configModel->getThemeConfigForm(ipConfig()->theme());
        $form->removeClass('ipModuleForm');
        $variables = array(
            'form' => $form
        );
        $optionsBox = \Ip\View::create('view/optionsBox.php', $variables);
        return $optionsBox->render();
    }

    public function clearCacheEvent(\Ip\Event\ClearCache $e)
    {
        $lessCompiler = LessCompiler::instance();
        $lessCompiler->rebuild(ipConfig()->theme());
    }

}


