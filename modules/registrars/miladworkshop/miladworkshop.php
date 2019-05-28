<?php
function miladworkshop_getConfigArray()
{
	$configarray = array(
		"AccessKey" => array( "Type" => "text", "Size" => "128", "Required" => true, "Description" => "لطفا کلید دسترسی به وب سرویس میلاد ورک شاپ را وارد کنید"),
		"AdminMble" => array( "Type" => "text", "Size" => "36",  "Required" => true, "Description" => "شماره موبایل خود را برای ارسال خطاهای سیستمی وارد کنید - در صورتی که مایل به دریافت پیامک نیستید این فیلد را خالی بگذارید")
	);

	return $configarray;
}

function miladworkshop_RegisterDomain($params)
{
	$domain_name = "{$params["sld"]}.{$params["tld"]}";
	$Period = (isset($params["regperiod"]) && $params["regperiod"] == 5) ? 5 : 1;

	$curl = curl_init();
	curl_setopt($curl, CURLOPT_URL, 'https://miladworkshop.ir/webservice/rest/DomainRegister');
	curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type' => 'application/json'));
	curl_setopt($curl, CURLOPT_POSTFIELDS, "AccessKey={$params["AccessKey"]}&NicHandle={$params["additionalfields"]["holder_id"]}&Domain={$domain_name}&Period={$Period}&NameServer1={$params["ns1"]}&NameServer2={$params["ns2"]}&NameServer3={$params["ns3"]}&NameServer4={$params["ns4"]}");
	curl_setopt($curl, CURLOPT_TIMEOUT, 30);
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	$curl_exec = curl_exec($curl);
	curl_close($curl);

	$result = json_decode($curl_exec);

	if (isset($result->Response) && $result->Response != "")
	{
		$miladworkshop_log = $_SERVER['DOCUMENT_ROOT']."/miladworkshop_log.txt";
		$fopen = fopen($miladworkshop_log, 'a');
		fwrite($fopen, "domainRegister => {$domain_name} ( Response : {$result->Response} )\r\n");
		fclose($fopen);
		
		if (isset($result->Response) && $result->Response == 100)
		{	
			return array(
				'success' => true,
			);
		} else {
			if (isset($params["AdminMble"]) && $params["AdminMble"] != "")
			{
				$Message = "خطا در ثبت دامنه ملی - کد خطا : {$result->Response}";

				$sms_curl = curl_init();
				curl_setopt($sms_curl, CURLOPT_URL, 'https://miladworkshop.ir/webservice/rest/SMSrequest');
				curl_setopt($sms_curl, CURLOPT_HTTPHEADER, array('Content-Type' => 'application/json'));
				curl_setopt($sms_curl, CURLOPT_POSTFIELDS, "AccessKey={$params["AccessKey"]}&Phone={$params["AdminMble"]}&Message={$Message}");
				curl_setopt($sms_curl, CURLOPT_TIMEOUT, 30);
				curl_setopt($sms_curl, CURLOPT_SSL_VERIFYPEER, false);
				curl_setopt($sms_curl, CURLOPT_RETURNTRANSFER, true);
				$sms_curl_exec = curl_exec($sms_curl);
				curl_close($sms_curl);
			}

			return array(
				'error' => $result->Response,
			);
		}
	} else {
		return array(
			'error' => "WebserviceError {$result->Response}",
		);
	}
}

function miladworkshop_RenewDomain($params)
{
	$domain_name = "{$params["sld"]}.{$params["tld"]}";
	$Period = (isset($params["regperiod"]) && $params["regperiod"] == 5) ? 5 : 1;

	$curl = curl_init();
	curl_setopt($curl, CURLOPT_URL, 'https://miladworkshop.ir/webservice/rest/DomainRenew');
	curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type' => 'application/json'));
	curl_setopt($curl, CURLOPT_POSTFIELDS, "AccessKey={$params["AccessKey"]}&Domain={$domain_name}&Period={$Period}");
	curl_setopt($curl, CURLOPT_TIMEOUT, 30);
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	$curl_exec = curl_exec($curl);
	curl_close($curl);

	$result = json_decode($curl_exec);

	if (isset($result->Response) && $result->Response != "")
	{
		$miladworkshop_log = $_SERVER['DOCUMENT_ROOT']."/miladworkshop_log.txt";
		$fopen = fopen($miladworkshop_log, 'a');
		fwrite($fopen, "domainRenew => {$domain_name} ( Response : {$result->Response} )\r\n");
		fclose($fopen);
		
		if (isset($result->Response) && $result->Response == 100)
		{	
			return array(
				'success' => true,
			);
		} else {
			if (isset($params["AdminMble"]) && $params["AdminMble"] != "")
			{
				$Message = "خطا در تمدید دامنه ملی - کد خطا : {$result->Response}";

				$sms_curl = curl_init();
				curl_setopt($sms_curl, CURLOPT_URL, 'https://miladworkshop.ir/webservice/rest/SMSrequest');
				curl_setopt($sms_curl, CURLOPT_HTTPHEADER, array('Content-Type' => 'application/json'));
				curl_setopt($sms_curl, CURLOPT_POSTFIELDS, "AccessKey={$params["AccessKey"]}&Phone={$params["AdminMble"]}&Message={$Message}");
				curl_setopt($sms_curl, CURLOPT_TIMEOUT, 30);
				curl_setopt($sms_curl, CURLOPT_SSL_VERIFYPEER, false);
				curl_setopt($sms_curl, CURLOPT_RETURNTRANSFER, true);
				$sms_curl_exec = curl_exec($sms_curl);
				curl_close($sms_curl);
			}

			return array(
				'error' => $result->Response,
			);
		}
	} else {
		return array(
			'error' => "WebserviceError",
		);
	}
}

