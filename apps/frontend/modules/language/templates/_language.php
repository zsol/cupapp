<div class="language_switcher">
  <?php echo __('Other languages') . ':' ?>
  <?php if (sfConfig::get('app_language_use_flags') == false): ?>
    <?php foreach($languages as $language => $conf): ?>
      <?php echo link_to($conf['name'], 
	                 '@' . $routename . $conf['query'], 
                         $conf['sf_culture'] == $sf_user->getCulture() ? array('class' => 'active') : array()) ?>
    <?php endforeach; ?>
  <?php else: ?>
    <?php foreach($languages as $language => $conf): ?>
      <div class="language<?php echo $conf['sf_culture'] == $sf_user->getCulture() ? ' active' : ''?>">
        <?php echo link_to(image_tag($conf['flag'],
                                     array('alt' => $conf['name'])), 
                           '@' . $routename . $conf['query']) ?>
      </div>
    <?php endforeach; ?>
  <?php endif; ?>
</div>