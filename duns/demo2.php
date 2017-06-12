<?php
session_start();
include("includes/connection.php");
include("includes/functions.php");
require("includes/funcstuffs.php");

  $data_string = '{
    "home_url": "https://www.velan.com/",
    "urls": [
        "https://www.velan.com/",
        "https://www.velan.com/en/resources/literature?doctype=3",
        "https://www.velan.com/en/company/at_a_glance"
    ]
}';

$jdata = '{
  "siteId": "alvivddlt05vmookm4nhvrcga5",
    "status": 1
}';
$jd = json_decode($jdata,true);
$m = 0;
foreach ($jd as $key => $dj){
  $arr[$m] = $dj;
  $m++;
}
echo $site_id = $arr[0];
echo $stat = $arr[1];

$data = '{
  "sicDisplay": [
    {
      "code": "Mining",
      "code_type": "division",
      "name": "Mining",
      "children": [
        {
          "code": "13",
          "code_type": "sic2",
          "name": "Oil & Gas Extraction",
          "children": [
            {
              "code": "1382",
              "code_type": "sic4",
              "name": "Oil and Gas Field Exploration Services"
            },
            {
              "code": "1381",
              "code_type": "sic4",
              "name": "Drilling Oil and Gas Wells"
            },
            {
              "code": "1311",
              "code_type": "sic4",
              "name": "Crude Petroleum and Natural Gas"
            },
            {
              "code": "1389",
              "code_type": "sic4",
              "name": "Oil and Gas Field Services NEC"
            }
          ]
        }
      ]
    },
    {
      "code": "Manufacturing",
      "code_type": "division",
      "name": "Manufacturing",
      "children": [
        {
          "code": "28",
          "code_type": "sic2",
          "name": "Chemical & Allied Products",
          "children": [
            {
              "code": "2869",
              "code_type": "sic4",
              "name": "Industrial Organic Chemicals NEC"
            },
            {
              "code": "2813",
              "code_type": "sic4",
              "name": "Industrial Gases"
            },
            {
              "code": "2819",
              "code_type": "sic4",
              "name": "Industrial Inorganic Chemicals NEC"
            },
            {
              "code": "2821",
              "code_type": "sic4",
              "name": "Plastics Material and Synthetic Resins and Nonvulcanizable Elastomers"
            }
          ]
        },
        {
          "code": "35",
          "code_type": "sic2",
          "name": "Industrial Machinery & Equipment",
          "children": [
            {
              "code": "3524",
              "code_type": "sic4",
              "name": "Lawn and Garden Tractors and Home Lawn and Garden Equipment"
            },
            {
              "code": "3542",
              "code_type": "sic4",
              "name": "Machine Tools Metal Forming Type"
            },
            {
              "code": "3533",
              "code_type": "sic4",
              "name": "Oil and Gas Field Machinery and Equipment"
            },
            {
              "code": "3532",
              "code_type": "sic4",
              "name": "Mining Machinery and Equipment Except Oil and Gas Field Machinery and Equipment"
            }
          ]
        },
        {
          "code": "33",
          "code_type": "sic2",
          "name": "Primary Metal Industries",
          "children": [
            {
              "code": "3399",
              "code_type": "sic4",
              "name": "Primary Metal Products NEC"
            },
            {
              "code": "3398",
              "code_type": "sic4",
              "name": "Metal Heat Treating"
            },
            {
              "code": "3317",
              "code_type": "sic4",
              "name": "Steel Pipe and Tubes"
            },
            {
              "code": "3316",
              "code_type": "sic4",
              "name": "Cold-Rolled Steel Sheet Strip and Bars"
            }
          ]
        },
        {
          "code": "38",
          "code_type": "sic2",
          "name": "Instruments & Related Products",
          "children": [
            {
              "code": "3873",
              "code_type": "sic4",
              "name": "Watches Clocks Clockwork Operated Devices and Parts"
            },
            {
              "code": "3823",
              "code_type": "sic4",
              "name": "Industrial Instruments for Measurement Display and Control of Process Variables; and Related Products"
            }
          ]
        },
        {
          "code": "20",
          "code_type": "sic2",
          "name": "Food & Kindred Products",
          "children": [
            {
              "code": "2077",
              "code_type": "sic4",
              "name": "Animal and Marine Fats and Oils"
            },
            {
              "code": "2075",
              "code_type": "sic4",
              "name": "Soybean Oil Mills"
            },
            {
              "code": "2076",
              "code_type": "sic4",
              "name": "Vegetable Oil Mills Except Corn Cottonseed and Soybeans"
            },
            {
              "code": "2074",
              "code_type": "sic4",
              "name": "Cottonseed Oil Mills"
            }
          ]
        },
        {
          "code": "26",
          "code_type": "sic2",
          "name": "Paper & Allied Products",
          "children": [
            {
              "code": "2621",
              "code_type": "sic4",
              "name": "Paper Mills"
            },
            {
              "code": "2611",
              "code_type": "sic4",
              "name": "Pulp Mills"
            },
            {
              "code": "2674",
              "code_type": "sic4",
              "name": "Uncoated Paper and Multiwall Bags"
            },
            {
              "code": "2675",
              "code_type": "sic4",
              "name": "Die-Cut Paper and Paperboard and Cardboard"
            }
          ]
        }
      ]
    }
  ]
}';

$json  = json_decode($data,true);

print_r($json);

$sic4 = array();
$sic2div = array();
$sic2 = array();
$i = 0;
$j = 0;

echo "<br>";
echo "<br>";
echo '<table>';

$si = count($json['sicDisplay']);
for($i=0;$i<$si;$i++){
	$sicdata = $json['sicDisplay'][$i]['code'];
	$sic2 = count($json['sicDisplay'][$i]['children']);
	for($j=0;$j<$sic2;$j++){
		$sicdesc2 = count($json['sicDisplay'][$i]['children'][$j]['children']);
		//echo $sicdata = $json['sicDisplay'][$i]['children'][$j]['code'];
		//echo $sicdata = $json['sicDisplay'][$i]['children'][$j]['name'];
		for($k=0;$k<$sicdesc2;$k++){
			echo "<tr>";
			echo "<td>".$json['sicDisplay'][$i]['name']."</td>";
			echo "<td>".$json['sicDisplay'][$i]['children'][$j]['code']."</td>";
			echo "<td>".$json['sicDisplay'][$i]['children'][$j]['name']."</td>";
			echo "<td>".$json['sicDisplay'][$i]['children'][$j]['children'][$k]['code']."</td>";
			echo "<td>".$json['sicDisplay'][$i]['children'][$j]['children'][$k]['name']."</td>";
			echo "</tr>";

		}
	}
}
echo '</table>';

echo '<style>
    table,td{
    border: 1px solid #0c57a3;
    }

</style>
';

exit;

$url = 'http://localhost:8080/mapsite';

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'Content-Type: application/json',
    'Content-Length: ' . strlen($data_string))
);
echo $result = curl_exec($ch);
curl_close($ch);
echo $json_result = json_decode($result, true);

/*
$qry = "select * from sic_mapper.site_data where site_id='pmstsn46s5t3bflouc6ub3ehr7'";
$rs = mysqli_query($db,$qry) or die("cannot Connect".mysqli_error($db));
$row= mysqli_fetch_array($rs);

echo $row["sic_data"];
$json = json_decode($row["sic_data"],true);

$obj = $json["sic_mappings"][0];*/
?>