<?php
/*
    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/


/*
define("MPQ_HASH_TABLE_OFFSET", 0);
define("MPQ_HASH_NAME_A", 1);
define("MPQ_HASH_NAME_B", 2);
define("MPQ_HASH_FILE_KEY", 3);
define("MPQ_HASH_ENTRY_EMPTY", -1); // (0xFFFF << 16) | 0xFFFF)
define("MPQ_HASH_ENTRY_DELETED", -2); // (0xFFFF << 16) | 0xFFFE)
*/
class MPQFile {
	private $filename;
	private $fp;
	private $hashtable,$blocktable;
	private $hashTableSize, $blockTableSize;
	private $hashTableOffset, $blockTableOffset;
	private $headerOffset;
	private $init;
	private $verMajor;
	private $build;
	private $sectorSize;
	private $debug;
	private $debugNewline;
	private $gameLen;
	private $versionString;
	public static $cryptTable;
	private $fileType;
	private $fileData;
	private $archiveSize;
	// the following are MPQ-related constants
	private static $MPQ_HASH_TABLE_OFFSET = 0;
	private static $MPQ_HASH_NAME_A = 1;
	private static $MPQ_HASH_NAME_B = 2;
	private static $MPQ_HASH_FILE_KEY = 3;
	private static $MPQ_HASH_ENTRY_EMPTY = -1;
	private static $MPQ_HASH_ENTRY_DELETED = -2;
	
	function __construct($filename, $autoparse = true, $debug = 0) {
		$this->filename = $filename;
		$this->hashtable = NULL;
		$this->blocktable = NULL;
		$this->hashTableSize = 0;
		$this->blockTableSize = 0;
		$this->headerOffset = 0;
		$this->init = false;
		$this->verMajor = 0;
		$this->build = 0;
		$this->gameLen = 0;
		$this->sectorSize = 0;
		$this->debug = $debug;
		$this->debugNewline = "<br />\n";
		$this->versionString = "null";
		$this->fileType = null;
		if (!self::$cryptTable)
			self::initCryptTable();
		
		if (file_exists($this->filename)) {
			$fp = fopen($this->filename, 'rb');
			$contents = fread($fp, filesize($this->filename));
			if ($this->debug && $contents === false) $this->debug("Error opening file $filename for reading");
			if ($contents !== false)
				$this->fileData = $contents;
			fclose($fp);
		}
		if ($autoparse)
			$this->parseHeader();
	}
	private function debug($message) { echo $message.($this->debugNewline); }
	function setDebugNewline($str) { $this->debugNewline = $str; }
	function setDebug($bool) { $this->debug = $bool; }
	
	static function readByte($string, &$numByte) {
		$tmp = unpack("C",substr($string,$numByte,1));
		$numByte++;
		return $tmp[1];
	}
	static function readBytes($string, &$numByte, $length) {
		$tmp = substr($string,$numByte,$length);
		$numByte += $length;
		return $tmp;
	}
	static function readUInt16($string, &$numByte) {
		$tmp = unpack("v",substr($string,$numByte,2));
		$numByte += 2;
		return $tmp[1];
	}
	static function readUInt32($string, &$numByte) {
		$tmp = unpack("V",substr($string,$numByte,4));
		$numByte += 4;
		return $tmp[1];
	}
	
	static function parseVLFNumber($string, &$numByte) {
		$number = 0;
		$first = true;
		$multiplier = 1;
		for ($i = MPQFile::readByte($string,$numByte),$bytes = 0;true;$i = MPQFile::readByte($string,$numByte),$bytes++) {
			$number += ($i & 0x7F) * pow(2,$bytes * 7);
			if ($first) {
				if ($number & 1) {
					$multiplier = -1;
					$number--;
				}
				$first = false;
			}
			if (($i & 0x80) == 0) break;
		}
		$number *= $multiplier;
		$number /= 2; // can't use right-shift because the datatype will be float for large values on 32-bit systems
		return $number;
	}
	
