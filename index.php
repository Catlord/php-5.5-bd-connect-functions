<?php

require 'bd.php';
global $bd;

$bd=new bd('localhost','root','','database');

$bd->init();

?>
<!DOCTYPE html>
<html lang="en">
<head>
</head>
<body>

<?php

ob_start();
echo 'SELECT 
	id,date,champ1,champ2
FROM database
WHERE actif=1
';
$res=$bd->select(ob_get_clean());

if(count($res)==0) return;
foreach($res AS $row){ 
	echo $row['champ1'];
}

?>

</body>
</html>
