<?php
if(!defined('IN_ID'))die('You are not allowed to access to this page.');

class mp3_id3v2 {
	private $file_loaded;
	private $filename,$fp;
	private $tag_readable;
	private $tag;
	var $tag_array = array();	// Array of TagValue
	private $header_array = array();
	private $version_major,$version_revision;
	private $flag;
	private $size;
	private $padding_size;
	private $remove_tag;
	private $tag_end;
	private $tag_explain = array(
		'AENC'=>'Audio encryption',
		'APIC'=>'Attached picture',
		'ASPI'=>'Audio seek point index',
		'COMM'=>'Comments',
		'COMR'=>'Commercial frame',
		'ENCR'=>'Encryption method registration',
		'EQU2'=>'Equalization (2)',
		'EQUA'=>'Equalization',				/* Deprecated */
		'ETCO'=>'Event timing codes',
		'GEOB'=>'General encapsulated object',
		'GRID'=>'Group identification registration',
		'IPLS'=>'Involved people list',			/* Deprecated */
		'LINK'=>'Linked information',
		'MCDI'=>'Music CD identifier',
		'MLLT'=>'MPEG location lookup table',
		'OWNE'=>'Ownership frame',
		'PRIV'=>'Private frame',
		'PCNT'=>'Play counter',
		'POPM'=>'Popularimeter',
		'POSS'=>'Position synchronisation frame',
		'RBUF'=>'Recommended buffer size',
		'RVA2'=>'Relative volume adjustment (2)',
		'RVAD'=>'Relative volume adjustment',		/* Deprecated */
		'RVRB'=>'Reverb',
		'SEEK'=>'Seek frame',
		'SIGN'=>'Signature frame',
		'SYLT'=>'Synchronized lyric/text',
		'SYTC'=>'Synchronized tempo codes',
		'TALB'=>'Album/Movie/Show title',
		'TBPM'=>'BPM (beats per minute)',
		'TCOM'=>'Composer',
		'TCON'=>'Content type',
		'TCOP'=>'Copyright message',
		'TDAT'=>'Date',					/* Deprecated */
		'TDEN'=>'Encoding time',
		'TDLY'=>'Playlist delay',
		'TDOR'=>'Original release year',
		'TDRC'=>'Recording time',
		'TDRL'=>'Release time',
		'TDTG'=>'Tagging time',
		'TENC'=>'Encoded by',
		'TEXT'=>'Lyricist/Text writer',
		'TFLT'=>'File type',
		'TIME'=>'Time',					/* Deprecated */
		'TIPL'=>'involved people list',
		'TIT1'=>'Content group description',
		'TIT2'=>'Title/songname/content description',
		'TIT3'=>'Subtitle/Description refinement',
		'TKEY'=>'Initial key',
		'TLAN'=>'Language(s)',
		'TLEN'=>'Length',
		'TMCL'=>'Musition credits list',
		'TMED'=>'Media type',
		'TMOO'=>'Mood',
		'TOAL'=>'Original album/movie/show title',
		'TOFN'=>'Original filename',
		'TOLY'=>'Original lyricist(s)/text writer(s)',
		'TOPE'=>'Original artist(s)/performer(s)',
		'TORY'=>'Original release year',		/* Deprecated */
		'TOWN'=>'File owner/licensee',
		'TPE1'=>'Lead performer(s)/Soloist(s)',
		'TPE2'=>'Band/orchestra/accompaniment',
		'TPE3'=>'Conductor/performer refinement',
		'TPE4'=>'Interpreted, remixed, or otherwise modified by',
		'TPOS'=>'Part of a set',
		'TPRO'=>'Produced notice',
		'TPUB'=>'Publisher',
		'TRCK'=>'Track number/Position in set',
		'TRDA'=>'Recording dates',			/* Deprecated */
		'TRSN'=>'Internet radio station name',
		'TRSO'=>'Internet radio station owner',
		'TSIZ'=>'Size',					/* Deprecated */
		'TSOA'=>'Album sort order',
		'TSOP'=>'Performer sort order',
		'TSOT'=>'Title sort order',
		'TSRC'=>'ISRC (international standard recording code)',
		'TSSE'=>'Software/Hardware and settings used for encoding',
		'TSST'=>'Set subtitle',
		'TYER'=>'Year',					/* Deprecated */
		'TXXX'=>'User defined text information frame',
		'UFID'=>'Unique file identifier',
		'USER'=>'Terms of use',
		'USLT'=>'Unsychronized lyric/text transcription',
		'WCOM'=>'Commercial information',
		'WCOP'=>'Copyright/Legal information',
		'WOAF'=>'Official audio file webpage',
		'WOAR'=>'Official artist/performer webpage',
		'WOAS'=>'Official audio source webpage',
		'WORS'=>'Official internet radio station homepage',
		'WPAY'=>'Payment',
		'WPUB'=>'Publishers official webpage',
		'WXXX'=>'User defined URL link frame'
	);

