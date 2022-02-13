<?php
    class Post {
        public $id;
        public $occasion;
        public $privacy;
        public $occasionDate;
        public $location;
        public $content;

        public function __construct($id, $occasion, $privacy, $occasionDate, $location, $content) {
            $this->id = $id;
            $this->occasion = $occasion;
            $this->privacy = $privacy;
            $this->occasionDate = $occasionDate;
            $this->location = $location;
            $this->content = $content;
        }
    }
?>