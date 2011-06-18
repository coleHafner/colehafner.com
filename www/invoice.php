<? 
echo '
<!DOCTYPE html>
<html>

<head>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
<title>Slider Demo</title>

<style>
body {
	margin:0px;
	padding:0px;
	background-color:#FFFFFF;
	font-family:arial;
	font-size:14px;
	line-height:1.33em;
}

.align_right { text-align:right; }
.align_left { text-align:left; }

.padder_5 { padding:5px; }
.padder_5_top { padding-top:5px; }
.padder_5_right { padding-right:5px; }
.padder_5_bottom { padding-bottom:5px; }
.padder_5_left { padding-left:5px; }

.padder_10 { padding:10px; }
.padder_10_top { padding-top:10px; }
.padder_10_right { padding-right:10px; }
.padder_10_bottom { padding-bottom:10px; }
.padder_10_left { padding-left:10px; }

.padder_15 { padding:15px; }
.padder_15_top { padding-top:15px; }
.padder_15_right { padding-right:15px; }
.padder_15_bottom { padding-bottom:15px; }
.padder_15_left { padding-left:15px; }

.border_thick { border:7px solid #CCCCCC; }
.border_thick_top { border-top:7px solid #CCCCCC; }
.border_thick_bottom { border-bottom:7px solid #CCCCCC; }

.border_thin { border:3px solid #CCCCCC; }
.border_thin_top { border-top:3px solid #CCCCCC; }
.border_thin_bottom { border-bottom:3px solid #CCCCCC; }

.header {
	position:relative;
	margin-bottom:50px;	
}

.info_header {
	position:relative;
	margin-bottom:10px;
}

.info_body{
	position:relative;
}

.right {
	position:relative;
	float:right;
}

.left {
	position:relative;
	float:left;
}

.clear {
	clear:both;
}

.item_list {
	position:relative;
	border-collapse:collapse;
	width:100%;
}

.item_list td.item { width:33%; }
.item_list tr.header { font-weight:bold; }
.item_list td.price, .item_list td.qty, .item_list td.total { width:22%; }

</style>

</head>

<body>
';

$clients = array(
	'bts' => array(
		'client_info' => "
			Bottom Time Scuba<br/>
			117 North Pacific Hwy<br/>
			Talent, OR 97540<br/>
			(541) 512-0012",
		
		'invoice_info' => "
			Invoice #: BTS001<br/>
			Invoice Date: 2011-06-12<br/>
			Due Date: 2011-06-30",

		'items' => array(
			array( 'item' => "Interface Design", 'price' => "35.00", 'qty' => "9" ),
			array( 'item' => "CMS Implmentation", 'price' => "35.00", 'qty' => "12" ),
			array( 'item' => "Database Schema/Population", 'price' => "35.00", 'qty' => "8" )
		)
	),
	
	'sbc' => array(
		'client_info' => "
			Simple Bicycle Company<br/>
			Yakima, WA 98902<br/>
			(509) 829 - 6272",
		
		'invoice_info' => "
			Invoice #: SBC001<br/>
			Invoice Date: 2011-06-14<br/>
			Due Date: 2011-06-30",

		'items' => array(
			array( 'item' => "Interface Design", 'price' => "35.00", 'qty' => "8" ),
			array( 'item' => "CMS Implmentation", 'price' => "35.00", 'qty' => "3" ),
			array( 'item' => "Database Schema/Population", 'price' => "35.00", 'qty' => "5" )
		)
	)
);


$valid_clients = array_keys( $clients );
$client = strtolower( trim( $_GET['client'] ) );

if( !in_array( $client, $valid_clients ) )
{
	echo '
	<div style="color:#FF0000;font-weight:bold;">
		Error: Client "' . $client . '" is invalid.
	</div>
</body>
';
	
}

echo '
<div class="padder_15">

	<div class="header">
		<div class="left">
			<img src="/images/logo_invoice.png" />
		</div>
		
		<div class="right align_right">
			Cole Hafner<br/>
			240 Suncrest Rd. #24<br/>
			Talent, OR 97540<br/>
			<br/>
			colehafner@gmail.com<br/>
			<a href="http://www.colehafner.com" target="_blank">www.colehafner.com</a><br/>
			(503) 511 - 7496
		</div>
		
		<div class="clear"></div>		
	</div>
	
	<div class="info_header padder_10_top padder_10_bottom border_thick_top">
	
		<div class="left align_left">
			' . $clients[$client]['client_info'] . '
		</div>
		
		<div class="right align_right">
			' . $clients[$client]['invoice_info'] . '
		</div>
		
		<div class="clear"></div>
	</div>
	
	<div class="info_body">
		<table class="item_list">
			<tr class="border_thin_bottom border_thin_top header">
				<td class="item">
					Item
				</td>
				
				<td class="price">
					Price/hr
				</td>
				
				<td class="qty">
					Qty
				</td>
				
				<td class="total align_right">
					Total
				</td>
			</tr>
			';

foreach( $clients[$client]['items'] as $i => $item )
{
	$padder_top = ( $i == 0 ) ? "padder_5_top" : "";
	$total = $item['price'] * $item['qty'];
	$grand_total += $total;
	
	echo '
			<tr>
				<td class="padder_5_bottom ' . $padder_top . '">
					' . $item['item'] . '
				</td>
				<td>
					$' . $item['price'] . '
				</td>
				<td>
					' . $item['qty'] . '
				</td>
				<td class="align_right" style="font-weight:bold;">
					$' . $total . '
				</td>
			</tr>
			';
}

echo '
			<tr class="border_thin_top">
				<td colspan="4">
					<div class="padder_5_bottom"></div>
					&nbsp;
				</td>
			</tr>
		</table>
	</div>
	
	<div class="align_right" style="font-weight:bold;">
		Grand Total: $' . $grand_total . '
	</div>
	
</div>

</body>

</html>
';
?>