	static function parseSerializedData($string, &$numByte) {
		$dataType = MPQFile::readByte($string,$numByte);
		switch ($dataType) {
			case 0x02: // binary data
				$dataLen = MPQFile::parseVLFNumber($string,$numByte);
				return MPQFile::readBytes($string,$numByte,$dataLen);
				break;
			case 0x04: // simple array
				$array = array();
				$numByte += 2; // skip 01 00
				$numElements = MPQFile::parseVLFNumber($string,$numByte);
				while ($numElements > 0) {
					$array[] = MPQFile::parseSerializedData($string,$numByte);
					$numElements--;
				}
				return $array;
				break;
			case 0x05: // array with keys
				$array = array();
				$numElements = MPQFile::parseVLFNumber($string,$numByte);
				while ($numElements > 0) {
					$index = MPQFile::parseVLFNumber($string,$numByte);
					$array[$index] = MPQFile::parseSerializedData($string,$numByte);
					$numElements--;
				}				
				return $array;
				break;
			case 0x06: // number of one byte
				return MPQFile::readByte($string,$numByte);
				break;
			case 0x07: // number of four bytes
				return MPQFile::readUInt32($string,$numByte);
				break;
			case 0x09: // number in VLF
				return MPQFile::parseVLFNumber($string,$numByte);
				break;
			default:
				if ($this->debug) $this->debug(sprintf("Unknown data type in function parseDetailsValue (%d)",$dataType));
				return false;
		}
	}
	

	function parseHeader() {
		$fp = 0;
		$headerParsed = false;
		$headerOffset = 0;
		while (!$headerParsed) {
			$magic = unpack("c4",self::readBytes($this->fileData,$fp,4)); // MPQ 1Bh or 1Ah
			if (($magic[1] != 0x4D) || ($magic[2] != 0x50) || ($magic[3] != 0x51)) { $this->init = false; return false; }
			if ($magic[4] == 27) { // user data block (1Bh)
				if ($this->debug) $this->debug(sprintf("Found user data block at %08X",$fp));
				$uDataMaxSize = self::readUInt32($this->fileData, $fp);
				$headerOffset = self::readUInt32($this->fileData, $fp);
				$this->headerOffset = $headerOffset;
				$uDataSize = self::readUInt32($this->fileData, $fp);
				$uDataStart = $fp;
				$userDataArray = MPQFile::parseSerializedData($this->fileData,$fp);
				
				$this->verMajor = $userDataArray[1][1];
				$this->build = $userDataArray[1][4];
				$this->gameLen = ceil($userDataArray[3] / 16);
				$this->versionString = sprintf("%d.%d.%d.%d",$this->verMajor,
															 $userDataArray[1][2],
															 $userDataArray[1][3],
															 $this->build
															 );
				
				$fp = $headerOffset;
			}
			else if ($magic[4] == 26) { // header (1Ah)
				if ($this->debug) $this->debug(sprintf("Found header at %08X",$fp));
				$headerSize = self::readUInt32($this->fileData, $fp);
				$archiveSize = self::readUInt32($this->fileData, $fp);
				$this->archiveSize = $archiveSize;
				$formatVersion = self::readUInt16($this->fileData, $fp);
				$sectorSizeShift = self::readByte($this->fileData, $fp);
				$sectorSize = 512 * pow(2,$sectorSizeShift);
				$this->sectorSize = $sectorSize;
				$fp++;
				$hashTableOffset = self::readUInt32($this->fileData, $fp) + $headerOffset;
				$this->hashTableOffset = $hashTableOffset;
				$blockTableOffset = self::readUInt32($this->fileData, $fp) + $headerOffset; 
				$this->blockTableOffset = $blockTableOffset;
				if ($this->debug) $this->debug(sprintf("Hash table offset: %08X, Block table offset: %08X",$hashTableOffset, $blockTableOffset));
				$hashTableEntries = self::readUInt32($this->fileData, $fp);
				$this->hashTableSize = $hashTableEntries;
				$blockTableEntries = self::readUInt32($this->fileData, $fp);
				$this->blockTableSize = $blockTableEntries;
				
				$headerParsed = true;
			}
			else {
				if ($this->debug) $this->debug("Could not find MPQ header");
				return false;
			}
		}
		// read and decode the hash table
		$fp = $hashTableOffset;
		$hashSize = $hashTableEntries * 4; // hash table size in 4-byte chunks
		$tmp = array();
		for ($i = 0;$i < $hashSize;$i++)
			$tmp[$i] = self::readUInt32($this->fileData, $fp);
		if ($this->debug) {
			$this->debug("Encrypted hash table:");
			$this->printTable($tmp);
		}
		$hashTable = self::decryptStuff($tmp,self::hashStuff("(hash table)", self::$MPQ_HASH_FILE_KEY));
		if ($this->debug) {
			$this->debug("DEBUG: Hash table");
			$this->debug("HashA, HashB, Language+platform, Fileblockindex");
			$tmpnewline = $this->debugNewline;
			$this->debugNewline = "";
			for ($i = 0;$i < $hashTableEntries;$i++) {
				$filehashA = $hashTable[$i*4];
				$filehashB = $hashTable[$i*4 +1];
				$lanplat = $hashTable[$i*4 +2];
				$blockindex = $hashTable[$i*4 +3];
				$this->debug(sprintf("<pre>%08X %08X %08X %08X</pre>",$filehashA, $filehashB, $lanplat, $blockindex));
			}
			$this->debugNewline = $tmpnewline;
		}		
		// read and decode the block table
		$fp = $blockTableOffset;
		$blockSize = $blockTableEntries * 4; // block table size in 4-byte chunks
		$tmp = array();
		for ($i = 0;$i < $blockSize;$i++)
			$tmp[$i] = self::readUInt32($this->fileData, $fp);
		if ($this->debug) {
			$this->debug("Encrypted block table:");
			$this->printTable($tmp);
		}

		$blockTable = self::decryptStuff($tmp,self::hashStuff("(block table)", self::$MPQ_HASH_FILE_KEY));		
		$this->hashtable = $hashTable;
		$this->blocktable = $blockTable;
		if ($this->debug) {
			$this->debug("DEBUG: Block table");
			$this->debug("Offset, Blocksize, Filesize, flags");
			$tmpnewline = $this->debugNewline;
			$this->debugNewline = "";
			for ($i = 0;$i < $blockTableEntries;$i++) {
				$blockIndex = $i * 4;
				$blockOffset = $this->blocktable[$blockIndex] + $this->headerOffset;
				$blockSize = $this->blocktable[$blockIndex + 1];
				$fileSize = $this->blocktable[$blockIndex + 2];
				$flags = $this->blocktable[$blockIndex + 3];
				$this->debug(sprintf("<pre>%08X %8d %8d %08X</pre>",$blockOffset, $blockSize, $fileSize, $flags));
			}
			$this->debugNewline = $tmpnewline;
		}
		$this->init = true;
		
		if ($this->getFileSize("replay.details") > 0 && $this->getFileSize("replay.initData") > 0)
			$this->fileType = "SC2replay";
		else if ($this->getFileSize("DocumentHeader") > 0 && $this->getFileSize("Minimap.tga") > 0)
			$this->fileType = "SC2map";
		else
			$this->fileType = "Unknown";
		
		return true;
	}
	

