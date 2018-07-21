<?php

abstract class Errors {

	# predefined errors constants
	const SUCCESS = 100;
	const BAD_PARAMETER = 300;
	
	# custom constants
	const BAD_DEVICE_ID		= 301;
	const BAD_UUID			= 302;
	
	const BAD_ACTCODE		= 311;	
	const DAILY_LIMIT		= 312;
	const TIME_LIMIT		= 313;
	
	const BAD_ID_EVENT		= 321;
	const BAD_ID_SCENE		= 322;
	const BAD_VOTE			= 323;
	const BAD_VOTING_CODE	= 324;
	const BAD_ID_ARTIST		= 325;
	
	const MSISDN_NOT_FOUND	= 331;
	const MSISDN_CONFIRMED	= 332;
	const MSISDN_REGISTRED	= 333;
	const MEMBER_IS_VOTE	= 334;
	const VOTE_NO_TIME		= 335;
	

}

?>
