<?php
/**
 * class Main_Io
 * Class provide extended I/O functionality
 * @category Main
 * @package Main_Paginator
 * @copyright Copyright (c) 2006-2012 creoLIFE
 * @author Mirek Ratman
 * @version 1.1
 */

class Main_Io
{

	/**
	* Method will check if file name exists
	* @method fileExists
    * @param string $filename - name of file
	* @return boolean
	*/
    static public function fileExists( $filename ){
		if( $filename !== null && file_exists( $filename ) && !is_dir( $filename ) ){
			return true;
		}
		return false;
	}


    /**
     * Method create a directory with subdirectores if needed 
     * @method makeDir
     * @param string $dirname - name of directory
     * @param string $chmod - directory attributes
	 * @param string $recursive - to make folders recursively (future)
     * @return boolean
     */
    static public function makeDir( $dirname, $chmod = '0777', $recursive = true ){
		if( is_null($dirname) || is_dir( $dirname) || $dirname === "/" || file_exists( $dirname ) ){
			return false;
		}

		$dirs = explode("/",$dirname);

		$currentFolder = '';
		
		for( $x = 0; $x < count( $dirs ); $x++){
			$currentFolder .= $dirs[$x].'/';
			if( !is_dir( $currentFolder ) ){
				if( !mkdir( $currentFolder, $chmod ) ){
					return false;
				}
			}
		}
	
		return true;
	}


    /**
     * Method read all entries from directory except .htaccess
     * @method makeDir
     * @param string $dirname - name of directory
	 * @return mixed - list of directories
     */
    static public function readDirectory( $dirname ){
		if( is_null($dirname) || is_file( $dirname) || $dirname === "/" ){
			return false;
		}

		$exceptions = array('.','..','.htaccess');

		$out = array();

		if( $handle = opendir( $dirname ) ){
			while( false !== ($entry = readdir($handle)) ){
				if( !in_array($entry, $exceptions) ){
					$out[] = $entry;
				}
			}
			closedir($handle);
		}	

		return $out;
	}


    /**
     * Method create file and call for create directory structure if needed 
     * @method writeFile
     * @param string $filename - name of file
     * @param string $content - content to be written in to file
     * @param string $chmod - file attributes
     * @param string $mode - file operation mode
     * @return boolean
     */
	static public function writeFile( $filename, $content = null, $chmod = '0777', $mode = 'w+' ){
		
		$dir = substr( $filename, 0, strrpos($filename,'/') );
		if( !is_dir( $dir ) ){
			self::makeDir( $dir, $chmod );
		}
		$file = "/".substr( $filename, strrpos($filename,'/') , strlen( $filename) );
		$pathtofile = $dir . $file;

		unset($dir,$file);

		if( !is_dir( $pathtofile ) ){
			$f = @fopen( $pathtofile, $mode );

			if( $f ){
				@fwrite($f, $content);
				@fclose($f);
			}
			return true;
		}
		return false;
	}


    /**
     * Method read and parse CSV file to array
     * @method writeFile
     * @param string $filename - name of file
     * @param string $glue - delimeter character
	 * @param string $firstLineDesc - define if first line of CSV file is description
     * @return mixed - csv data
     */
	static public function readCSV( $filename, $glue = ',', $firstLineDesc = true ){
		if( self::fileExists( $filename ) ){
			try{
				$out = new Main_Response_Csv();
				if( ($handle = @fopen($filename, "r")) !== FALSE ){
					$line = 0;
					while( ($data = @fgetcsv($handle, 5000, $glue)) !== FALSE ){
						if( $line === 0 && $firstLineDesc === true ){
							$out->description = $data;
						}
						else{
							$out->data[] = $data;
						}
						$line++;
					}

					@fclose($handle);
					$out->dataCount = count($out->data);
					return $out;
				}
			}
			catch( Exception $e ){
				throw new exception( $e );
			}
		}
		return false;
	}


    /**
     * Method create CSV file based on gived description and data
     * @method writeFile
     * @param string $filename - name of file to be created
	 * @param array $data - define data array
     * @param array $description - define description array
     * @param string $glue - delimeter character
     * @param string $chmod - file attributes
     * @param string $mode - file operation mode
     * @return boolean
     */
	static public function writeCSV( $filename, $data, $description = false, $glue = ',', $chmod = '0777', $mode = 'w+' ){
		if( $description && count($description) !== count($data[0]) ){
			throw new exception( 'number of elements from description array is different to number of elements from data array !' );
			return false;
		}

		$out = null;

        //implode description info from array
        if( $description ){
    		$out = implode($glue, $description)."\n";
        }

        //implode data info from array
        foreach( $data as $key=>$d ){
            $out .= implode($glue, $d)."\n";
		}

        //write or update file
		return self::writeFile( $filename, $out, $chmod, $mode);
	}


    /**
     * Method create CSV file based on gived description and data
     * @method writeBigCSV
     * @param string $filename - name of file to be created
     * @param array $data - define data array
     * @param array $description - define description array
     * @param string $glue - delimeter character
     * @param string $chmod - file attributes
     * @param string $mode - file operation mode
     * @param string $memorySize - size of memory that will be used durring temp file create
     * @return boolean
     */
    static public function writeBigCSV( $filename, $data, $description = false, $glue = ',', $chmod = '0777', $mode = 'w+', $memorySize = 50 ){
        if( $description && count($description) !== count($data[0]) ){
            throw new exception( 'number of elements from description array is different to number of elements from data array !' );
            return false;
        }

        $out = null;

        $fp = fopen('php://temp/maxmemory:' . ($memorySize * 1024 * 1024), 'r+'); // memory allocation

        //implode description info from array
        if( $description ){
            fputcsv($fp, $description);
        }

        //implode data info from array
        foreach( $data as $key=>$d ){
            fputcsv($fp, $d);
        }

        rewind($fp);
        $output = stream_get_contents($fp);
        fclose($fp);

        //write or update file
        return self::writeFile( $filename, $output, $chmod, $mode);
    }


	/**
	* Add line to file
	* @method addLineToFile
    * @param string filename - name of file
    * @param string content - string that will be added to file
    * @param string $chmod - file attributes
	* @return boolean
	*/
	public function addLineToFile( $filename = null, $content = null, $chmod = '0777' ){
		$dir = substr( $filename, 0, strrpos($filename,'/') );
		if( !is_dir( $dir ) ){
			self::makeDir( $dir, $chmod );
		}
		$file = "/".substr( $filename, strrpos($filename,'/') , strlen( $filename) );
		$pathtofile = $dir . $file;

		unset($dir,$file);

		if( !is_dir( $pathtofile ) ){
			$f = @fopen( $pathtofile, 'a+');

			if( $f ){
				@fwrite($f, $content);
				@fclose($f);
			}
			return true;
		}
		return false;
	}


	/**
	* backup existing file
	* @method backupFile
    * @param string filename - name of file
	* @return boolean
	*/
	public function backupFile( $filename = null, $mainBackup = true, $extendedBackup = false, $backupExt = '.bak' ){
        if( self::fileExists( $filename ) ){
            if( $mainBackup ){
                copy( $filename, $filename . $backupExt );
            }
            if( $extendedBackup ){
                copy( $filename, $filename . time() );
            }
        }
        else{
            return false;
        }
	}


	/**
	* Get information about file
	* @method getFileInfo
    * @param string filename - name of file
	* @return boolean
	*/
	public function getFileInfo( $filename = null ){
        if( self::fileExists( $filename ) ){
            //set defaults
            return stat( $filename );
        }
        else{
            return false;
        }
	}


}
