<?php

class xrowSassParser extends SassParser
{

    public function parse($source, $isFile = true)
    {
        if (! $source) {
            return $this->toTree($source);
        }
        
        if (is_array($source)) {
            $return = null;
            foreach ($source as $key => $value) {
                if (is_numeric($key)) {
                    $code = $value;
                    $type = true;
                } else {
                    $code = $key;
                    $type = $value;
                }
                if ($return === null) {
                    $return = $this->parse($code, $type);
                } else {
                    $newNode = $this->parse($code, $type);
                    foreach ($newNode->children as $children) {
                        array_push($return->children, $children);
                    }
                }
            }
            return $return;
        }
        
        if ($isFile && $files = SassFile::get_file($source, $this)) {
            $files_source = '';
            foreach ($files as $i => $file) {
                $this->filename = $file;
                $this->syntax = substr(strrchr($file, '.'), 1);
                if ($this->syntax == SassFile::CSS) {
                    $this->property_syntax = "css";
                } elseif (! $this->property_syntax && $this->syntax == SassFile::SCSS) {
                    $this->property_syntax = "scss";
                }
                
                if ($this->syntax !== SassFile::SASS && $this->syntax !== SassFile::SCSS && $this->syntax !== SassFile::CSS) {
                    if ($this->debug) {
                        throw new SassException('Invalid {what}', array(
                            '{what}' => 'syntax option'
                        ));
                    }
                    return FALSE;
                }
                
                $fileContent = SassFile::get_file_contents($this->filename, $this);
                
                $content = "";
                if ( !trim( $fileContent ) )
                {
                    $content = "/* empty: $file */\r\n";
                    continue;
                }
                
                $fileContent = xrowsasspacker::fixImgPaths( $fileContent, $file ); // We need to fix relative background image paths if this is a css file
                if ( $i ) $fileContent = preg_replace('/^@charset[^;]+;/i', '', $fileContent); // Remove @charset if this is not the first file (some browsers will ignore css after a second occurance of this)
                
                $content = "/* start: $file */\r\n" . $fileContent . "\r\n/* end: $file */\r\n\r\n";
            }
            return $this->toTree($content);
        } else {
            return $this->toTree($source);
        }
    }
}