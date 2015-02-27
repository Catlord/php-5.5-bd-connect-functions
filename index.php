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
ORDER BY date DESC
LIMIT 1
';
$res=$bd->sql(ob_get_clean());

while($row=$bd->result($res)){
	echo $row['champ1'];
}

?>

</body>
</html>