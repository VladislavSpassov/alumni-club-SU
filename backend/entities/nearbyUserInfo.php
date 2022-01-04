<?php
	class NearbyUser {
		public $email;
		public $firstName;
		public $lastName;
        public $speciality;
		public $graduationYear;
        public $groupUni;
		public $faculty;
        public $longitude;
        public $latitude;
		
		public function __construct($email, $firstName, $lastName, $speciality, $graduationYear, $groupUni, $faculty, $longitude, $latitude) {
			$this->email = $email;
			$this->firstName = $firstName;
			$this->lastName = $lastName;
			$this->speciality = $speciality;
			$this->graduationYear = $graduationYear;
			$this->groupUni = $groupUni;
            $this->faculty = $faculty;
            $this->longitude = $longitude;
            $this->latitude = $latitude;
		}
    }
?>