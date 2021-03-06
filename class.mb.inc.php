<?php 

	class MB
	{

		var $apiBaseUrl = 'https://www.mercadobitcoin.net';

		var $ID 		= '';
		var $SECRET 	= '';

		var $ENDPOINS 	= array(
			'get_account_info' 	=> array('GET', '/tapi/v3/'),
			'list_orders' 		=> array('GET', '/tapi/v3/'),
			'list_system_messages' 		=> array('GET', '/tapi/v3/'),
		);

		var $apiTimeout	= 20;

		function __construct($ID, $SECRET)
		{
			$this->ID = $ID;
			$this->SECRET = $SECRET;
		}

		function cotacao($MOEDA)
		{
			$MOEDA = strtoupper($MOEDA);
			$REQUEST = $this->simpleRequest('/api/'.$MOEDA.'/ticker');
			return json_decode($REQUEST[1], true);
		}

		function get($X, $POST = array())
		{
			if(isset($this->ENDPOINS[$X]))
			{
				$POST = array_merge(array('tapi_method' => $X, 'tapi_nonce' => time()), $POST);
				$REQUEST = $this->request($this->ENDPOINS[$X][0], $this->ENDPOINS[$X][1], $POST);
				return json_decode($REQUEST[1], true);
			}
		}

		function simpleRequest($URI, $POST = array())
		{
			$ch = curl_init();

			$header = array(
				"Accept-Language: pt-BR;q=0.8,en-US;q=0.6,en;q=0.4"
			);

			curl_setopt($ch, CURLOPT_URL, $this->apiBaseUrl . $URI);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

			curl_setopt($ch,CURLOPT_USERAGENT,'');

			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $this->apiTimeout);
			curl_setopt($ch, CURLOPT_TIMEOUT, $this->apiTimeout);

			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);

			if($POST && is_array($POST))
			{
				curl_setopt($ch,CURLOPT_POST, true);
				curl_setopt($ch,CURLOPT_POSTFIELDS, http_build_query($POST));
			}

			$data = curl_exec($ch);
			
			$info = curl_getinfo($ch);

			curl_close($ch);

			return array($info,$data);
		}

		function request($method, $URI, $POST = false)
		{
			$ch = curl_init();

			#$timeout = 20;

			$POST = (array) $POST;

			$ENCODER = $URI . '?' . http_build_query($POST);

			$signedMessage = hash_hmac('sha512', $ENCODER, $this->SECRET);


			$header = array(
				'TAPI-ID: ' . $this->ID,
				'TAPI-MAC: ' . $signedMessage,
				"Accept-Language: pt-BR;q=0.8,en-US;q=0.6,en;q=0.4"
			);			

			curl_setopt($ch, CURLOPT_URL, $this->apiBaseUrl . $URI);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

			curl_setopt($ch,CURLOPT_USERAGENT,'');

			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $this->apiTimeout);
			curl_setopt($ch, CURLOPT_TIMEOUT, $this->apiTimeout);

			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);

		  if($POST && is_array($POST))
		  {
		      curl_setopt($ch,CURLOPT_POST, true);
		      curl_setopt($ch,CURLOPT_POSTFIELDS, http_build_query($POST));
		  }

			$data = curl_exec($ch);
			
			$info = curl_getinfo($ch);

			curl_close($ch);

			return array($info,$data);
		}
	};
