<?php

abstract class Errors
{
    #MAIN ERRORS\
    const SUCCESS = 100;
    const FAIL = 101;
    const CODE_TIME_LIMIT = 102;
    const NOT_UNIQUE_PARAMETER = 103;
    const NO_DATA_FOUND = 104;
    const NO_ACTION_FOUND = 105;
    const ACCESS_DENIED = 106;
    const WRONG_CODE = 107;
    const TOKEN_EXPIRED = 108;

    const EMAIL_ALREADY_EXISTS = 108;
    const CAR_NUMBER_ALREADY_EXISTS = 109;
    const BAD_CAR_NUMBER = 110;
    const BAD_CAR_CODE = 111;
    const INCORRECT_OBJ_TYPE = 112;

    const BAD_PARAMETER = 300;
    const BAD_EXTENSION = 301;
    const BAD_SIZE = 302;
    const BAD_WIDTH = 303;
    const BAD_HEIGHT = 304;

    #CUSTOM ERRORS\
    const NO_OBJECTS_TO_RETURN = 301;
    const INCORRECT_AUTH = 308;

    #BANK ERRORS\
    const CHANNEL_IS_NULL = 319;
    const USER_IS_NULL = 320;
    const MEMBER_NOT_FOUND = 321;
    const REASON_NOT_FOUND = 322;

    const MEMBER_NOT_CONFIRMED = 323;

    const TEMPLATE_NOT_FOUND = 324;
    const NO_DB_CONNECT_2 = 325;
    const INCORRECT_USER_FORMAT = 326;
    const UNKNOWN_CHANNEL = 327;
    const USER_ALREADY_EXISTS = 328;
    const TOO_MUCH_CHARACTERS_IN_USER = 329;
    const TEMPLATE_IS_NULL = 330;

    #TRANSFER ERRORS\
    const SENDER_TRANSFER_PARAMETERS_ARE_NULL = 331;
    const RECEIVER_TRANSFER_PARAMETERS_ARE_NULL = 332;
    const SENDER_CARD_CVC2_IS_NULL = 333;
    const ERROR_IN_SENDER_CARD_CVC2 = 334;
    const AMOUNT_IS_NULL = 335;
    const ERROR_IN_AMOUNT = 336;
    const DUPLICATE_USER_PARAMETERS = 337;
    const ERROR_IN_SENDER_CARD_NUMBER = 338;
    const ERROR_IN_SENDER_CARD_EXP = 339;
    const ERROR_IN_SENDER_CARD_EXP_YEAR = 340;
    const SENDER_CARD_NOT_FOUND_BY_ID = 341;
    const SENDER_CARD_EXP_MONTH_NOT_FOUND_AND_NOT_PRESENTED = 342;
    const SENDER_CARD_EXP_YEAR_NOT_FOUND_AND_NOT_PRESENTED = 343;
    const ERROR_IN_RECEIVER_CARD_NUMBER = 344;
    const RECEIVER_CARD_NOT_FOUND_BY_ID = 345;
    const SENDERS_CARD_NOT_ENROLLED_TO_3DS = 346;
    const TECHNICAL_ERROR = 347;


    const CARD_ALREADY_EXISTS = 360;
    const ERROR_IN_CARD_NUMBER = 361;
    const EXCEEDED_ALLOWED_QUANTITY_OF_CARDS_FOR_USER = 362;
    const CARD_NOT_FOUND = 363;
    const CARD_DATA_IS_NOT_DIFFERENT_FROM_CURRENT = 364;
    const NO_OTHER_DEFAULT_CARDS_FOR_USER = 365;

    const ERROR_SAVE_TEMPLATE = 367;
    const USER_LIST_IS_NULL = 368;
    const TEMPLATE_ALREADY_EXISTS = 369;
    const CARD_NOT_EXISTS = 370;
    const SENDER_CARD_NOT_EXISTS = 371;
    const RECEIVER_CARD_NOT_EXISTS = 372;
    const TEMPLATE_DATA_IS_NOT_DIFFERENT_FROM_CURRENT = 373;
    const SENDER_CARD_ID_AND_RECEIVER_CARD_ID_HAVE_THE_SAME_VALUE = 374;
    const NO_ANY_SUCCESS_TRANSFER_FROM_CARD_BY_USER = 375;

}
