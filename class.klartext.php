<?php

//error_reporting(E_ALL);

class KTC {

	function __construct( $cfg ) {
		$this->cfg = $cfg;
	}

	function __destruct( ) {

	}

	private function ValidateFields( $arr, $man_arr ) {
		foreach( $man_arr as $field ) {
			if ( @$arr[ $field ] == null ) {
				throw new Exception( 'Field missing: "'. $field .'"' );
			}
		}
	}

	private function FixDate( $date_str ) {
		//$timezone = new DateTimeZone('UTC');
		$date = new DateTime( $date_str );
		return $date->format('c');
	}

	function SetCustomer( $customer ) {
		// mandatory fields
		$fields = array(
			'Contact',
			'Email',
			'Phone',
		);
		$this->ValidateFields( $customer, $fields );

		// prepare envelope
		$this->customer = $customer;
	}

	function CheckRoute( $route ) {
		// mandatory fields
		$fields = array(
			'DepartsFrom',
			'DepartureDate',
			'Destination',
			'NoPassengers',
		);
		$this->ValidateFields( $route, $fields );

		// fix date formats
		$route['DepartureDate'] = $this->FixDate( $route['DepartureDate'] );
		if ( @$route['DestinationDate'] ) {
			$route['DestinationDate'] = $this->FixDate( $route['DestinationDate'] );
		} else {
			unset( $route['DestinationDate'] );
		}
		return $route;
	}

	function SetRoute( $route ) {
		// finally - prepare envelope
		$this->route = $this->CheckRoute( $route );
	}

	function SetReturnRoute( $route ) {
		// finally - prepare envelope
		$this->return_route = $this->CheckRoute( $route );
	}

	function MakeBooking( $type ) {
		// mandatory checks before making call
		if ( !isset( $this->customer ) ) {
			throw new Exception( 'Customer info is required' );
		}
		if ( !isset( $this->route ) ) {
			throw new Exception( 'Route info is required' );
		}
		// build params
		$params = array(
			'Customer' => $this->customer,
			'Route'    => $this->route,
		);
		// mandatory checks, depending on type of call
		switch ( $type ) {
			case 'BookForTransferBasic':
				break;
			case 'BookForTransferWithReturnBasic':
				if ( !isset( $this->return_route ) ) {
					throw new Exception( 'Return route info is required' );
				}
				$params['ReturnRoute'] = $this->return_route;
				break;
			case 'BookForDisposalBasic':
				if ( !isset( $this->return_route ) ) {
					throw new Exception( 'Return route info is required' );
				}
				$params['ReturnRoute'] = $this->return_route;
				break;
		}
		// extended params
		$params['FtgID']    = $this->cfg['FtgID'];
		$params['KontoNr']  = $this->cfg['KontoNr'];
		$params['user']     = $this->cfg['username'];
		$params['password'] = $this->cfg['password'];

		// soap options
		$options = array(
			'trace'        => true,
			'exceptions'   => false,
			'soap_version' => SOAP_1_2,
			'cache_wsdl'   => WSDL_CACHE_NONE
		);

		// create client
		$client = new SoapClient( $this->cfg['wsdl_uri'] );
		//$client->soap_defencoding = 'UTF-8';

		// make call
		$response = $client->$type( $params );

		// kill client
		unset( $client ); 

		// return response
		$type_result = $type .'Result';
		return $response->$type_result->Description;
	}

}

?>