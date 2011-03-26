<?php

class ImageHelper {
    static public function createImage($filePath, $savePath, $width, $height, $mimeType) {
        $thumbnail = new sfThumbnail($width, $height);
        $thumbnail->loadFile($filePath);
        $thumbnail->save($savePath, $mimeType);
    }
}