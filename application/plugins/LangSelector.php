<?php

class Plugins_LangSelector extends Zend_Controller_Plugin_Abstract
{
    public function preDispatch(Zend_Controller_Request_Abstract $request)
    {
        $registry = Zend_Registry::getInstance();
        $translate = $registry->get('Zend_Translate');
        $currLocale = $translate->getLocale();

        // Create Session block and save the locale
        $session = new Zend_Session_Namespace('session');

        $lang = $request->getParam('lang','');
        
        // Set languages here
        switch($lang) {
            case "de":
                $langLocale = 'de_DE'; break;  
            case "en":
                $langLocale = 'en_EN'; break;
            
            default:
                /**
                 * Get a previously set locale from session or set
                 * the current application wide locale (set in
                 * Bootstrap)if not.
                 */
                $langLocale = isset($session->lang) ? $session->lang : $currLocale;
        }

        $newLocale = new Zend_Locale();
        $newLocale->setLocale($langLocale);
        $registry->set('Zend_Locale', $newLocale);

        $translate->setLocale($langLocale);
        $session->lang = $langLocale;

        // Save the modified translate back to registry
        $registry->set('Zend_Translate', $translate);
    }
}