	function getFileSize($filename) {
		if (!$this->init) {
			if ($this->debug) $this->debug("Tried to use getFileSize without initializing");
			return false;
		}
		$hashA = self::hashStuff($filename, self::$MPQ_HASH_NAME_A);
		$hashB = self::hashStuff($filename, self::$MPQ_HASH_NAME_B);
		$hashStart = self::hashStuff($filename, self::$MPQ_HASH_TABLE_OFFSET) & ($this->hashTableSize - 1);
		$tmp = $hashStart;
		do {
			if (($this->hashtable[$tmp*4 + 3] == self::$MPQ_HASH_ENTRY_DELETED) || ($this->hashtable[$tmp*4 + 3] == self::$MPQ_HASH_ENTRY_EMPTY)) return false;
			if (($this->hashtable[$tmp*4] == $hashA) && ($this->hashtable[$tmp*4 + 1] == $hashB)) { // found file
				$blockIndex = ($this->hashtable[($tmp *4) + 3]) *4;
				$fileSize = $this->blocktable[$blockIndex + 2];
				return $fileSize;
			}
			$tmp = ($tmp + 1) % $this->hashTableSize;
		} while ($tmp != $hashStart);
		if ($this->debug) $this->debug("Did not find file $filename in archive");
		return false;
	}
	