function miladworkshop_GetNameservers($params)
{
	$domain_name = "{$params["sld"]}.{$params["tld"]}";

	$curl = curl_init();
	curl_setopt($curl, CURLOPT_URL, 'https://miladworkshop.ir/webservice/rest/DomainInfo');
	curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type' => 'application/json'));
	curl_setopt($curl, CURLOPT_POSTFIELDS, "AccessKey={$params["AccessKey"]}&Domain={$domain_name}");
	curl_setopt($curl, CURLOPT_TIMEOUT, 30);
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	$curl_exec = curl_exec($curl);
	curl_close($curl);

	$result = json_decode($curl_exec);

	if (isset($result->Response) && $result->Response == 100)
	{
        return array(
            'ns1' => $result->ns1,
            'ns2' => $result->ns2,
            'ns3' => $result->ns3,
            'ns4' => $result->ns4,
            'ns5' => $result->ns5,
			'success' => true,
        );
	} else {
		return array(
			'error' => "WebserviceError",
		);
	}   
}

function miladworkshop_SaveNameservers($params)
{
	$domain_name = "{$params["sld"]}.{$params["tld"]}";

	$curl = curl_init();
	curl_setopt($curl, CURLOPT_URL, 'https://miladworkshop.ir/webservice/rest/DomainUpdateDNS');
	curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type' => 'application/json'));
	curl_setopt($curl, CURLOPT_POSTFIELDS, "AccessKey={$params["AccessKey"]}&NicHandle={$params["additionalfields"]["holder_id"]}&Domain={$domain_name}&NameServer1={$params["ns1"]}&NameServer2={$params["ns2"]}&NameServer3={$params["ns3"]}&NameServer4={$params["ns4"]}");
	curl_setopt($curl, CURLOPT_TIMEOUT, 30);
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	$curl_exec = curl_exec($curl);
	curl_close($curl);

	$result = json_decode($curl_exec);
	
	if (isset($result->Response) && $result->Response == 100)
	{
        return array(
            'success' => true,
        );
	} else {
		return array(
			'error' => $result->Response,
		);
	}
}

function miladworkshop_TransferDomain($params)
{
	$domain_name = "{$params["sld"]}.{$params["tld"]}";
	$Period = (isset($params["regperiod"]) && $params["regperiod"] == 5) ? 5 : 1;

	$curl = curl_init();
	curl_setopt($curl, CURLOPT_URL, 'https://miladworkshop.ir/webservice/rest/DomainTransfer');
	curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type' => 'application/json'));
	curl_setopt($curl, CURLOPT_POSTFIELDS, "AccessKey={$params["AccessKey"]}&Domain={$domain_name}&Period={$Period}&NameServer1={$params["ns1"]}&NameServer2={$params["ns2"]}&NameServer3={$params["ns3"]}&NameServer4={$params["ns4"]}");
	curl_setopt($curl, CURLOPT_TIMEOUT, 30);
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	$curl_exec = curl_exec($curl);
	curl_close($curl);

	$result = json_decode($curl_exec);

	if (isset($result->Response) && $result->Response != "")
	{
		$miladworkshop_log = $_SERVER['DOCUMENT_ROOT']."/miladworkshop_log.txt";
		$fopen = fopen($miladworkshop_log, 'a');
		fwrite($fopen, "DomainTransfer => {$domain_name} ( Response : {$result->Response} )\r\n");
		fclose($fopen);
		
		if (isset($result->Response) && $result->Response == 100)
		{	
			return array(
				'success' => true,
			);
		} else {
			if (isset($params["AdminMble"]) && $params["AdminMble"] != "")
			{
				$Message = "خطا در انتقال دامنه ملی - کد خطا : {$result->Response}";

				$sms_curl = curl_init();
				curl_setopt($sms_curl, CURLOPT_URL, 'https://miladworkshop.ir/webservice/rest/SMSrequest');
				curl_setopt($sms_curl, CURLOPT_HTTPHEADER, array('Content-Type' => 'application/json'));
				curl_setopt($sms_curl, CURLOPT_POSTFIELDS, "AccessKey={$params["AccessKey"]}&Phone={$params["AdminMble"]}&Message={$Message}");
				curl_setopt($sms_curl, CURLOPT_TIMEOUT, 30);
				curl_setopt($sms_curl, CURLOPT_SSL_VERIFYPEER, false);
				curl_setopt($sms_curl, CURLOPT_RETURNTRANSFER, true);
				$sms_curl_exec = curl_exec($sms_curl);
				curl_close($sms_curl);
			}

			return array(
				'error' => $result->Response,
			);
		}
	} else {
		return array(
			'error' => "WebserviceError",
		);
	}
}
?>