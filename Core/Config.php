<?php


namespace TopConcepts\Klarna\Core;


class Config extends Config_parent
{
    protected function setDefaults()
    {
        parent::setDefaults();
        $sessionStartRules = $this->getConfigParam('aRequireSessionWithParams');
        $sessionStartRules['fnc']['startSessionAjax'] = true;
        $this->setConfigParam('aRequireSessionWithParams', $sessionStartRules);
    }
}