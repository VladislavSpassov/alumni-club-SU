<?php
require_once(realpath(dirname(__FILE__) . '/../dbConnection.php'));
require_once(realpath(dirname(__FILE__) . '/../../entities/user.php'));
require_once(realpath(dirname(__FILE__) . '/../../entities/nearbyUserInfo.php'));

/**
 * All the statements about the users
 */
class UserRepository {
        private $selectUserById;
        private $updateUser;
        private $selectUsers;
        private $selectUser;
        private $groupUsersBy;
        private $countUsers;
        private $userRole;

        private $database;

        public function __construct()
        {
            $this->database = new Database();
        }

        public function updateUserQuery($data)
        {
            $this->database->getConnection()->beginTransaction();   
            try {
                $sql = "UPDATE users SET password = :password, firstName = :firstName, 
                        lastName = :lastName, email = :email WHERE id = '{$_SESSION['userId']}'";
                $this->updateUser = $this->database->getConnection()->prepare($sql);
                $this->updateUser->execute($data);
                $this->database->getConnection()->commit();   
                return ["success" => true];
            } catch (PDOException $e) {
                echo "exception test";
                $this->database->getConnection()->rollBack();
                return ["success" => false, "error" => "Connection failed: " . $e->getMessage()];
            }
        }

        // public function updateUserQuery($data)
        // {
        //     $this->database->getConnection()->beginTransaction();   
        //     try {
        //         $sql = "UPDATE users SET username = :username, password = :password, firstName = :firsName, 
        //                 lastName = :lastName, email = :email, role = :role, speciality = :speciality, graduationYear = :graduationYear,
        //                 groupUni = :groupUni, faculty = :faculty) WHERE id = '{$_SESSION['userId']}'";
        //         $this->updateUser = $this->database->getConnection()->prepare($sql);
        //         $this->updateUser->execute($data);
        //         $this->database->getConnection()->commit();   
        //         return ["success" => true];
        //     } catch (PDOException $e) {
        //         $this->database->getConnection()->rollBack();
        //         return ["success" => false, "error" => "Connection failed: " . $e->getMessage()];
        //     }
        // }

        public function selectUsersQuery() {
            $this->database->getConnection()->beginTransaction();
            try {
                $sql = "SELECT * FROM users";
                $this->selectUsers = $this->database->getConnection()->prepare($sql);
                $this->selectUsers->execute();

                $users = array();
                while ($row = $this->selectUsers->fetch())
                {
                    $user = new User($row['id'], $row['username'], $row['password'], $row['firstName'], $row['lastName'], $row['email'], $row['role']);
                    array_push($users, $user);
                }
                $this->database->getConnection()->commit();
                return $users;
            } catch(PDOException $e) {
                $this->database->getConnection()->rollBack();
                return ["success" => false, "error" => "Connection failed: " . $e->getMessage()];
            }
        }

        public function selectNearbyUsersInfoQuery() {
            $this->database->getConnection()->beginTransaction();
            try {
                $sql = "SELECT * FROM users WHERE id <> '{$_SESSION['userId']}'";
                $this->selectUsers = $this->database->getConnection()->prepare($sql);
                $this->selectUsers->execute();

                $users = array();
                while ($row = $this->selectUsers->fetch())
                {
                    $user = new NearbyUser($row['email'], $row['firstName'], $row['lastName'], $row['speciality'], $row['graduationYear'], $row['groupUni'], $row['faculty'], $row['longitude'], $row['latitude']);
                    array_push($users, $user);
                }
                $this->database->getConnection()->commit();
                return $users;
            } catch(PDOException $e) {
                $this->database->getConnection()->rollBack();
                return ["success" => false, "error" => "Connection failed: " . $e->getMessage()];
            }
        }

        public function selectUserByIdQuery($data) {
            $this->database->getConnection()->beginTransaction();
            try{
                $sql = "SELECT * FROM users WHERE id=:id";
                $this->selectUserById = $this->database->getConnection()->prepare($sql);
                $this->selectUserById->execute(["id" => $data]);
                $this->database->getConnection()->commit();
                return array("success" => true, "data" => $this->selectUserById);
            } catch(PDOException $e){
                $this->database->getConnection()->rollBack();
                return ["success" => false, "error" => "Connection failed: " . $e->getMessage()];
            }
        }

        public function selectUserByUsernameQuery($username) {
            $this->database->getConnection()->beginTransaction();
            try {
                $sql = "SELECT * FROM users WHERE username=:username";
                $this->selectUser = $this->database->getConnection()->prepare($sql);
                $this->selectUser->execute(["username" => $username]);
                $this->database->getConnection()->commit();
                // $user = $query["data"]->fetch(PDO::FETCH_ASSOC);
                return array("success" => true, "data" => $this->selectUser);
            } catch(PDOException $e) {
                $this->database->getConnection()->rollBack();
                return ["success" => false, "error" => "Connection failed: " . $e->getMessage()];
            }
        }

        public function updateCoordinatesQuery($data)
        {
            $this->database->getConnection()->beginTransaction();   
            try {
                $sql = "UPDATE users SET longitude = :longitude, latitude =:latitude WHERE id = '{$_SESSION['userId']}'";
                $this->updateUser = $this->database->getConnection()->prepare($sql);
                $this->updateUser->execute($data);
                $this->database->getConnection()->commit();   
                return ["success" => true];
            } catch (PDOException $e) {
                echo "exception test";
                $this->database->getConnection()->rollBack();
                return ["success" => false, "error" => "Connection failed: " . $e->getMessage()];
            }
        }

        public function groupUsersQuery($column)
        {
            $this->database->getConnection()->beginTransaction();   
            try {
                $sql = "SELECT COUNT(id), ${column} FROM users GROUP BY ${column}";
                $this->groupUsers = $this->database->getConnection()->prepare($sql);
                $this->groupUsers->execute();   
                
                $columns = array();
                $counts = array();
                while ($row = $this->groupUsers->fetch())
                {
                    array_push($columns, $row["${column}"]);
                    array_push($counts, $row['COUNT(id)']);
                }
                $this->database->getConnection()->commit();
                return [$columns, $counts];
            } catch (PDOException $e) {
                echo "exception test";
                $this->database->getConnection()->rollBack();
                return ["success" => false, "error" => "Connection failed: " . $e->getMessage()];
            }
        }

        public function selectUsersCountQuery()
        {
            $this->database->getConnection()->beginTransaction();   
            try {
                $sql = "SELECT COUNT(id) FROM users";
                $this->countUsers = $this->database->getConnection()->prepare($sql);
                $this->countUsers->execute();
                $this->database->getConnection()->commit();   
                return ["success" => true, "data" => $this->countUsers];
            } catch (PDOException $e) {
                echo "exception test";
                $this->database->getConnection()->rollBack();
                return ["success" => false, "error" => "Connection failed: " . $e->getMessage()];
            }
        }

        public function selectUserRoleDataQuery() {
            $this->database->getConnection()->beginTransaction();
            try{
                $sql = "SELECT speciality, groupUni, faculty, role, graduationYear FROM users WHERE id = '{$_SESSION['userId']}'";
                $this->userRole = $this->database->getConnection()->prepare($sql);
                $this->userRole->execute();
                $this->database->getConnection()->commit();
                return array("success" => true, "data" => $this->userRole);
            } catch(PDOException $e){
                $this->database->getConnection()->rollBack();
                return ["success" => false, "error" => "Connection failed: " . $e->getMessage()];
            }
        }
    }
?>