	function __construct(){
		$this->file_loaded = false;
		$this->tag_readable = false;
		$this->remove_tag = false;
		$this->tag_end = 0;
		$this->flag = 0;
		$this->header_array['size'] = 0;
		$this->header_array['flags'] = 0;
		$this->header_array['crc32'] = FALSE;
	}

	function __destruct(){
		if($this->fp)
			fclose($this->fp);
	}

	/**
	 * Loads an MP3 File
	 *
	 * @param string $file path to file
	 * @return bool
	 */
	public function load_file($file){
		$this->remove_tag = false;
		if($this->file_loaded == false){
			$this->fp = fopen($file,'rb');
			if($this->fp){
				$this->filename = $file;
				$this->file_loaded = true;
				$this->read_tag();
				return true;
			}
			else
				return false;
		}
		else
			return false;
	}

	/**
	 * Transform $val into a int32 value
	 *
	 * @param string $val
	 * @return int
	 */
	private function get_int32($val){
		$buffer = ord($val[0]) << 24;
		$buffer += ord($val[1]) << 16;
		$buffer += ord($val[2]) << 8;
		$buffer += ord($val[3]) << 0;
		return $buffer;
	}

	/**
	 * Transform $val into a int16 value
	 *
	 * @param string $val
	 * @return int
	 */
	private function get_int16($val){
		$buffer = ord($val[0]) << 8;
		$buffer += ord($val[1]) << 0;
		return $buffer;
	}

	/**
	 * Fills variable from file
	 * Called only if extended header detected
	 */
	private function get_extended_header(){
		$this->header_array['size'] = $this->get_int32(fread($this->fp,4));		// Size of extended header (currently 6 or 10 bytes)
		$current = fread($this->fp,$this->header_array['size']);			// We don't implement the extended header for now
		$temp = unpack('nflags',substr($current,0,2));
		$this->set_extended_flags($temp['flags']);
		$count = 6;									// We don't need padding...
		if($this->get_extended_flags() & 32768){
			$temp = unpack('Ncrc',substr($current,$count,4));
			$this->header_array['crc32'] = $temp['crc'];
		}
	}

	/**
	 * Set extended header
	 *
	 * @param string $data Data for crc 32
	 * @return string Header
	 */
	private function set_extended_header($data){
		$temp_crc = '';
		if($this->header_array['flags'] & 32768){
			$temp_size = pack('N',10);
			$temp_crc = pack('N',crc32($data));
		}
		else
			$temp_size = pack('N',6);
		$temp_pad = pack('N',$this->padding_size);
		return $temp_size.pack('n',$this->header_array['flags']).$temp_pad.$temp_crc;		
	}

	/**
	 * Read tag from file
	 *
	 * @return bool
	 */
	public function read_tag(){
		if($this->file_loaded == true){
			fseek($this->fp,0);
			if(fread($this->fp,3) == 'ID3'){
				unset($this->tag_array);
				$this->tag_array = array();
				$this->flag = 0;
				$this->header_array['size'] = 0;
				$this->header_array['flags'] = 0;
				$this->header_array['crc32'] = FALSE;
				$this->tag_readable = true;
				$this->version_major = ord(fread($this->fp,1));
				$this->version_revision = ord(fread($this->fp,1));
				$this->flag = ord(fread($this->fp,1));
				if($this->flag & 128)
					sleep(0);						// Not Supported
				$bytes = array();
				$count_byte = 0;
				$count = 0;
				for($i=0;$i<4;$i++)
					$bytes[] = ord(fread($this->fp,1));
				for($i=3;$i>=0;$i--){
					for($j=0;$j<7;$j++){
						$count_byte += (($bytes[$i] & 1<<$j)==1<<$j)?1<<$count:0;
						$count++;
					}
				}
				$this->size = $count_byte;
				if($this->flag & 64) {
					$this->get_extended_header();				// Treating Extended Header
					$this->size -= 10;
				}
				if($this->size>0)
					$tag = fread($this->fp,$this->size);
				else
					$tag = '';
				$this->tag_end = ftell($this->fp);
				$count = 0;
				$count_tag = 0;
				while($count < $this->size){
					// 4 chars FRAME ID
					$this->tag_array[$count_tag] = new TagValue();
					$this->tag_array[$count_tag]->id = substr($tag,$count,4);
					if(trim($this->tag_array[$count_tag]->id) == ''){ // Padding detected
						unset($this->tag_array[$count_tag]);
						break;
					}
					$count+=4;
					$this->tag_array[$count_tag]->size = $this->get_int32(substr($tag,$count,4));
					$count+=4;
					$this->tag_array[$count_tag]->flag = $this->get_int16(substr($tag,$count,2));
					$count+=2;
					$this->tag_array[$count_tag]->data = substr($tag,$count,$this->tag_array[$count_tag]->size);
					$count+=$this->tag_array[$count_tag]->size;
					$count_tag++;
				}
				$this->padding_size = $this->size-$count;
				return true;
			}
			else{
				$this->tag_readable = false;
				return false;
			}
		}
		else
			return false;
	}

