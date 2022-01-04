<?php
    require_once(realpath(dirname(__FILE__) . '/../db/repositories/userRepository.php'));

    class UserService {
        private $userRepository;
        private $radius;

        public function __construct()
        {
            $this->userRepository = new UserRepository();
            $config = parse_ini_file('../../config/config.ini', true);

            $this->radius = $config['radius']['km'];
        }

        public function getAllUsers()
        {
            return $this->userRepository->selectUsersQuery();
        }

        public function updateUser($user)
        {
            $this->validate($user->password, $user->email, $user->firstName, $user->lastName);
            $query = $this->userRepository->updateUserQuery([
                "password" => $user->password,
                "firstName" => $user->firstName,
                "lastName" => $user->lastName,
                "email" => $user->email
            ]);
        }

        private function required($field1, $field2, $field3, $field4): bool {
			return !empty($field1) && !empty($field2) && !empty($field3) && !empty($field4);
		}
		
		private function validName($field): bool{
			return strlen($field) >= 2 && strlen($field) <= 45 && 
            (preg_match('/^[\p{Cyrillic}]+[- \']?[\p{Cyrillic}]+$/u', $field) || preg_match('/^[a-zA-Z]+[- \']?[a-zA-Z]+$/', $field));	
		}
		
		private function validEmail($field): bool {
			return preg_match('/^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/', $field);
		}

		private function validPassword($field): bool {
			return preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])[a-zA-Z0-9]{6,30}$/', $field);
		}
		
		private function validate($password, $email, $firstName, $lastName): void {
			
			if (!$this->required($password, $email, $firstName, $lastName)) {
				throw new Exception("Please enter all fields.");
			}
			
			if (!$this->validName($firstName)) {
				throw new Exception("Please enter valid first name.");
			}
			
			if (!$this->validName($lastName)) {
				throw new Exception("Please enter valid last name.");
			}
			
			if (!$this->validEmail($email)) {
				throw new Exception("Please enter valid email.");
			}
			
			if (!$this->validPassword($password)) {
				throw new Exception("Please enter valid password.");
			}
		}

        public function getUser()
        {
            $result = $this->userRepository->selectUserByIdQuery($_SESSION['userId']);
            return $result["data"]->fetch(PDO::FETCH_ASSOC);
        }


        public function checkLogin($username, $password) {
            $result = $this->userRepository->selectUserByUsernameQuery($username);
            $resultData = $result["data"]->fetch(PDO::FETCH_ASSOC);
            if (!$result["success"] || empty($resultData)) {
                throw new Exception("Грешно потребителско име.");
            } else if (!password_verify($password, $resultData["password"])) { 
                throw new Exception("Грешна парола.");
            }
            session_start();
            return $resultData["id"];
        }

        public function updateCoordinates($longitude, $latitude) {
            if(empty($longitude) || empty($latitude)) {
                throw new Exception("Empty latitude or longitude.");
            }
            $this->userRepository->updateCoordinatesQuery([
                "longitude" => $longitude,
                "latitude" => $latitude
            ]);
        }

        private function getDistance($latitude1, $longitude1, $latitude2, $longitude2) {  
            $earth_radius = 6371;
          
            $dLat = deg2rad($latitude2 - $latitude1);  
            $dLon = deg2rad($longitude2 - $longitude1);  
          
            $a = sin($dLat/2) * sin($dLat/2) + cos(deg2rad($latitude1)) * cos(deg2rad($latitude2)) * sin($dLon/2) * sin($dLon/2);  
            $c = 2 * asin(sqrt($a));  
            $d = $earth_radius * $c;  
          
            return $d;  
          }

          public function getAllNearbyUsers(){
            $allUsers = $this->userRepository->selectNearbyUsersInfoQuery();
            $currentUser = $this->getUser();
            $result = array();

            foreach ($allUsers as $user) {
                if($user->latitude == null && $user->longitude == null) {
                    continue;
                }
                $distance = $this->getDistance($currentUser["latitude"], $currentUser["longitude"], $user->latitude, $user->longitude);
                if($distance < $this->radius){
                    array_push($result, $user);
                }
            }
            
            return $result;
        }

        public function getUserRoleData(){
            return $this->userRepository->selectUserRoleDataQuery();
        }
    }
?>