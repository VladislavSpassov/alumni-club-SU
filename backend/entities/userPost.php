<?php
    class UserPost {
        public $occasion;
        public $privacy;
        public $occasionDate;
        public $location;
        public $content;
        public $speciality;
        public $groupUni;
		public $faculty;
        public $graduationYear;
        public $firstName;
        public $lastName;
        public $userId;
        public $postId;
        public $coming;

        public function __construct($occasion, $privacy, $occasionDate, $location,
         $content, $speciality, $groupUni, $faculty, $graduationYear, $firstName, 
         $lastName, $userId, $postId, $coming) {
            $this->occasion = $occasion;
            $this->privacy = $privacy;
            $this->occasionDate = $occasionDate;
            $this->location = $location;
            $this->content = $content;
            $this->speciality = $speciality;
			$this->groupUni = $groupUni;
            $this->faculty = $faculty;
            $this->graduationYear = $graduationYear;
            $this->firstName = $firstName;
            $this->lastName = $lastName;
            $this->userId = $userId;
            $this->postId = $postId;
            $this->coming = $coming;
        }
    }
?>