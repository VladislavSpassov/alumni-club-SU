<?php
require_once(realpath(dirname(__FILE__) . '/../db_connection.php'));
require_once(realpath(dirname(__FILE__) . '/../../data_models/user.php'));
require_once(realpath(dirname(__FILE__) . '/../../data_models/nearby_user_info.php'));

/**
 * All the statements about the users
 */
class UserModel {
        private $selectUserById;
        private $update_user;
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

        public function update_user_query($data)
        {
            $this->database->get_connection()->beginTransaction();   
            try {
                $sql = "UPDATE users SET password = :password, firstName = :firstName, 
                        lastName = :lastName, email = :email WHERE id = '{$_SESSION['userId']}'";
                $this->update_user = $this->database->get_connection()->prepare($sql);
                $this->update_user->execute($data);
                $this->database->get_connection()->commit();   
                return ["success" => true];
            } catch (PDOException $e) {
                echo "exception test";
                $this->database->get_connection()->rollBack();
                return ["success" => false, "error" => "Connection failed: " . $e->getMessage()];
            }
        }

        public function select_users_query() {
            $this->database->get_connection()->beginTransaction();
            try {
                $sql = "SELECT * FROM users";
                $this->selectUsers = $this->database->get_connection()->prepare($sql);
                $this->selectUsers->execute();

                $users = array();
                while ($row = $this->selectUsers->fetch())
                {
                    $user = new User($row['id'], $row['username'], $row['password'], $row['firstName'], $row['lastName'], $row['email'], $row['role']);
                    array_push($users, $user);
                }
                $this->database->get_connection()->commit();
                return $users;
            } catch(PDOException $e) {
                $this->database->get_connection()->rollBack();
                return ["success" => false, "error" => "Connection failed: " . $e->getMessage()];
            }
        }

        public function select_nearby_users_info_query() {
            $this->database->get_connection()->beginTransaction();
            try {
                $sql = "SELECT * FROM users WHERE id <> '{$_SESSION['userId']}'";
                $this->selectUsers = $this->database->get_connection()->prepare($sql);
                $this->selectUsers->execute();

                $users = array();
                while ($row = $this->selectUsers->fetch())
                {
                    $user = new NearbyUser($row['email'], $row['firstName'], $row['lastName'], $row['speciality'], $row['graduationYear'], $row['groupUni'], $row['faculty'], $row['longitude'], $row['latitude']);
                    array_push($users, $user);
                }
                $this->database->get_connection()->commit();
                return $users;
            } catch(PDOException $e) {
                $this->database->get_connection()->rollBack();
                return ["success" => false, "error" => "Connection failed: " . $e->getMessage()];
            }
        }

        public function select_user_by_id_query($data) {
            $this->database->get_connection()->beginTransaction();
            try{
                $sql = "SELECT * FROM users WHERE id=:id";
                $this->selectUserById = $this->database->get_connection()->prepare($sql);
                $this->selectUserById->execute(["id" => $data]);
                $this->database->get_connection()->commit();
                return array("success" => true, "data" => $this->selectUserById);
            } catch(PDOException $e){
                $this->database->get_connection()->rollBack();
                return ["success" => false, "error" => "Connection failed: " . $e->getMessage()];
            }
        }

        public function select_user_by_username_query($username) {
            $this->database->get_connection()->beginTransaction();
            try {
                $sql = "SELECT * FROM users WHERE username=:username";
                $this->selectUser = $this->database->get_connection()->prepare($sql);
                $this->selectUser->execute(["username" => $username]);
                $this->database->get_connection()->commit();

                return array("success" => true, "data" => $this->selectUser);
            } catch(PDOException $e) {
                $this->database->get_connection()->rollBack();
                return ["success" => false, "error" => "Connection failed: " . $e->getMessage()];
            }
        }

        public function update_coordinates_query($data)
        {
            $this->database->get_connection()->beginTransaction();   
            try {
                $sql = "UPDATE users SET longitude = :longitude, latitude =:latitude WHERE id = '{$_SESSION['userId']}'";
                $this->update_user = $this->database->get_connection()->prepare($sql);
                $this->update_user->execute($data);
                $this->database->get_connection()->commit();   
                return ["success" => true];
            } catch (PDOException $e) {
                echo "exception test";
                $this->database->get_connection()->rollBack();
                return ["success" => false, "error" => "Connection failed: " . $e->getMessage()];
            }
        }

        public function group_users_query($column)
        {
            $this->database->get_connection()->beginTransaction();   
            try {
                $sql = "SELECT COUNT(id), ${column} FROM users GROUP BY ${column}";
                $this->groupUsers = $this->database->get_connection()->prepare($sql);
                $this->groupUsers->execute();   
                
                $columns = array();
                $counts = array();
                while ($row = $this->groupUsers->fetch())
                {
                    array_push($columns, $row["${column}"]);
                    array_push($counts, $row['COUNT(id)']);
                }
                $this->database->get_connection()->commit();
                return [$columns, $counts];
            } catch (PDOException $e) {
                echo "exception test";
                $this->database->get_connection()->rollBack();
                return ["success" => false, "error" => "Connection failed: " . $e->getMessage()];
            }
        }

        public function select_users_count_query()
        {
            $this->database->get_connection()->beginTransaction();   
            try {
                $sql = "SELECT COUNT(id) FROM users";
                $this->countUsers = $this->database->get_connection()->prepare($sql);
                $this->countUsers->execute();
                $this->database->get_connection()->commit();   
                return ["success" => true, "data" => $this->countUsers];
            } catch (PDOException $e) {
                echo "exception test";
                $this->database->get_connection()->rollBack();
                return ["success" => false, "error" => "Connection failed: " . $e->getMessage()];
            }
        }

        public function select_user_role_data_query() {
            $this->database->get_connection()->beginTransaction();
            try{
                $sql = "SELECT speciality, groupUni, faculty, role, graduationYear FROM users WHERE id = '{$_SESSION['userId']}'";
                $this->userRole = $this->database->get_connection()->prepare($sql);
                $this->userRole->execute();
                $this->database->get_connection()->commit();
                return array("success" => true, "data" => $this->userRole);
            } catch(PDOException $e){
                $this->database->get_connection()->rollBack();
                return ["success" => false, "error" => "Connection failed: " . $e->getMessage()];
            }
        }
    }
?>