	/**
	 * Returns the size of the id3tag
	 *
	 * @return int
	 */
	public function get_size(){
		return $this->size+9;	// 9 is the header not include in $size
	}

	/**
	 * Return the explanation of the tag $val or NULL if it is not existing
	 *
	 * @param string $val 4 letters
	 * @return string
	 */
	public function get_explain($val){
		return (isset($this->tag_explain[$val]))?$this->tag_explain[$val]:NULL;
	}

	/**
	 * Return the tag array
	 *
	 * @return string[]
	 */
	public function get_tag(){
		if($this->tag_readable == true && isset($this->tag_array))	// Second check only for empty id3
			return $this->tag_array;
		else
			return NULL;
	}

	/**
	 * Returns buffer. The buffer is the real data read from the file (only frame data)
	 *
	 * @return string
	 */
	public function get_buffer(){
		$tag = $this->get_tag();
		if($tag!==NULL){
			$buffer = '';
			$c = count($tag);
			for($i=0;$i<$c;$i++)
				$buffer .= $tag[$i]->id.self::get_num($tag[$i]->size,4).self::get_flag($tag[$i]->flag).$tag[$i]->data;
			return $buffer;
		}
		return '';
	}

	/**
	 * Returns flag data from int to string
	 *
	 * @param int $c
	 * @return string
	 */
	private static function get_flag($c) {
		$flag1 = 0;
		$flag2 = 0;
		for($i=0;$i<3;$i++)
			if(isset($c[$i+1]) && $c[$i+1]==1)
				$flag1 += 1<<(7-$i);
		for($i=0;$i<3;$i++)
			if(isset($c[$i+4]) && $c[$i+4]==1)
				$flag2 += 1<<(7-$i);
		return chr($flag1).chr($flag2);
	}

	/**
	 * Returns the size in $n char
	 *
	 * @param int $size
	 * @param int $n
	 * @return string
	 */
	private static function get_num($size,$n) {
		$value = str_repeat(chr(255),$n);
		for($i=0;$i<$n;$i++){
			$buffer = 0;
			for($j=0;$j<8;$j++)
				$buffer += (($size & pow(2,(8*$i+$j))) == pow(2,(8*$i+$j)))?(1<<$j):0;
			$value[$n-$i-1] = chr($buffer);
		}
		return $value;
	}

	/**
	 * Writes the data $data into the file
	 *
	 * @param string data
	 */
	public function write_file($data=''){
		if($this->remove_tag == true){
			$music_file = $this->get_music();
			fclose($this->fp);
			$this->fp = fopen($this->filename,'wb');
			fputs($this->fp,$music_file);
			fclose($this->fp);
			clearstatcache();
			$this->file_loaded = false;
			$this->load_file($this->filename);
		}
		else{
			$tag = 'ID3';
			$version = chr($this->version_major).chr($this->version_revision);
			if($this->flag & 64)
				$header_extended = $this->set_extended_header($data);
			else
				$header_extended = '';
			$size = str_repeat(chr(255),4);
			// Check if the padding > 0
			$this->padding_size = ($this->padding_size<0)?0:$this->padding_size;
			$temp_size = $data.$header_extended.str_repeat(chr(0),$this->padding_size);
			for($i=3,$count=0;$i>=0;$i--){
				$buffer = 0;
				for($j=0;$j<7;$j++,$count++){
					$buffer += ((strlen($temp_size) & 1<<($count)) == 1<<($count))?(1<<$j):0;
				}
				$size[$i] = chr($buffer);
			}
			$music_file = $this->get_music();
			fclose($this->fp);
			$this->fp = fopen($this->filename,'wb');
			$pads = str_repeat(chr(0),$this->padding_size);
			fputs($this->fp,$tag.$version.chr($this->flag).$size.$header_extended.$data.$pads.$music_file);
			fclose($this->fp);
			clearstatcache();
			$this->file_loaded = false;
			$this->load_file($this->filename);
		}
	}

