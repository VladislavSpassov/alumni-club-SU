<?php
    require_once(realpath(dirname(__FILE__) . '/../db/models/user_model.php'));
    require_once(realpath(dirname(__FILE__) . '/../db/models/post_model.php'));

    class StatisticsService {
        private $userRepository;
        private $postRepository;

        public function __construct()
        {
            $this->userRepository = new UserModel();
            $this->postRepository = new PostModel();
        }

        public function groupUsersByFaculty(){
            $faculty = 'faculty';
            return $this->userRepository->group_users_query($faculty);
        }

        public function groupUsersBySpeciality(){
            $speciality = "speciality";
            return $this->userRepository->group_users_query($speciality);
        }

        public function groupUsersByGraduationYear(){
            $graduationYear = "graduationYear";
            return $this->userRepository->group_users_query($graduationYear);
        }

        public function getUsersCount(){
            return $this->userRepository->select_users_count_query()["data"]->fetch(PDO::FETCH_ASSOC)["COUNT(id)"];
        }

        public function getPostsCount(){
            return $this->postRepository->select_posts_count_query()["data"]->fetch(PDO::FETCH_ASSOC)["COUNT(id)"];
        }
    }
?>