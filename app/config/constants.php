<?php

    class Constant {
        public static $FORGOTTEN_PASSWORD_CODE = "Réinitialiser mot de passe";
        public static $UNKNOW_EMAIL = "Adresse email introuvable";
        public static $INVALID_CODE = "Code de validation erroné.";

        /**
         * NODE
         */
        public static $NODE_RESPONSE = "response";
        public static $NODE_STATUS = "status";
        public static $NODE_STATUS_CODE = "code";
        public static $NODE_STATUS_MESSAGE = "message";

        public static $NODE_HAS_ERROR = "has_error";

        public static $NODE_DATA  = "data";
        public static $NODE_DATAS = "datas";
        public static $NODE_ITEM  = "item";
        public static $NODE_ITEMS = "items";

        /**
         * @var STATUS CODE
         */

        // REQUEST
        public static $CODE_GOOD = 200;
        public static $CODE_BAD = 400;

        // ARGS
        public static $ARGS_MISSING = 500;
        public static $ARGS_INVALID = 501;

        /**
         * @var RESULT
         */
        public static $RESULT_GOOD_REQUEST = false;
        public static $RESULT_BAD_REQUEST = true;
        public static $RESULT_GOOD_MESSAGE = "Opération effectuée avec succès.";
        public static $RESULT_BAD_MESSAGE = "Service inexistant.";
        public static $RESULT_INVALID_MESSAGE = "La requête fournie n'est pas celle attendue.";
        public static $RESULT_ACTION_MISSING = "Action non définie.";
        public static $RESULT_SYCA_SUCCESS = "Paiement effectué avec succès.";
        public static $RESULT_SYCA_CANCEL = "Paiement annulé.";

        /**
         * Controller action
         */
        public static $ACTION_GET                              = "getByCriteria";
        public static $ACTION_CREATE                           = "create";
        public static $ACTION_UPDATE                           = "update";
        public static $ACTION_DELETE                           = "delete";
        public static $ACTION_CONNEXION                        = "connexion";
        public static $ACTION_RESET_PASSWORD                   = "resetPassword";
        public static $ACTION_GET_FORGOTTEN_PASSWORD_CODE      = "getForgottenPasswordCode";
        public static $ACTION_VALIDATE_FORGOTTEN_PASSWORD_CODE = "validateForgottenPasswordCode";
        public static $ACTION_CHECK_CODE                       = "checkCode";
        public static $ACTION_GENERATE_CODE                    = "generateCode";
        public static $ACTION_PRINT_QR_CODE                    = "print_qr_code";
        public static $ACTION_MISSING                          = "missing";

    }
