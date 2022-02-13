<?php
	class Privacy {
        public $speciality;
        public $groupUni;
		public $faculty;
        public $role;
		
		public function __construct($speciality, $groupUni, $faculty, $role) {
			$this->speciality = $speciality;
			$this->groupUni = $groupUni;
            $this->faculty = $faculty;
            $this->role = $role;
		}
    }
?>