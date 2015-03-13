<?php
/**
 * class Main_Io
 * Class provide extended I/O functionality
 * @category Main
 * @package Main_Io
 * @copyright Copyright (c) 2006-2015 creoLIFE
 * @author Mirek Ratman
 * @version 1.1
 */

class Main_Io
{

	/**
	* Method will check if file name exists
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
                exec( 'mkdir -m '. $chmod .' '. $currentFolder, $o, $status );

                if( !$status ){
                    return false;
                }
			}
		}
	
		return true;
	}


    /**
     * Method read all entries from directory except .htaccess
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
     * Method will read file
     * @param string $filename - name of file
     * @return string
     */
    public static function readFile($filename = null)
    {
        if( self::fileExists($filename) )
        {
            $handle = fopen($filename, "r");
            $contents = fread($handle, ( filesize($filename) == 0 ? 1 : filesize($filename) ) );
            fclose($handle);

            return $contents;
        }
        else
            return false;
    }

    /**
     * Method create file and call for create directory structure if needed 
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

		$out = 'sep=' . $glue . "\n";

        //implode description info from array
        if( $description ){
    		$out .= implode($glue, $description)."\n";
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

    /**
     * Get binary file as base64
     * @param string filename - name of file
     * @return boolean
     */
    public function getFileAsBase64( $filename = null ){
        if( self::fileExists( $filename ) ){
            //set defaults
            return base64_encode( file_get_contents( $filename ) );
        }
        else{
            return false;
        }
    }

}
