<?php

namespace sletatru;

/**
 * Class for a cb RF service
 */
class XmlGate extends BaseServiceSoap
{
	/**
	 * @var string
	 */
	public $wsdl = 'http://module.sletat.ru/XmlGate.svc?wsdl';
	/**
	 * @var string
	 */
	public $login = '';
	/**
	 * @var string
	 */
	public $password = '';


	/**
	 * @param int $countryId
	 * @param int $cityFromId
	 * @param array $cities
	 * @param array $meals
	 * @param array $stars
	 * @param array $hotels
	 * @param int $adults
	 * @param int $kids
	 * @param array $kidsAges
	 * @param int $nightsMin
	 * @param int $nightsMax
	 * @param int $priceMin
	 * @param int $priceMax
	 * @param string $currencyAlias
	 * @param string $departFrom
	 * @param string $departTo
	 * @param bool $hotelIsNotInStop
	 * @param bool $hasTickets
	 * @param bool $ticketsIncluded
	 * @param bool $useFilter
	 * @param array $f_to_id
	 * @param bool $includeDescriptions
	 * @param int $cacheMode
	 * @return string
	 */
	public function CreateRequest(
		$countryId, 
		$cityFromId, 
		$cities = null, 
		$meals = null, 
		$stars = null, 
		$hotels = null,
		$adults = 2,
		$kids = 0,
		$kidsAges = null,
		$nightsMin = 7,
		$nightsMax = 7,
		$priceMin = null,
		$priceMax = null,
		$currencyAlias = 'RUB',
		$departFrom = null,
		$departTo = null,
		$hotelIsNotInStop = false,
		$hasTickets = false,
		$ticketsIncluded = false,
		$useFilter = false,
		array $f_to_id = null,
		$includeDescriptions = false,
		$cacheMode = 0
	){
		$params = array();
		if (!empty($countryId)) {
			$params[0]['countryId'] = $countryId;
		}
		if (!empty($cityFromId)) {
			$params[0]['cityFromId'] = $cityFromId;
		}
		if (is_array($cities) && !empty($cities)) {
			$params[0]['cities'] = $cities;
		}
		if (is_array($meals) && !empty($meals)) {
			$params[0]['meals'] = $meals;
		}
		if (is_array($stars) && !empty($stars)) {
			$params[0]['stars'] = $stars;
		}
		if (is_array($hotels) && !empty($hotels)) {
			$params[0]['hotels'] = $hotels;
		}
		if (($adults = (int) $adults) > 0) {
			$params[0]['adults'] = $adults;
		}
		if (($kids = (int) $kids) > 0) {
			$params[0]['kids'] = $kids;
		}
		if (is_array($kidsAges) && !empty($kidsAges)) {
			$params[0]['kidsAges'] = $kidsAges;
		}
		if (($nightsMin = (int) $nightsMin) > 0) {
			$params[0]['nightsMin'] = $nightsMin;
		}
		if (($nightsMax = (int) $nightsMax) > 0) {
			$params[0]['nightsMax'] = $nightsMax;
		}
		if (($priceMin = (int) $priceMin) > 0) {
			$params[0]['priceMin'] = $priceMin;
		}
		if (($priceMax = (int) $priceMax) > 0) {
			$params[0]['priceMax'] = $priceMax;
		}
		if (!empty($currencyAlias)) {
			$params[0]['currencyAlias'] = $currencyAlias;
		}
		if (!empty($departFrom) && ($time = strtotime($departFrom))) {
			$params[0]['departFrom'] = date('d.m.Y', $time);
		}
		if (!empty($departTo) && ($time = strtotime($departTo))) {
			$params[0]['departTo'] = date('d.m.Y', $time);
		}
		$params[0]['hotelIsNotInStop'] = (bool) $hotelIsNotInStop;
		$params[0]['hasTickets'] = (bool) $hasTickets;
		$params[0]['ticketsIncluded'] = (bool) $ticketsIncluded;
		$params[0]['useFilter'] = (bool) $useFilter;
		if (is_array($f_to_id) && !empty($f_to_id)) {
			$params[0]['f_to_id'] = $f_to_id;
		}
		$params[0]['includeDescriptions'] = (bool) $includeDescriptions;
		$params[0]['cacheMode'] = (int) $cacheMode;

		$res = $this->doSoapCall('CreateRequest', $params);
		return !empty($res->CreateRequestResult) ? $res->CreateRequestResult : null;
	}


	/**
	 * @param string $requestId
	 */
	public function GetRequestState($requestId)
	{
		$params[0]['requestId'] = $requestId;
		return $this->parseDictionary('GetRequestState', 'OperatorLoadState', $params);
	}

