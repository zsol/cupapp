<?php

class SC2Map {
	private $author;
	private $mapName;
	private $description;
	private $shortDescription;
	private $miniMapData;
	
	function __construct() {
		$this->author = "";
		$this->mapName = "";
		$this->description = "";
		$this->shortDescription = "";
		$this->miniMapData = null;
	}
	
	function parseMap($mpqfile) {
		if (!class_exists('MPQFile') && (file_exists('mpqfile.php'))) {
			include 'mpqfile.php';
			if (!!class_exists('MPQFile')) return false;
		}
		if (!($mpqfile instanceof MPQFile) || !$mpqfile->isParsed()) return false;
		$string = $mpqfile->readFile("DocumentHeader");
		if (strlen($string) > 0)
			$this->parseDocumentHeader($string);
		$string = $mpqfile->readFile("Minimap.tga");
		if (strlen($string) > 0) {
			$this->miniMapData = $this->imagecreatefromtga($string);
		}
	}
	
	function getAuthor() { return $this->author; }
	function getMapName() { return $this->mapName; }
	function getLongDescription() { return $this->description; }
	function getShortDescription() { return $this->shortDescription; }
	function getMiniMapData() { return $this->miniMapData; }
	
	private function parseDocumentHeader($string) {
		$numByte = 44; // skip header and unknown stuff
		$numDeps = MPQFile::readByte($string,$numByte); // uncertain that this is the number of dependencies, might also be uint32 if it is
		$numByte += 3;
		while ($numDeps > 0) {
			while (MPQFile::readByte($string,$numByte) !== 0);
			$numDeps--;
		}
		$numAttribs = MPQFile::readUInt32($string,$numByte);
		$attribs = array();
		while ($numAttribs > 0) {
			$keyLen = MPQFile::readUInt16($string,$numByte);
			$key = MPQFile::readBytes($string,$numByte,$keyLen);
			$numByte += 4; // always seems to be followed by ascii SUne
			$valueLen = MPQFile::readUInt16($string,$numByte);
			$value = MPQFile::readBytes($string,$numByte,$valueLen);
			$attribs[$key] = $value;
			$numAttribs--;
		}
		$this->author = $attribs["DocInfo/Author"];
		$this->mapName = $attribs["DocInfo/Name"];
		$this->description = $attribs["DocInfo/DescLong"];
		$this->shortDescription = $attribs["DocInfo/DescShort"];
	}
	
	// following function copied from a comment at http://www.php.net/manual/en/function.imagecreatetruecolor.php#54010 with slight modifications
	function imagecreatefromtga ( $data, $return_array = 0 ) {
		$pointer = 18;
		$x = 0;
		$y = 0;
		$w = base_convert ( bin2hex ( strrev ( substr ( $data, 12, 2 ) ) ), 16, 10 );
		$h = base_convert ( bin2hex ( strrev ( substr ( $data, 14, 2 ) ) ), 16, 10 );
		$img = imagecreatetruecolor( $w, $h );

		while ( $pointer < strlen ( $data ) )
		{
			imagesetpixel ( $img, $x, $y, base_convert ( bin2hex ( strrev ( substr ( $data, $pointer, 3 ) ) ), 16, 10 ) );
			$x++;

			if ($x == $w)
			{
				$y++;
				$x=0;
			}

			$pointer += 3;
		}
	   
		if ( $return_array )
			return array ( $img, $w, $h );
		else
			return $img;
	}
}


?>