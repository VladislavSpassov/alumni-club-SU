<?php
    require_once(realpath(dirname(__FILE__) . '/../db/repositories/userRepository.php'));
    require_once(realpath(dirname(__FILE__) . '/../db/repositories/postRepository.php'));

    class StatisticsService {
        private $userRepository;
        private $postRepository;

        public function __construct()
        {
            $this->userRepository = new UserRepository();
            $this->postRepository = new PostRepository();
        }

        public function groupUsersByFaculty(){
            $faculty = 'faculty';
            return $this->userRepository->groupUsersQuery($faculty);
        }

        public function groupUsersBySpeciality(){
            $speciality = "speciality";
            return $this->userRepository->groupUsersQuery($speciality);
        }

        public function groupUsersByGraduationYear(){
            $graduationYear = "graduationYear";
            return $this->userRepository->groupUsersQuery($graduationYear);
        }

        public function getUsersCount(){
            return $this->userRepository->selectUsersCountQuery()["data"]->fetch(PDO::FETCH_ASSOC)["COUNT(id)"];
        }

        public function getPostsCount(){
            return $this->postRepository->selectPostsCountQuery()["data"]->fetch(PDO::FETCH_ASSOC)["COUNT(id)"];
        }
    }
?>