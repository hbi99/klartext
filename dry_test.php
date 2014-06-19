<?php

// include the class
require_once 'class.klartext.php';

// config values
$cfg = array(
	'wsdl_uri' => 'http://klartextws.sindas.se/PATH-TO-WSDL-SERVICE',
	'username' => 'xxxxxxxxxxx',
	'password' => 'xxxxxxxxxxx',
	'FtgID'    => 0,
	'KontoNr'  => 'NULL',
);

$customer = array(
	'Contact'    => 'Hakan Bilgin',
	'Email'      => 'hbi99@hotmail.com',
	'Address'    => 'Langgatan 123',
	'PostalCode' => '12342',
	'City'       => 'Stockholm',
	'Company'    => 'Company INC',
	'Fax'        => '1',
	'Mobile'     => '12',
	'Phone'      => '123',
	'OrgNo'      => '1234',
	'Other'      => 'Message here',
);
$route = array(
	'NoPassengers'    => '1',
	'DepartsFrom'     => 'Klagshamnsvagen, Klagshamn, Sweden',
	'DepartureDate'   => '2014-05-22 12:30:00',
	'DepartsFromInfo' => '',
	'Destination'     => 'Goteborg, Sweden',
	'DestinationDate' => '',
	'DestinationInfo' => '',
	'Other'           => '',
	'Itinerary'       => '',
);
$return_route = array(
	'NoPassengers'    => '1',
	'DepartsFrom'     => 'Goteborg, Sweden',
	'DepartureDate'   => '2014-05-24 12:30:00',
	'DepartsFromInfo' => '',
	'Destination'     => 'Stockholm, Sweden',
	'DestinationDate' => '',
	'DestinationInfo' => '',
	'Other'           => '',
	'Itinerary'       => '',
);

// create class
$ktc = new KTC( $cfg );

// set customer
$ktc->SetCustomer( $customer );

// set route
$ktc->SetRoute( $route );

// set return_route
$ktc->SetReturnRoute( $return_route );

// make call to KlarText
//$ktc->MakeBooking( 'BookForTransferBasic' );
//$ktc->MakeBooking( 'BookForTransferWithReturnBasic' );
$ref_nr = $ktc->MakeBooking( 'BookForDisposalBasic' );

echo $ref_nr;

?>