	/**
	 * Removes frame from file
	 *
	 * @param int $id (for multiple similar frame name)
	 * @param string $frame_name
	 * @return bool For success
	 */
	public function remove_frame($id, $frame_name){
		if($this->tag_readable == true){
			$temp_array = array();
			$c = count($this->tag_array);
			for($i=0;$i<$c;$i++)
				if($this->tag_array[$i]->id != $frame_name || $id != $i)
					$temp_array[] = $this->tag_array[$i];
			$this->tag_array = $temp_array;
			return true;
		}
		else
			return false;
	}

	/**
	 * Returns the music (after the id3v2 tag). Are you able to parse it :) ?
	 *
	 * @return string or FALSE on failure
	 */
	private function get_music(){
		fseek($this->fp,$this->tag_end);
		if(filesize($this->filename)>0)
			return fread($this->fp,filesize($this->filename)-$this->tag_end);
		else
			return FALSE;
	}

	/**
	 * Removes completely tag from file
	 */
	public function remove_tag(){
		$this->tag_readable = false;
		$this->remove_tag = true;
		$this->version_major = FALSE;
		$this->version_revision = FALSE;
		$this->padding_size = FALSE;
		$this->flag = 0;
		$this->header_array['size'] = 0;
		$this->header_array['flags'] = 0;
		$this->header_array['crc32'] = FALSE;
	}

	/**
	 * Set version of the tag. Normally $major is 2 and $revision is 4
	 * Watch out, if you put a number to high, Winamp or other reader won't be able to read it.
	 *
	 * @param int $major
	 * @param int $revision
	 */
	public function set_version($major,$revision){
		$this->version_major = intval($major);
		$this->version_revision = intval($revision);
	}

	/**
	 * Return the version used as an array. Index 0 is the major and 1 is the revision
	 *
	 * @return int[]
	 */
	public function get_version(){
		return array($this->version_major,$this->version_revision);
	}

	/**
	 * Set padding size. Normally this is used to be able to modify the file without
	 * having to rewrite all the music all the time.
	 *
	 * @param int $size
	 */
	public function set_padding($size){
		$this->padding_size = intval($size);
	}

	/**
	 * Get padding size
	 *
	 * @return int
	 */
	public function get_padding(){
		return $this->padding_size;
	}

	/**
	 * Returns the number of known frame (found in $tag_explain)
	 *
	 * @return int
	 */
	public function get_frame_known(){
		$known = 0;
		$c = count($this->tag_array);
		for($i=0;$i<$c;$i++)
			if(array_key_exists($this->tag_array[$i]->id,$this->tag_explain))
				$known++;
		return $known;
	}

	/**
	 * Returns the number of unknown frame (not found in $tag_explain)
	 *
	 * @return int
	 */
	public function get_frame_unknown(){
		$unknown = 0;
		$c = count($this->tag_array);
		for($i=0;$i<$c;$i++)
			if(!array_key_exists($this->tag_array[$i]->id,$this->tag_explain))
				$unknown++;
		return $unknown;
	}

	/**
	 * Returns the header flags
	 *
	 * @return int
	 */
	public function get_header_flags(){
		return $this->flag;
	}

	/**
	 * Set the header flags
	 *
	 * @param int $flags
	 */
	public function set_header_flags($flags){
		$this->flag = intval($flags);
	}

	/**
	 * Get the extended header size
	 *
	 * @return int
	 */
	public function get_extended_size(){
		return $this->header_array['size'];
	}

	/**
	 * Return the crc32 of the tag
	 *
	 * @return int
	 */
	public function get_extended_crc32(){
		return $this->header_array['crc32'];
	}

	/**
	 * Return the flags of the extended header
	 *
	 * @return int
	 */
	public function get_extended_flags(){
		return $this->header_array['flags'];
	}

	/**
	 * Set the flags of the extended header
	 *
	 * @param int $flags
	 */
	public function set_extended_flags($flags){
		$this->header_array['flags'] = intval($flags);
	}
};
?>