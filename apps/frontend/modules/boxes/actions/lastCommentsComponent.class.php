<?php

class lastCommentsComponent extends sfComponent {
  public function execute( $request) {
    $limit = sfConfig::get('app_boxes_last_comments_num');
    $default_culture = sfConfig::get('default_culture'); //This might not work...
    $this->comments = ReplayCommentPeer::getLastComments($request->getParameter('sf_culture', $default_culture), $limit);
  }
}