<?php 

header('Content-Type: text/html; charset=utf-8');

function replaceAll($string) {

	return strtolower(trim(preg_replace('~[^0-9a-z.]+~i', null, preg_replace('~&([a-z]{1,2})(acute|cedil|circ|grave|lig|orn|ring|slash|th|tilde|uml);~i', null,
 htmlentities($string, ENT_QUOTES, 'UTF-8'))), null));

}


function dirToArray($dir) { 
   
   $result = array(); 

   $cdir = scandir($dir); 
   foreach ($cdir as $key => $value) 
   { 
      if (!in_array($value,array(".","..", ".DS_Store"))) 
      { 
         if (is_dir($dir . DIRECTORY_SEPARATOR . $value)) 
         { 
            $result[$value] = dirToArray($dir . DIRECTORY_SEPARATOR . $value); 
         }
 
	 rename($dir . DIRECTORY_SEPARATOR . $value, $dir . DIRECTORY_SEPARATOR . replaceAll($value)); 
      } 
   } 
   
   return $result; 
}

dirToArray('produtos_for_android');
