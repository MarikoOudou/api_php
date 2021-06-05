
<?php 

	class Member extends Model {

		public function __construct($pdo){
			parent::__construct($pdo);
			$this->load('member','id','name','');
		}

		// NODE
		public static $MEMBER_NODE_ID = "id";
		public static $MEMBER_NODE_NAME = "name";
		public static $MEMBER_NODE_FORENAME = "forename";
		public static $MEMBER_NODE_MAIL = "mail";
		public static $MEMBER_NODE_PHONE = "phone";
		public static $MEMBER_NODE_COMPANY = "company";
		public static $MEMBER_NODE_COUNTRY = "country";
		public static $MEMBER_NODE_ACCEPTED = "accepted";
		public static $MEMBER_NODE_SCORE = "score";
		public static $MEMBER_NODE_DATE_CREATION = "date_creation";

	}

?>