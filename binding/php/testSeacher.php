<?php
/**
 * Ip2Region php client test script
 *
 * @author	chenxin<chenxin619315@gmail.com>
*/

if ( $argc < 2 ) 
{
	$usage = <<<EOF
Usage: php Test.php [ip2region db file] [alrogrithm]
+-Algorithm: binary or b-tree\n
EOF;
	exit($usage);
}

array_shift($argv);
$dbFile		= $argv[0];
$method		= 1;
$algorithm	= 'B-tree';
if ( isset($argv[1])
	&& strtolower($argv[1]) == 'binary' ) {
	$method		= 2;
	$algorithm	= 'Binary';
}

require dirname(__FILE__) . '/Ip2Region.class.php';
$ip2regionObj = new Ip2Region($dbFile);

$initStr = <<<INIT
initializing  {$algorithm} ... 
+----------------------------------+
| ip2region test script            |
| Author: chenxin619315@gmail.com  |
| Type 'quit' to exit program      |
+----------------------------------+
INIT;
echo $initStr, "\n";

while ( true )
{
	echo "ip2region>> ";
	$line = trim(fgets(STDIN));
	if ( strlen($line) < 2 ) continue;
	if ( $line == 'quit'  ) break;
	if ( ip2long($line) == NULL ) {
		echo "Error: invalid ip address\n";
		continue;
	}

	$s_time = getTime();
	$data	= $method==2 ? $ip2regionObj->binarySearch($line) : $ip2regionObj->btreeSearch($line);
	$c_time = getTime() - $s_time;
	printf("%s|%s in %.5f millseconds\n", $data['city_id'], $data['region'], $c_time);
}

function getTime()
{
	return (microtime(true) * 1000);
}
?>