	function readFile($filename) {
		if (!$this->init) {
			if ($this->debug) $this->debug("Tried to use getFile without initializing");
			return false;
		}
		$hashA = self::hashStuff($filename, self::$MPQ_HASH_NAME_A);
		$hashB = self::hashStuff($filename, self::$MPQ_HASH_NAME_B);
		$hashStart = self::hashStuff($filename, self::$MPQ_HASH_TABLE_OFFSET) & ($this->hashTableSize - 1);
		$tmp = $hashStart;
		$blockSize = -1;
		do {
			if (($this->hashtable[$tmp*4 + 3] == self::$MPQ_HASH_ENTRY_DELETED) || ($this->hashtable[$tmp*4 + 3] == self::$MPQ_HASH_ENTRY_EMPTY)) return false;
			if (($this->hashtable[$tmp*4] == $hashA) && ($this->hashtable[$tmp*4 + 1] == $hashB)) { // found file
				$blockIndex = ($this->hashtable[($tmp *4) + 3]) *4;
				$blockOffset = $this->blocktable[$blockIndex] + $this->headerOffset;
				$blockSize = $this->blocktable[$blockIndex + 1];
				$fileSize = $this->blocktable[$blockIndex + 2];
				$flags = $this->blocktable[$blockIndex + 3];
				break;
			}
			$tmp = ($tmp + 1) % $this->hashTableSize;
		} while ($tmp != $hashStart);
		if ($blockSize == -1) {
			if ($this->debug) $this->debug("Did not find file $filename in archive");
			return false;
		}
		$flag_file       = $flags & 0x80000000;
		$flag_checksums  = $flags & 0x04000000;
		$flag_deleted    = $flags & 0x02000000;
		$flag_singleunit = $flags & 0x01000000;
		$flag_hEncrypted = $flags & 0x00020000;
		$flag_encrypted  = $flags & 0x00010000;
		$flag_compressed = $flags & 0x00000200;
		$flag_imploded   = $flags & 0x00000100;
		
		if ($this->debug) $this->debug(sprintf("Found file $filename with flags %08X, block offset %08X, block size %d and file size %d",
										$flags, $blockOffset,$blockSize,$fileSize));
		
		if (!$flag_file) return false;
		$fp = $blockOffset;
		if ($flag_checksums) {
			for ($i = $fileSize;$i > 0;$i -= $this->sectorSize) {
				$sectors[] = self::readUInt32($this->fileData, $fp);
				$blockSize -= 4;
			}
			$sectors[] = self::readUInt32($this->fileData, $fp);
			$blockSize -= 4;
		}
		else {
			$sectors[] = 0;
			$sectors[] = $blockSize;
		}
		$c  = count($sectors) - 1;
		$totDur = 0;
		$output = "";
		for ($i = 0;$i < $c;$i++) {
			$sectorLen = $sectors[$i + 1] - $sectors[$i];
			if ($sectorLen == 0) break;
			$fp = $blockOffset + $sectors[$i];
			$sectorData = self::readBytes($this->fileData, $fp,$sectorLen);
			if ($this->debug) $this->debug(sprintf("Got %d bytes of sector data",strlen($sectorData)));
			if ($flag_compressed && (($flag_singleunit && ($blockSize < $fileSize)) || ($flag_checksums && ($sectorLen <  $this->sectorSize)))) {
				$numByte = 0;
				$compressionType = self::readByte($sectorData,$numByte);
				$sectorData = substr($sectorData,1);
				switch ($compressionType) {
					case 0x02:
						if ($this->debug) $this->debug("Compression type: gzlib");
						$output .= self::deflate_decompress($sectorData);
						break;
					case 0x10:
						if ($this->debug) $this->debug("Compression type: bzip2");
						$output .= self::bzip2_decompress($sectorData);
						break;
					default:
						if ($this->debug) $this->debug(sprintf("Unknown compression type: %d",$compressionType));
						return false;
				}
			}
			else $output .= $sectorData;
		}
		if (strlen($output) != $fileSize) {
			if ($this->debug) $this->debug(sprintf("Decrypted/uncompressed file size(%d) does not match original file size(%d)",
											strlen($output),$fileSize));
			return false;
		}
		return $output;
	}
	
	function parseReplay() {
		if (!$this->init) {
			if ($this->debug) $this->debug("Tried to use parseReplay without initializing");
			return false;
		}
		if (class_exists("SC2Replay") || (include 'sc2replay.php')) {
			$tmp = new SC2Replay();
			if ($this->debug) $tmp->setDebug($this->debug);
			$tmp->parseReplay($this);
			return $tmp;
		}
		else {
			if ($this->debug) $this->debug("Unable to find or load class SC2Replay");
			return false;
		}
	}
	function isParsed() {
		return $this->init === true;
	}
	function getState() {
		return $this->init;
	}
	function getFileType() { return $this->fileType; }
	function getBuild() { return $this->build; }
	function getVersion() { return $this->verMajor; }
	function getVersionString() { return $this->versionString; }
	function getHashTable() { return $this->hashtable; }
	function getBlockTable() { return $this->blocktable; }
	function getGameLength() { return $this->gameLen; }
	
