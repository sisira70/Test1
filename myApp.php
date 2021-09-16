<?php

$escapeStr = "QUIT";

$nodes;

$inputStr ="";

if ($argc < 2)
{
	die("Please provide CSV file path.");
}

$filePath = $argv[1];

if (file_exists ( $filePath ))
{
	$nodes[] = processCSVFile($filePath);
	
}else
{
die("Unable to open file, please check");
}


while (strcmp($inputStr,$escapeStr) != 0)
{
	$inputStr = readline('Input: ');
	
	if (strcmp($inputStr,$escapeStr) != 0)
	{
	if (processInputString($inputStr))
	{
		echo checkFromToNodesAndCal($nodes[0], $inputStr );	
		
	}else
	{
		echo "Provided input string is not valid. Expected intput string format [Device From] [Device To] [Time(numnber)].".PHP_EOL;
	}
	}
		
}




function checkFromToNodesAndCal(array $nodes, string $str) :string
{
	$check =1;
	$strArray = explode(" ",$str);
	$fromNode =trim($strArray[0]," ");  
	$toNode =trim($strArray[1]," ");  
	$time = (float)$strArray[2];
	$timeCount = 0;
	$valid = true;
	$rtnString =PHP_EOL;
	$lastNode = 1;
	$tempArray = [];
	$tempObjArray = [];
	$isUp = 1;
	
	
	
	for ($id =0 ; $id < count($nodes) ; $id++)
	{
	
		
	foreach($nodes as $node)
	{

		if (strcmp($node["from"],$fromNode) == 0)
		{
			$tempObjArray[] = $node;
			
		}
	}
	
	foreach($tempObjArray as $tmp)
	{
		
		if (strcmp($tmp["to"],$toNode) == 0 )
		{
			if ($check != 0)
			{
				$timeCount += (int)$tmp["delay"];
				$rtnString .= $tmp["from"].' => ';
				$rtnString .= $tmp["to"] .' => '.$timeCount;
				$check = 0;
				$isUp =0;
				if ($timeCount >=  $time)
				{
					$rtnString = "PATH NOT FOUND";
				}
			}
				
				
		}
				
	}
	
	if ( $check == 1)
	{
		$previousVal =0;
		foreach($tempObjArray as $tmp1)
		{
		
			if (strcmp($tmp1["to"],$toNode) != 0 )
			{
				if (!strpos($rtnString,$tmp1["from"]) !== false)
				{
				$previousVal = (int)$tmp1["delay"];	
				$timeCount += (int)$tmp1["delay"];
			    $rtnString .= $tmp1["from"].' => ';
				}
				else
				{
					if ($previousVal > 0)
					{
						$timeCount -=$previousVal;
						$timeCount += (int)$tmp1["delay"];
					}
				}
				
			}
			$fromNode = $tmp1["to"];
		}
		
		
	}
	
	
}

if ($check == 1 && $isUp ==1)
{
	$strArray = explode(" ",$str);
	$fromNode =trim($strArray[1]," ");  
	$toNode =trim($strArray[0]," ");  
	$time = (float)$strArray[2];
	$timeCount = 0;
	$valid = true;
	$rtnString =PHP_EOL;
	$lastNode = 1;
	$tempArray = [];
	$tempObjArray = [];
	
	
	
	
	for ($id =0 ; $id < count($nodes) ; $id++)
	{
	
		
	foreach($nodes as $node)
	{
		
		
		if (strcmp($node["from"],$fromNode) == 0)
		{
			$tempObjArray[] = $node;
			
		}
	}
	
	foreach($tempObjArray as $tmp)
	{
		
		if (strcmp($tmp["to"],$toNode) == 0 )
		{
			if ($check != 0)
			{
				$timeCount += (int)$tmp["delay"];
				$rtnString .= $tmp["from"].' => ';
				$rtnString .= $tmp["to"];
				
				$rtnString = str_replace(PHP_EOL, '', $rtnString);
				
				$nds = explode(" => ",$rtnString);
				
				$revesedNds = array_reverse($nds);
				$rtnString =PHP_EOL;
				foreach($revesedNds as $r)
				{
					$rtnString .= $r." => ";
				}
				$rtnString .= $timeCount;
				
				
				$check = 0;
				$isUp =0;
				if ($timeCount >=  $time)
				{
					$rtnString = "PATH NOT FOUND";
				}
			}
				
				
		}
				
	}
	
	if ( $check == 1)
	{
		$previousVal =0;
		foreach($tempObjArray as $tmp1)
		{
		
			if (strcmp($tmp1["to"],$toNode) != 0 )
			{
				if (!strpos($rtnString,$tmp1["from"]) !== false)
				{
				$previousVal = (int)$tmp1["delay"];	
				$timeCount += (int)$tmp1["delay"];
			    $rtnString .= $tmp1["from"].' => ';
				}
				else
				{
					if ($previousVal > 0)
					{
						$timeCount -=$previousVal;
						$timeCount += (int)$tmp1["delay"];
					}
				}
				
			}
			$fromNode = $tmp1["to"];
		}
		
		
	}
	
	
}
}

	
	if ($isUp != 0 && $check !=0)
	{
		return "PATH NOT FOUND".PHP_EOL;
	}		
	
	return $rtnString.' '.PHP_EOL;
	
}

function processInputString(string $str) :bool
{
	$valid = true;
	
	$strArray = explode(" ",$str);
	
	if (count($strArray) != 3 )
	{
		return false;
	}
	
	if (! is_numeric($strArray[2]))
	{
		return false;
	}
	
	return $valid ;
	
}



function processCSVFile($filePath) : Array
{


		$handle = fopen($filePath, "r");
		if ($handle) {

		while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) 
		{
			if (! is_numeric(trim($data[2])))
			{
				die("incorrect value provided for coloumn no 3, expeted value ia a number");
			}
			
				$nodes[] = array ("from" => trim($data[0]), "to" => trim($data[1]), "delay" => (int)trim($data[2]) );		
					
		}

		fclose($handle);
		} else {
			die("Unable to open file");
		}

		if (is_null($nodes)){
			die("Check file format");
		}


		return $nodes;
}
	

?>