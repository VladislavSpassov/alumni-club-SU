<?php
require_once(realpath(dirname(__FILE__) . '/../db_connection.php'));
require_once(realpath(dirname(__FILE__) . '/../../data_models/post.php'));
require_once(realpath(dirname(__FILE__) . '/../../data_models/user_post.php'));

/**
 * All the statements about the posts
 */
class PostModel {
        private $insertPost;
        private $selectPosts;
        private $countPosts;
        private $selectPostUser;
        private $selectAnswer;
        private $insert_answer;
        private $update_answer;
        private $selectAccepted;
        private $delete_post;
        private $deleteAnsweredUsersPost;
        private $selectIfUserAccepted;

        private $database;

        public function __construct()
        {
            $this->database = new Database();
        }

        public function insert_post_query($data)
        {
            $this->database->get_connection()->beginTransaction();   
            try {
                $sql = "INSERT INTO posts(userId, occasion, privacy, occasionDate, location, content) VALUES('{$_SESSION['userId']}', :occasion, :privacy, :occasionDate, :location, :content)";

                $this->insertPost = $this->database->get_connection()->prepare($sql);
                $this->insertPost->execute($data);
                $this->database->get_connection()->commit();   
                return ["success" => true];
            } catch (PDOException $e) {
                echo "exception test";
                $this->database->get_connection()->rollBack();
                return ["success" => false, "error" => "Connection failed: " . $e->getMessage()];
            }
        }

        public function select_posts_query() {
            $this->database->get_connection()->beginTransaction();
            try {
                $sql = "SELECT * FROM posts";
                $this->selectPosts = $this->database->get_connection()->prepare($sql);
                $this->selectPosts->execute();

                $posts = array();
                while ($row = $this->selectPosts->fetch())
                {
                    $post = new Post($row['id'], $row['occasion'], $row['privacy'], $row['occasionDate'], $row['location'], $row['content']);
                    array_push($posts, $post);
                }
                return $posts;
            } catch(PDOException $e) {
                $this->database->get_connection()->rollBack();
                return ["success" => false, "error" => "Connection failed: " . $e->getMessage()];
            }
        }

        public function select_posts_count_query()
        {
            $this->database->get_connection()->beginTransaction();   
            try {
                $sql = "SELECT COUNT(id) FROM posts";
                $this->countPosts = $this->database->get_connection()->prepare($sql);
                $this->countPosts->execute();
                $this->database->get_connection()->commit();   
                return ["success" => true, "data" => $this->countPosts];
            } catch (PDOException $e) {
                echo "exception test";
                $this->database->get_connection()->rollBack();
                return ["success" => false, "error" => "Connection failed: " . $e->getMessage()];
            }
        }

        public function select_post_user_query() {
            $this->database->get_connection()->beginTransaction();
            try {
                $sql = "SELECT occasion, privacy, occasionDate, location, content, speciality, groupUni,
                 faculty, graduationYear, firstName, lastName, userId, posts.id as postId 
                 FROM posts INNER JOIN users ON posts.userId=users.id 
                 WHERE occasionDate >= CURDATE()";
                $this->selectPostUser = $this->database->get_connection()->prepare($sql);
                $this->selectPostUser->execute();

                $postUserArray = array();
                while ($row = $this->selectPostUser->fetch())
                {
                    $userPost = new UserPost( $row['occasion'], $row['privacy'],
                        $row['occasionDate'], $row['location'], $row['content'],
                        $row['speciality'], $row['groupUni'], $row['faculty'], $row['graduationYear'],
                        $row['firstName'], $row['lastName'], $row['userId'], $row['postId'], array()); 
                    array_push($postUserArray, $userPost);
                }
               
                $this->database->get_connection()->commit();
                return $postUserArray;
                
            } catch(PDOException $e) {
                $this->database->get_connection()->rollBack();
                return ["success" => false, "error" => "Connection failed: " . $e->getMessage()];
            }
        }
        