	// prints block table or hash table, $data is the data in an array of UInt32s
	function printTable($data) {
		$this->debug("Hash table: HashA, HashB, Language+platform, Fileblockindex");
		$this->debug("Block table: Offset, Blocksize, Filesize, flags");
		$entries = count($data) / 4;
		$tmpnewline = $this->debugNewline;
		$this->debugNewline = "";
		for ($i = 0;$i < $entries;$i++) {
			$blockIndex = $i * 4;
			$blockOffset = $data[$blockIndex] + $this->headerOffset;
			$blockSize = $data[$blockIndex + 1];
			$fileSize = $data[$blockIndex + 2];
			$flags = $data[$blockIndex + 3];
			$this->debug(sprintf("<pre>%08X %08X %08X %08X</pre>",$blockOffset, $blockSize, $fileSize, $flags));
		}
		$this->debugNewline = $tmpnewline;
	}
	
	// the following replaces a file in the archive, meaning a file with that filename must be present already.
	function replaceFile($filename, $filedata) {
		if (!$this->init) {
			if ($this->debug) $this->debug("Tried to use replaceFile without initializing");
			return false;
		}
		if ($this->getFileSize($filename) === false || strlen($filedata) == 0) return false;
		$hashA = self::hashStuff($filename, self::$MPQ_HASH_NAME_A);
		$hashB = self::hashStuff($filename, self::$MPQ_HASH_NAME_B);
		$hashStart = self::hashStuff($filename, self::$MPQ_HASH_TABLE_OFFSET) & ($this->hashTableSize - 1);
		$tmp = $hashStart;
		$blockIndex = -1;
		do {
			if (($this->hashtable[$tmp*4 + 3] == self::$MPQ_HASH_ENTRY_DELETED) || ($this->hashtable[$tmp*4 + 3] == self::$MPQ_HASH_ENTRY_EMPTY)) return false;
			if (($this->hashtable[$tmp*4] == $hashA) && ($this->hashtable[$tmp*4 + 1] == $hashB)) { // found file
				$blockIndex = ($this->hashtable[($tmp *4) + 3]) *4;
				$blockOffset = $this->blocktable[$blockIndex] + $this->headerOffset;
				$blockSize = $this->blocktable[$blockIndex + 1];
				$fileSize = $this->blocktable[$blockIndex + 2];
				$flags = $this->blocktable[$blockIndex + 3];
				break;
			}
			$tmp = ($tmp + 1) % $this->hashTableSize;
		} while ($tmp != $hashStart);
		if ($blockIndex == -1) return false;

		// fix block table offsets
		for ($i = 0;$i < $this->blockTableSize;$i++) {
			if ($i == $blockIndex) continue;
			if ($this->blocktable[$i*4] > ($blockOffset - $this->headerOffset))
				$this->blocktable[$i*4] -= $blockSize;
		}
		if ($this->blockTableOffset > $blockOffset) {
			$this->blockTableOffset = $this->blockTableOffset - $this->headerOffset - $blockSize;
			$this->fileData = substr_replace($this->fileData, pack("V",$this->blockTableOffset),$this->headerOffset + 20,4);
			$this->blockTableOffset += $this->headerOffset;
		}
		if ($this->hashTableOffset > $blockOffset) {
			$this->hashTableOffset = $this->hashTableOffset - $this->headerOffset - $blockSize;
			$this->fileData = substr_replace($this->fileData, pack("V",$this->hashTableOffset),$this->headerOffset + 16,4);
			$this->hashTableOffset += $this->headerOffset;
		}
		// remove the original file contents
		$this->fileData = substr_replace($this->fileData,'',$blockOffset,$blockSize);		
		$newFileSize = strlen($filedata);
		// attempt to use bzip2 compression
		$compressedData =  chr(16) . bzcompress($filedata);

		$newBlockOffset = strlen($this->fileData) - $this->headerOffset;
		if (strlen($compressedData) >= $newFileSize) {
			$newFlags = (0x40000000 << 1) | 0x01000000;
			$compressedData = $filedata;
			$newBlockSize = $newFileSize;
		}
		else {
			$newFlags = (0x40000000 << 1) | 0x01000200;
			$newBlockSize = strlen($compressedData);
		}
		// fix archive size
		$this->fileData = substr_replace($this->fileData, pack("V",$this->archiveSize + $newBlockSize), $this->headerOffset + 8, 4); 
		// populate variables
		$this->fileData .= $compressedData;
		$this->blocktable[$blockIndex] = $newBlockOffset;
		$this->blocktable[$blockIndex + 1] = $newBlockSize;
		$this->blocktable[$blockIndex + 2] = $newFileSize;
		$this->blocktable[$blockIndex + 3] = $newFlags;
		// encrypt the block table
		$resultBlockTable = self::encryptStuff($this->blocktable,self::hashStuff("(block table)", self::$MPQ_HASH_FILE_KEY));		
		// replace the block table in fileData variable
		for ($i = 0;$i < $this->blockTableSize * 4;$i++) {
			$this->fileData = substr_replace($this->fileData, pack("V",$resultBlockTable[$i]), $this->blockTableOffset + $i * 4, 4); 
		}
		return true;
	}
	// saves the mpq data as a file.
	function saveAs($filename, $overwrite = false) {
		if (file_exists($filename) && !$overwrite) return false;
		$fp = fopen($filename, "wb");
		if (!$fp) return false;
		$result = fwrite($fp,$this->fileData);
		if (!$result) return false;
		fclose($fp);
		return true;
	}
	
