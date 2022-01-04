<?php
    require_once(realpath(dirname(__FILE__) . '/../db/repositories/postRepository.php'));

    class PostService {
        private $postRepository;

        public function __construct()
        {
            $this->postRepository = new PostRepository();
        }

        public function getAllPosts()
        {
            return $this->postRepository->selectPostsQuery();
        }

        public function createPost($post)
        {
            if(empty($post->occasion)) {
                throw new Exception("Occasion can't be empty.");
            } else if(empty($post->occasionDate)) {
                throw new Exception("Occasion date can't be empty.");
            } else if(empty($post->location)) {
                throw new Exception("Location can't be empty.");
            } else if(empty($post->content)) {
                throw new Exception("Content can't be empty.");
            }
            $query = $this->postRepository->insertPostQuery([
                "occasion" => $post->occasion,
                "privacy" => $post->privacy,
                "occasionDate" => $post->occasionDate,
                "location" => $post->location,
                "content" => $post->content
            ]);
        }

        public function filterPosts()
        {
            $allUserPosts = $this->postRepository->selectPostUserQuery();
           
            $result = array();
            foreach ($allUserPosts as $post) {
                $coming = $this->postRepository->selectAcceptedQuery($post->postId);
                $post->coming = $coming;
                if($post->userId == $_SESSION['userId']) {
                    continue;
                }
                if($post->privacy == "faculty" && $_SESSION['faculty'] == $post->faculty) {
                    array_push($result, $post);
                }
                else if($post->privacy == "speciality" && $_SESSION['speciality'] == $post->speciality) {
                    array_push($result, $post);
                }
                else if($post->privacy == "group"
                                    && $_SESSION['graduationYear'] == $post->graduationYear
                                    && $_SESSION['faculty'] == $post->faculty 
                                    && $_SESSION['speciality'] == $post->speciality
                                    && $_SESSION['groupUni'] == $post->groupUni){
                    array_push($result, $post);
                }
                else if($post->privacy == "all") {
                    array_push($result, $post); // privacy: all users
                }
            }

            return $result;
        }

        public function getMyPosts()
        {
            $allUserPosts = $this->postRepository->selectPostUserQuery();
           
            $result = array();
            foreach ($allUserPosts as $post) {
                if($post->userId == $_SESSION['userId']) {
                    array_push($result, $post);
                }
            }

            return $result;
        }

        public function answerPost($postId, $isAccepted, $userId) {
            if(empty($postId) || empty($userId) || !isset($isAccepted)) {
                throw new Exception("Empty postId, userId or isAccepted in answerPost.");
            }
            $result = $this->postRepository->getAnswer($postId, $userId)["data"]->fetch(PDO::FETCH_ASSOC);
            
            if(empty($result)) {
                $this->postRepository->insertAnswer($postId, $isAccepted, $userId);
            } else {
                $this->postRepository->updateAnswer($postId, $isAccepted, $userId);
            } 
        }

        public function deletePost($postId) {
            if(empty($postId)) {
                throw new Exception("Empty postId in deletePost.");
            }
            // first delete the post entries in user_post, then the post itself
            $this->postRepository->deleteAnsweredUsersPostQuery($postId);
            $this->postRepository->deletePostQuery($postId);
        }

        public function getIfUserAccepted($postId) {
            if(empty($postId)) {
                throw new Exception("Empty postId in getIfUserAccepted.");
            }
            $result =  $this->postRepository->getAnswer($postId, $_SESSION['userId'])["data"]->fetch(PDO::FETCH_ASSOC);

            if (empty($result)) {
                return "";
            } else {
               return $result;
            } 
        }
    }
?>