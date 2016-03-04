<?php


function dirToArray($dir) { 
	
	$result = []; 
	
	$cdir = scandir($dir); 
	foreach ($cdir as $key => $value) { 
		
		if (!in_array($value,array(".", "..", ".DS_Store"))) { 
			if (is_dir($dir . DIRECTORY_SEPARATOR . $value)) {
				$ct['name'] = $value;
				$ct['data'] = dirToArray($dir . DIRECTORY_SEPARATOR . $value); 
         			$result[] = $ct;
			} else {
				$ext = end(explode('.', $value));
				if( $ext == 'docx' and $value != 'Observação.docx' ) {
					$archiveFile = __DIR__ . DIRECTORY_SEPARATOR . $dir . DIRECTORY_SEPARATOR . $value;
						
					$striped_content = '';
        				$content = '';
					$zip = zip_open($archiveFile);
				
					while ($zip_entry = zip_read($zip)) {

            					if (zip_entry_open($zip, $zip_entry) == FALSE) continue;

            					if (zip_entry_name($zip_entry) != "word/document.xml") continue;
	
            					$content .= zip_entry_read($zip_entry, zip_entry_filesize($zip_entry));
							
            					zip_entry_close($zip_entry);
        				}

        				zip_close($zip);
					$content = str_replace('</w:r></w:p></w:tc><w:tc>', " ", $content);
        				$content = str_replace('</w:r></w:p>', "\r\n", $content);
					$content = str_replace('</w:p>', "\r\n", $content);
        				$striped_content = strip_tags($content);
					
					$keyName = '';
					if( strstr($value, 'medidas') ) {
						$keyName = 'medidas';
					} else {
						$keyName = 'descricao';
					}			
										
					$result[] = $striped_content;
		
				} else {
					$keyName = 'imagem';
					if( $ext == 'pdf' ) {
						$keyName = 'manual';
					}
					$result[] = $value;
				}		
         		}
      		} 
   	} 
   	return $result; 
} 

header('Content-type: application/json');
echo(json_encode(dirToArray('produtos')));