	function insertChatLogMessage($newMessage, $player, $time) {
		if (!$this->init || $this->getFileSize("replay.message.events") == 0) return false;
		if (!is_numeric($player)) return false; //$playerId = $this->addFakeObserver($player);
		else $playerId = $player;
		if ($playerId <= 0) return false;
		if ($this->getFileSize("replay.message.events") == 0) return false;
		$string = $this->readFile("replay.message.events");
		$numByte = 0;
		$time = $time * 16;
		$fileSize = strlen($string);
		$messageSize = strlen($newMessage);
		if ($messageSize >= 256) return false;
		$totTime = 0;
		$haveMessage = false;
		while ($numByte < $fileSize) {
			$pastHeaders = true;
			$start = $numByte;
			$timestamp = SC2Replay::parseTimeStamp($string,$numByte);
			$pid = self::readByte($string,$numByte);
			$nopcode = self::readByte($string,$numByte);
			$totTime += $timestamp;
			if ($nopcode == 0x80) {
				$numByte += 4;
				$pastHeaders = false;
			}
			else if (($nopcode & 0x80) == 0) { // message
				$messageTarget = $opcode & 3;
				$messageLength = self::readByte($string,$numByte);
				if (($nopcode & 8) == 8) $messageLength += 64;
				if (($nopcode & 16) == 16) $messageLength += 128;
				$message = self::readBytes($string,$numByte,$messageLength);
				$haveMessage = true;
			}
			else if ($nopcode == 0x83) { // ping on map? 8 bytes?
				$numByte += 8;
			}
			$end = $numByte;
			if ($pastHeaders && ($totTime >= $time)) {
				$opcode = 0;
				if ($messageSize >= 128) {
					$opcode = $opcode | 16;
					$messageSize -= 128;
				}
				if ($messageSize >= 64) {
					$opcode = $opcode | 8;
					$messageSize -= 64;
				}
				break;
			}
		}

		$nFrames = $time - ($totTime - $timestamp);
		$frameNum = SC2Replay::createTimeStamp($nFrames);
		$newMessageTS = "";
		for ($i = $frameNum[1] - 1;$i >= 0;$i--) { $newMessageTS .= pack("C", ($frameNum[2] & (0xFF << ($i*8))) >> ($i*8)); }
		$messageString = $newMessageTS . pack("C3", $playerId, $opcode, $messageSize). $newMessage;
		$width = 0;
		
		if ($haveMessage) { // $haveMessage is true if there is at least one chatlog message.
			$nextMessageTS = "";
			$newTimeStamp = SC2Replay::createTimeStamp($timestamp - $nFrames); // calculate new timestamp for the messages following the inserted one
			for ($i = $newTimeStamp[1] - 1;$i >= 0;$i--) { $nextMessageTS .= pack("C", ($newTimeStamp[2] & (0xFF << ($i*8))) >> ($i*8)); }
			$messageString .= $nextMessageTS . pack("C3", $pid, $nopcode, $messageLength). $message;
			$width = $end - $start;
		}
		
		$newData = substr_replace($string, $messageString, $start, $width);
		$this->replaceFile("replay.message.events", $newData);
		return true;
	}
	// $obsName is the fake observer name, $string is the contents of replay.initData file
	// DOES NOT WORK CURRENTLY!
	function addFakeObserver($obsName) {
		return false; // this function does not work currently so DO NOT USE!
		if (!$this->init || $this->getFileSize("replay.initData") == 0) return false;
		$string = $this->readFile("replay.initData");
		$numByte = 0;
		$numPlayers = MPQFile::readByte($string,$numByte);
		$playerAdded = false;
		$playerId = 0;
		for ($i = 1;$i <= $numPlayers;$i++) {
			$nickLen = MPQFile::readByte($string,$numByte);
			if ($nickLen > 0) {
				$numByte += $nickLen;
				$numByte += 5;
			} 
			elseif (!$playerAdded) {
				// first empty slot
				$playerAdded = true;
				$numByte--;
				if ($i == $numPlayers)
					$len = 5;
				else
					$len = 6;
				// add the player to the initdata file
				$obsNameLength = strlen($obsName);
				$repString = chr($obsNameLength) . $obsName . str_repeat(chr(0),5);
				$newData = substr($string,0,$numByte) . $repString . substr($string,$numByte - $len + strlen($repString));
				$numByte += strlen($repString);
				//$this->replaceFile("replay.initData", $newData);

				$playerId = $i;
				$string = $newData;
				// skip the next null part because it is 1 byte shorter than normal
				if ($i < $numPlayers) {
					$i++;
					$numByte += 4;
				}
			}
			else {
				$numByte += 5;
			}
		}
		if ($this->debug) $this->debug(sprintf("Got past first player loop, counter = $i, numbyte: %04X",$numByte));
		if ($playerId == 0)
			return false;
		$numByte += 25;
		$accountIdentifierLength = MPQFile::readByte($string,$numByte);
		if ($accountIdentifierLength > 0)
			$accountIdentifier = MPQFile::readBytes($string,$numByte,$accountIdentifierLength);
		$numByte += 684; // length seems to be fixed, data seems to vary at least based on number of players
		while (true) {
			$str = MPQFile::readBytes($string,$numByte,4);
			if ($str != 's2ma') { $numByte -= 4; break; }
			$numByte += 2; // 0x00 0x00
			$realm = MPQFile::readBytes($string,$numByte,2);
			$this->realm = $realm;
			$numByte += 32;
		}
		// start of variable length data portion
		$numByte += 2;
		$numPlayers = MPQFile::readByte($string,$numByte);
		// need to increment numplayers by 1
		$string = substr_replace($string, pack("c",$numPlayers + 1), $numByte - 1, 1);
		$numByte += 4;
		for ($i = 1;$i <= $numPlayers;$i++) {
			$firstByte = MPQFile::readByte($string,$numByte);
			$secondByte = MPQFile::readByte($string,$numByte);
			if ($this->debug) $this->debug(sprintf("Function addFakeObserver: numplayer: %d, first byte: %02X, second byte: %02X",$i,$firstByte,$secondByte));
			switch ($firstByte) {
				case 0xca:
					switch ($secondByte) {
						case 0x20: // player
							$numByte += 20;
							break;
						case 0x28: // player
							$numByte += 24;
							break;
						case 0x04:
						case 0x02: // spectator
						case 0x00: // computer
							$numByte += 4;
							break;
					}
					break;
				case 0xc2:
					switch ($secondByte) {
						case 0x04:
							$tmp = MPQFile::readByte($string,$numByte);
							if ($tmp == 0x05)
								$numByte += 4;
							$numByte += 20;
							break;
						case 0x24:
						case 0x44:
							$numByte += 5;
							break;
					}
					break;
				default:
					if ($this->debug) $this->debug(sprintf("Function addFakeObserver: Unknown byte at byte offset %08X, got %02X",$numByte,$firstByte));
					return false;
			}
		}
		
		// insert join game event and initial camera event for the newly created player
		$string = $this->readFile("replay.game.events");
		$string = substr_replace($string, pack("c3",0,$playerId,0x0B),0,0);
		$tmpByte = $i * 3 + 5;
		$camerastring = pack("c2", 0, (0x60 | $playerId)) .MPQFile::readBytes($string,$tmpByte,11);
		$string = substr_replace($string, $camerastring,$tmpByte,0);
		$this->replaceFile("replay.game.events", $string);
		return $playerId;
	}

