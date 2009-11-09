<?

$CWD = getcwd();
$filename = __FILE__;
$PATH_LIB = dirname($filename);

$PATH_LIB = $PATH_LIB;


$cwd_split = split("/",$CWD);
$pathlib_split = split("/",$PATH_LIB);



$i = 0;

$cont = true;
while ($cont && ($i < sizeof ($cwd_split))&& ($i < sizeof($pathlib_split)) ){
	//echo $i." ".$cwd_split[$i]."<BR> ";
	if ($cwd_split[$i] != $pathlib_split[$i]){
		$cont = false;
	}
	else{
		$i++;
	}
}

$newPath = "";
for ($j = $i ; ($j < sizeof ($cwd_split)); $j++){
	$newPath .= "../";
}

//echo "A ".$newPath."<BR>";


for ($j = $i ; ($j < sizeof ($pathlib_split)); $j++){
	$newPath .= $pathlib_split[$j]."/";

} 
//echo "B ".$newPath."<BR>";


//echo "<BR>[". $newPath."]<BR>";
//exit();

/*
while(list($key,$value)=each($_REQUEST)) {
	$GLOBALS[$key]=$value;
}
*/
$PATH_LIB = $newPath;


/**
    Retorna el valor de la variable o "" sino esta declarada
*/
function glb($variableName){
    if (array_key_exists ($variableName , $GLOBALS)){
        return $GLOBALS[$variableName];
    }
    return "";
}


?>
