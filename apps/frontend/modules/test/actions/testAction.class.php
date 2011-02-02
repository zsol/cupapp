<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of testActionclass
 *
 * @author Eshton
 */
class testAction extends sfAction {
    public function execute( $request ) {
        
        $testFile = sfConfig::get('sf_web_dir').'\uploads\replay\test.SC2Replay';

        $mpq = new MPQFile($testFile,true,0);
        $repData = $mpq->parseReplay();

        $this->repData = $repData;

        $this->messages = $repData->getMessages();
        $this->players = $repData->getPlayers();
    }
}