	function deflate_decompress($string) {
		if (function_exists("gzinflate")){
			$tmp = gzinflate(substr($string,2,strlen($string) - 2));
			return $tmp;
		}
		if ($this->debug) $this->debug("Function 'gzinflate' does not exist, is gzlib installed as a module?");
		return false;
	}
	function bzip2_decompress($string) {
		if (function_exists("bzdecompress")){
			$tmp = bzdecompress($string);
			if (is_numeric($tmp) && $this->debug) {
				$this->debug(sprintf("Bzip2 returned error code: %d",$tmp));
			}
			return $tmp;
		}
		if ($this->debug) $this->debug("Function 'bzdecompress' does not exist, is bzip2 installed as a module?");
		return false;
	}


	static function initCryptTable() {
		if (!self::$cryptTable)
			self::$cryptTable = array();
		$seed = 0x00100001;
		$index1 = 0;
		$index2 = 0;
		
		for ($index1 = 0; $index1 < 0x100; $index1++) {
			for ($index2 = $index1, $i = 0; $i < 5; $i++, $index2 += 0x100) {
				$seed = (uPlus($seed * 125,3)) % 0x2AAAAB;
				$temp1 = ($seed & 0xFFFF) << 0x10;
				
				$seed = (uPlus($seed * 125,3)) % 0x2AAAAB;
				$temp2 = ($seed & 0xFFFF);
				
				self::$cryptTable[$index2] = ($temp1 | $temp2);
			}
		}
	}