        public function get_answer($postId, $userId) {
            $this->database->get_connection()->beginTransaction();
            try {
                $sql = "SELECT isAccepted FROM user_post WHERE userId = :userId AND postId = :postId";
                $this->selectAnswer = $this->database->get_connection()->prepare($sql);
                $this->selectAnswer->execute(["userId" => $userId, "postId" => $postId]);
                $this->database->get_connection()->commit();   
                return ["success" => true, "data" => $this->selectAnswer];
            } catch(PDOException $e) {
                $this->database->get_connection()->rollBack();
                return ["success" => false, "error" => "Connection failed: " . $e->getMessage()];
            }
        }

        public function insert_answer($postId, $isAccepted, $userId) {
            $this->database->get_connection()->beginTransaction();   
            try {
                $sql = "INSERT INTO user_post(userId, postId, isAccepted) VALUES($userId, $postId, $isAccepted)";
                $this->insert_answer = $this->database->get_connection()->prepare($sql);
                $this->insert_answer->execute();
                $this->database->get_connection()->commit();   
                return ["success" => true];
            } catch (PDOException $e) {
                $this->database->get_connection()->rollBack();
                return ["success" => false, "error" => "Connection failed: " . $e->getMessage()];
            }
        }

        public function update_answer($postId, $isAccepted, $userId) {
            $this->database->get_connection()->beginTransaction();   
            try {
                $sql = "UPDATE user_post SET isAccepted = :isAccepted 
                        WHERE postId = :postId AND userId = :userId";
                $this->update_answer = $this->database->get_connection()->prepare($sql);
                $this->update_answer->execute(["isAccepted" => $isAccepted, "userId" => $userId, "postId" => $postId]);
                $this->database->get_connection()->commit();   
                return ["success" => true];
            } catch (PDOException $e) {
                echo $e->getMessage();
                $this->database->get_connection()->rollBack();
                return ["success" => false, "error" => "Connection failed: " . $e->getMessage()];
            }
        }

        public function select_accepted_query($postId) {
            $this->database->get_connection()->beginTransaction();
            try {
                $sql = "SELECT users.firstName as firstName, users.lastName as lastName 
                        FROM users 
                        JOIN user_post ON users.id = user_post.userId 
                        JOIN posts ON user_post.postId = posts.id 
                        WHERE posts.id = $postId AND user_post.isAccepted = true";
                $this->selectAccepted = $this->database->get_connection()->prepare($sql);
                $this->selectAccepted->execute();

                $acceptedArray = array();
                while ($row = $this->selectAccepted->fetch())
                {
                    $accepted = $row['firstName'] . " " . $row['lastName'];
                    array_push($acceptedArray, $accepted);
                }
                $this->database->get_connection()->commit();
                return $acceptedArray;
                
            } catch(PDOException $e) {
                $this->database->get_connection()->rollBack();
                return ["success" => false, "error" => "Connection failed: " . $e->getMessage()];
            }
        }

        public function delete_post_query($postId)
        {
            $this->database->get_connection()->beginTransaction();
            try {
                $sql = "DELETE FROM posts 
                        WHERE posts.id = $postId";
                $this->delete_post = $this->database->get_connection()->prepare($sql);
                $this->delete_post->execute();

                $this->database->get_connection()->commit();
                return ["success" => true];
            } catch (PDOException $e) {
            echo $e->getMessage();
                $this->database->get_connection()->rollBack();
                return ["success" => false, "error" => "Connection failed: " . $e->getMessage()];
            }
        }

        public function delete_answered_users_post_query($postId)
        {
            $this->database->get_connection()->beginTransaction();
            try {
                $sql = "DELETE FROM user_post
                            WHERE postId = $postId";
                $this->deleteAnsweredUsersPost = $this->database->get_connection()->prepare($sql);
                $this->deleteAnsweredUsersPost->execute();

                $this->database->get_connection()->commit();
                return ["success" => true];
            } catch (PDOException $e) {
                echo $e->getMessage();
                $this->database->get_connection()->rollBack();
                return ["success" => false, "error" => "Connection failed: " . $e->getMessage()];
            }
        }
    }
?>