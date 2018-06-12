<?php
if(!$_GET['time'])
{
	$_GET['time'] = date('Y-m-d H:i:s');
}

$data = array();
$date = $_GET['time'];

$db = new mysqli('localhost', 'root', '3333', 'eatery');
$db->query('SET NAMES utf8');

for($i=0; $i<80; $i++)
{
	$res = $db->query('
		SELECT * FROM order_menu WHERE time > "' . $_GET['time'] . '"');

	if($res->num_rows > 0)
	{
		while($v = $res->fetch_array(MYSQLI_ASSOC))
		{
			$data[] = $v;
			$date = $v['time'];
		}
		break;
	}
	usleep(250000);
}
echo json_encode(array('time' => $date, 'data' => $data));
?>
