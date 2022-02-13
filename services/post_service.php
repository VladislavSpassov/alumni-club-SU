<?php
    require_once(realpath(dirname(__FILE__) . '/../db/models/post_model.php'));

    class PostService {
        private $postModel;

        public function __construct()
        {
            $this->postModel = new PostModel();
        }

        public function get_all_posts()
        {
            return $this->postModel->select_posts_query();
        }

        public function create_post($post)
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
            $query = $this->postModel->insert_post_query([
                "occasion" => $post->occasion,
                "privacy" => $post->privacy,
                "occasionDate" => $post->occasionDate,
                "location" => $post->location,
                "content" => $post->content
            ]);
        }

        public function filter_posts()
        {
            $allUserPosts = $this->postModel->select_post_user_query();
           
            $result = array();
            foreach ($allUserPosts as $post) {
                $coming = $this->postModel->select_accepted_query($post->postId);
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

        public function get_my_posts()
        {
            $allUserPosts = $this->postModel->select_post_user_query();
           
            $result = array();
            foreach ($allUserPosts as $post) {
                if($post->userId == $_SESSION['userId']) {
                    array_push($result, $post);
                }
            }

            return $result;
        }

        public function answer_post($postId, $isAccepted, $userId) {
            if(empty($postId) || empty($userId) || !isset($isAccepted)) {
                throw new Exception("Empty postId, userId or isAccepted in answer_post.");
            }
            $result = $this->postModel->get_answer($postId, $userId)["data"]->fetch(PDO::FETCH_ASSOC);
            
            if(empty($result)) {
                $this->postModel->insert_answer($postId, $isAccepted, $userId);
            } else {
                $this->postModel->update_answer($postId, $isAccepted, $userId);
            } 
        }

        public function delete_post($postId) {
            if(empty($postId)) {
                throw new Exception("Empty postId in delete_post.");
            }
            // first delete the post entries in user_post, then the post itself
            $this->postModel->delete_answered_users_post_query($postId);
            $this->postModel->delete_post_query($postId);
        }

        public function get_if_user_accepted($postId) {
            if(empty($postId)) {
                throw new Exception("Empty postId in get_if_user_accepted.");
            }
            $result =  $this->postModel->get_answer($postId, $_SESSION['userId'])["data"]->fetch(PDO::FETCH_ASSOC);

            if (empty($result)) {
                return "";
            } else {
               return $result;
            } 
        }
    }
?>