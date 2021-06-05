
<?php 

	class Invites extends Model {

		public function __construct($pdo){
			parent::__construct($pdo);
			$this->load('invites','id','nom','');
		}

		// NODE
		public static $INVITES_NODE_ID = "id";
		public static $INVITES_NODE_NOM = "nom";
		public static $INVITES_NODE_PRENOMS = "prenoms";
		public static $INVITES_NODE_CONTACT = "contact";
		public static $INVITES_NODE_EMAIL = "email";
		public static $INVITES_NODE_DATE = "date";
		public static $INVITES_NODE_NOMBREPERSO = "nombreperso";
		public static $INVITES_NODE_PACKAGE = "package";
		public static $INVITES_NODE_DATE_CREATION = "date_creation";

	}

?>