	static function hashStuff($string, $hashType) {
		$seed1 = 0x7FED7FED;
		$seed2 = ((0xEEEE << 16) | 0xEEEE);
		$strLen = strlen($string);
		
		for ($i = 0;$i < $strLen;$i++) {
			$next = ord(strtoupper(substr($string, $i, 1)));

			$seed1 = self::$cryptTable[($hashType << 8) + $next] ^ (uPlus($seed1,$seed2));
			$seed2 = uPlus(uPlus(uPlus(uPlus($next,$seed1),$seed2),$seed2 << 5),3);
		}
		return $seed1;
	}

	static function decryptStuff($data, $key) {
		$seed = ((0xEEEE << 16) | 0xEEEE);
		$datalen = count($data);
		for($i = 0;$i < $datalen;$i++) {
			$seed = uPlus($seed,self::$cryptTable[0x400 + ($key & 0xFF)]);
			$ch = $data[$i] ^ (uPlus($key,$seed));

			$key = (uPlus(((~$key) << 0x15), 0x11111111)) | (rShift($key,0x0B));
			$seed = uPlus(uPlus(uPlus($ch,$seed),($seed << 5)),3);
			$data[$i] = $ch & ((0xFFFF << 16) | 0xFFFF);
		}
		return $data;
	}
	static function encryptStuff($data, $key) {
		$seed = ((0xEEEE << 16) | 0xEEEE);
		$datalen = count($data);
		for($i = 0;$i < $datalen;$i++) {
			$seed = uPlus($seed,self::$cryptTable[0x400 + ($key & 0xFF)]);
			$ch = $data[$i] ^ (uPlus($key,$seed));

			$key = (uPlus(((~$key) << 0x15), 0x11111111)) | (rShift($key,0x0B));
			$seed = uPlus(uPlus(uPlus($data[$i],$seed),($seed << 5)),3);
			$data[$i] = $ch & ((0xFFFF << 16) | 0xFFFF);			
		}
		return $data;
	}
}

function microtime_float() {
	list($usec, $sec) = explode(" ", microtime());
	return ((float)$usec + (float)$sec);
}

// function that adds up two integers without allowing them to overflow to floats
function uPlus($o1, $o2) {
	$o1h = ($o1 >> 16) & 0xFFFF;
	$o1l = $o1 & 0xFFFF;
	
	$o2h = ($o2 >> 16) & 0xFFFF;
	$o2l = $o2 & 0xFFFF;	

	$ol = $o1l + $o2l;
	$oh = $o1h + $o2h;
	if ($ol > 0xFFFF) { $oh += (($ol >> 16) & 0xFFFF); }
	return ((($oh << 16) & (0xFFFF << 16)) | ($ol & 0xFFFF));
}

// right shift without preserving the sign(leftmost) bit
function rShift($num,$bits) {
	return (($num >> 1) & 0x7FFFFFFF) >> ($bits - 1);
}

?>
