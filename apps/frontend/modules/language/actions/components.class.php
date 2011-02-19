<?php
class languageComponents extends sfComponents {
  public function executeLanguage(sfWebRequest $request)
  {
    $pathinfo = $this->getContext()->getRouting()->parse($request->getPathInfo());

    $this->routename = $this->getContext()->getRouting()->getCurrentRouteName();
    unset($pathinfo['module']); unset($pathinfo['action']); unset($pathinfo['_sf_route']);
    
    $languages = sfConfig::get('app_language_languages', array());

    foreach ($languages as $language => $config) {
      $culture = isset($config['sf_culture']) ? $config['sf_culture'] : $language;
      $name = isset($config['name']) ? $config['name'] : $language;
      $flag = isset($config['flag']) ? $config['flag'] : '/images/flags/'.$language.'.png';

      $pathinfo['sf_culture'] = $culture;

      $query = '';
      foreach ($pathinfo as $k => $v) {
	$query .= $query == '' ? '?' : '&';
	$query .= $k . '=' . $v;
      }

      $tmplanguages[$language]['query'] = $query;
      $tmplanguages[$language]['name'] = $name;
      $tmplanguages[$language]['flag'] = $flag;
      $tmplanguages[$language]['sf_culture'] = $culture;
    }

    $this->languages = isset($tmplanguages) ? $tmplanguages : array();
  }
}