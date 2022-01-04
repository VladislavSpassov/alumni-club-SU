<?php
require_once(realpath(dirname(__FILE__) . '/../dbConnection.php'));
require_once(realpath(dirname(__FILE__) . '/../../entities/post.php'));
require_once(realpath(dirname(__FILE__) . '/../../entities/userPost.php'));

/**
 * All the statements about the posts
 */
class PostRepository {
        private $insertPost;
        private $selectPosts;
        private $countPosts;
        private $selectPostUser;
        private $selectAnswer;
        private $insertAnswer;
        private $updateAnswer;
        private $selectAccepted;
        private $deletePost;
        private $deleteAnsweredUsersPost;
        private $selectIfUserAccepted;

        private $database;

        public function __construct()
        {
            $this->database = new Database();
        }

        public function insertPostQuery($data)
        {
            $this->database->getConnection()->beginTransaction();   
            try {
                $sql = "INSERT INTO posts(userId, occasion, privacy, occasionDate, location, content) VALUES('{$_SESSION['userId']}', :occasion, :privacy, :occasionDate, :location, :content)";
                $this->insertPost = $this->database->getConnection()->prepare($sql);
                $this->insertPost->execute($data);
                $this->database->getConnection()->commit();   
                return ["success" => true];
            } catch (PDOException $e) {
                echo "exception test";
                $this->database->getConnection()->rollBack();
                return ["success" => false, "error" => "Connection failed: " . $e->getMessage()];
            }
        }

        public function selectPostsQuery() {
            $this->database->getConnection()->beginTransaction();
            try {
                $sql = "SELECT * FROM posts";
                $this->selectPosts = $this->database->getConnection()->prepare($sql);
                $this->selectPosts->execute();

                $posts = array();
                while ($row = $this->selectPosts->fetch())
                {
                    $post = new Post($row['id'], $row['occasion'], $row['privacy'], $row['occasionDate'], $row['location'], $row['content']);
                    array_push($posts, $post);
                }
                return $posts;
            } catch(PDOException $e) {
                $this->database->getConnection()->rollBack();
                return ["success" => false, "error" => "Connection failed: " . $e->getMessage()];
            }
        }

        public function selectPostsCountQuery()
        {
            $this->database->getConnection()->beginTransaction();   
            try {
                $sql = "SELECT COUNT(id) FROM posts";
                $this->countPosts = $this->database->getConnection()->prepare($sql);
                $this->countPosts->execute();
                $this->database->getConnection()->commit();   
                return ["success" => true, "data" => $this->countPosts];
            } catch (PDOException $e) {
                echo "exception test";
                $this->database->getConnection()->rollBack();
                return ["success" => false, "error" => "Connection failed: " . $e->getMessage()];
            }
        }

        public function selectPostUserQuery() {
            $this->database->getConnection()->beginTransaction();
            try {
                $sql = "SELECT occasion, privacy, occasionDate, location, content, speciality, groupUni,
                 faculty, graduationYear, firstName, lastName, userId, posts.id as postId 
                 FROM posts INNER JOIN users ON posts.userId=users.id 
                 WHERE occasionDate >= CURDATE()";
                $this->selectPostUser = $this->database->getConnection()->prepare($sql);
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
               
                $this->database->getConnection()->commit();
                return $postUserArray;
                
            } catch(PDOException $e) {
                $this->database->getConnection()->rollBack();
                return ["success" => false, "error" => "Connection failed: " . $e->getMessage()];
            }
        }
        
        public function getAnswer($postId, $userId) {
            $this->database->getConnection()->beginTransaction();
            try {
                $sql = "SELECT isAccepted FROM user_post WHERE userId = :userId AND postId = :postId";
                $this->selectAnswer = $this->database->getConnection()->prepare($sql);
                $this->selectAnswer->execute(["userId" => $userId, "postId" => $postId]);
                $this->database->getConnection()->commit();   
                return ["success" => true, "data" => $this->selectAnswer];
            } catch(PDOException $e) {
                $this->database->getConnection()->rollBack();
                return ["success" => false, "error" => "Connection failed: " . $e->getMessage()];
            }
        }

        public function insertAnswer($postId, $isAccepted, $userId) {
            $this->database->getConnection()->beginTransaction();   
            try {
                $sql = "INSERT INTO user_post(userId, postId, isAccepted) VALUES($userId, $postId, $isAccepted)";
                $this->insertAnswer = $this->database->getConnection()->prepare($sql);
                $this->insertAnswer->execute();
                $this->database->getConnection()->commit();   
                return ["success" => true];
            } catch (PDOException $e) {
                $this->database->getConnection()->rollBack();
                return ["success" => false, "error" => "Connection failed: " . $e->getMessage()];
            }
        }

        public function updateAnswer($postId, $isAccepted, $userId) {
            $this->database->getConnection()->beginTransaction();   
            try {
                $sql = "UPDATE user_post SET isAccepted = :isAccepted 
                        WHERE postId = :postId AND userId = :userId";
                $this->updateAnswer = $this->database->getConnection()->prepare($sql);
                $this->updateAnswer->execute(["isAccepted" => $isAccepted, "userId" => $userId, "postId" => $postId]);
                $this->database->getConnection()->commit();   
                return ["success" => true];
            } catch (PDOException $e) {
                echo $e->getMessage();
                $this->database->getConnection()->rollBack();
                return ["success" => false, "error" => "Connection failed: " . $e->getMessage()];
            }
        }

        public function selectAcceptedQuery($postId) {
            $this->database->getConnection()->beginTransaction();
            try {
                $sql = "SELECT users.firstName as firstName, users.lastName as lastName 
                        FROM users 
                        JOIN user_post ON users.id = user_post.userId 
                        JOIN posts ON user_post.postId = posts.id 
                        WHERE posts.id = $postId AND user_post.isAccepted = true";
                $this->selectAccepted = $this->database->getConnection()->prepare($sql);
                $this->selectAccepted->execute();

                $acceptedArray = array();
                while ($row = $this->selectAccepted->fetch())
                {
                    $accepted = $row['firstName'] . " " . $row['lastName'];
                    array_push($acceptedArray, $accepted);
                }
                $this->database->getConnection()->commit();
                return $acceptedArray;
                
            } catch(PDOException $e) {
                $this->database->getConnection()->rollBack();
                return ["success" => false, "error" => "Connection failed: " . $e->getMessage()];
            }
        }

        public function deletePostQuery($postId)
        {
            $this->database->getConnection()->beginTransaction();
            try {
                $sql = "DELETE FROM posts 
                        WHERE posts.id = $postId";
                $this->deletePost = $this->database->getConnection()->prepare($sql);
                $this->deletePost->execute();

                $this->database->getConnection()->commit();
                return ["success" => true];
            } catch (PDOException $e) {
            echo $e->getMessage();
                $this->database->getConnection()->rollBack();
                return ["success" => false, "error" => "Connection failed: " . $e->getMessage()];
            }
        }

        public function deleteAnsweredUsersPostQuery($postId)
        {
            $this->database->getConnection()->beginTransaction();
            try {
                $sql = "DELETE FROM user_post
                            WHERE postId = $postId";
                $this->deleteAnsweredUsersPost = $this->database->getConnection()->prepare($sql);
                $this->deleteAnsweredUsersPost->execute();

                $this->database->getConnection()->commit();
                return ["success" => true];
            } catch (PDOException $e) {
                echo $e->getMessage();
                $this->database->getConnection()->rollBack();
                return ["success" => false, "error" => "Connection failed: " . $e->getMessage()];
            }
        }
    }
?>