	/**
	 * @param string $requestId
	 */
	public function GetRequestResult($requestId)
	{
		$params[0]['requestId'] = $requestId;
		$res = $this->doSoapCall('GetRequestResult', $params);
		$return = array();
		if (!empty($res->GetRequestResultResult)) {
			if (!empty($res->GetRequestResultResult->HotelCount)) {
				$return['HotelCount'] = (int) $res->GetRequestResultResult->HotelCount;
			}
			$return['OperatorLoadState'] = $this->serviceResponseArrayToArray(
				$res->GetRequestResultResult,
				'LoadState',
				'OperatorLoadState'
			);
			$return['OilTaxes'] = $this->serviceResponseArrayToArray(
				$res->GetRequestResultResult,
				'OilTaxes',
				'XmlTourOilTax'
			);
			if (!empty($res->GetRequestResultResult->RequestId)) {
				$return['RequestId'] = (int) $res->GetRequestResultResult->RequestId;
			}
			$return['Rows'] = $this->serviceResponseArrayToArray(
				$res->GetRequestResultResult,
				'Rows',
				'XmlTourRecord'
			);
			if (!empty($res->GetRequestResultResult->RowsCount)) {
				$return['RowsCount'] = (int) $res->GetRequestResultResult->RowsCount;
			}
		}		
		return $return;
	}
	

	/**
	 * @return array
	 */
	public function GetDepartCities()
	{
		return $this->parseDictionary('GetDepartCities', 'City');
	}

	/**
	 * @param string $townFromId
	 * @return array
	 */
	public function GetCountries($townFromId = null)
	{
		$params = array();
		if (!empty($townFromId)) {
			$params[0]['townFromId'] = $townFromId;
		}
		return $this->parseDictionary('GetCountries', 'Country', $params);
	}

	/**
	 * @param string $countryId
	 * @return array
	 */
	public function GetCities($countryId)
	{
		$params = array();
		if (!empty($countryId)) {
			$params[0]['countryId'] = $countryId;
		}
		return $this->parseDictionary('GetCities', 'City', $params);
	}

	/**
	 * @param string $countryId
	 * @param array $towns
	 * @return array
	 */
	public function GetHotelStars($countryId, array $towns = null)
	{
		$params = array();
		if (!empty($countryId)) {
			$params[0]['countryId'] = $countryId;
		}
		if (!empty($towns)) {
			$params[0]['towns'] = $towns;
		}
		return $this->parseDictionary('GetHotelStars', 'HotelStars', $params);
	}

	/**
	 * @return array
	 */
	public function GetMeals()
	{
		return $this->parseDictionary('GetMeals', 'Meal');
	}

	/**
	 * @param string $townFromId
	 * @param string $countryId
	 * @return array
	 */
	public function GetTourOperators($townFromId = null, $countryId = null)
	{
		$params = array();
		if (!empty($townFromId)) {
			$params[0]['townFromId'] = $townFromId;
		}
		if (!empty($countryId)) {
			$params[0]['countryId'] = $countryId;
		}
		return $this->parseDictionary('GetTourOperators', 'TourOperator', $params);
	}

	/**
	 * @param string $countryId
	 * @param array $towns
	 * @param array $stars
	 * @param string $filter
	 * @param int $count
	 * @return array
	 */
	public function GetHotels($countryId, $towns = null, $stars = null, $filter = null, $count = -1)
	{
		$params = array();
		$params[0]['countryId'] = $countryId;
		$params[0]['count'] = (int) $count;
		if (!empty($towns) && is_array($towns)) {
			$params[0]['towns'] = $towns;
		}
		if (!empty($stars) && is_array($stars)) {
			$params[0]['stars'] = $stars;
		}
		if ($filter !== null) {
			$params[0]['stars'] = trim($filter);
		}
		return $this->parseDictionary('GetHotels', 'Hotel', $params);
	}


	/**
	 * @param string $method
	 * @param string $item
	 */
	protected function parseDictionary($method, $item, array $params = array())
	{
		$return = array();
		$res = $this->doSoapCall($method, $params);
		$resName = $method . 'Result';
		return $this->serviceResponseArrayToArray($res, $resName, $item);
	}

	/**
	 * @param \stdClass $res
	 * @param string $resName
	 * @param string $item
	 */
	protected function serviceResponseArrayToArray($res, $resName, $item)
	{
		$return = array();
		if (!empty($res->$resName->$item)) {
			if (is_array($res->$resName->$item)) {
				foreach ($res->$resName->$item as $li) {
					$return[] = $this->serviceResponceItemToArray($li);
				}
			} else {
				$return[] = $this->serviceResponceItemToArray($res->$resName->$item);
			}	
		}
		return $return;
	}

	/**
	 * @param mixed $array
	 * @return array
	 */
	protected function serviceResponceItemToArray($array)
	{
		$return = array();
		$toArray = (array) $array;
		foreach ($toArray as $key => $value) {
			if ($value === 'NIL') {
				$return[$key] = null;
			} else {
				$return[$key] = $value;
			}
		}
		return $return;
	}

	/**
	 * @param string $wsdl
	 * @param array $soapOptions
	 * @return \SoapClient
	 */
	protected function createSoapClient($wsdl, array $soapOptions = array())
	{
		$client = parent::createSoapClient($wsdl, $soapOptions);
		$client->__setSoapHeaders(new \SoapHeader('urn:SletatRu:DataTypes:AuthData:v1', 'AuthInfo', array(
			'Login' => $this->login,
			'Password' => $this->password,
		)));
		return $client;
	}
}