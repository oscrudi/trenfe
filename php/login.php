<?php

    class User {
        // Opciones de contraseña
        const HASH = PASSWORD_DEFAULT;
        const COST = 10;
        // Almacenamiento de datos del usuario
        public $data;

        public function __construct($user, $pass) {
            $usuario = checkUser($user);
            if( $usuario != false ){
                //TODO: check pass
            }
        }

        public function guardarEnBBDD() {
            //TODO: Guardar los datos de $data en la base de datos
        }

        public function updatePassword($password) {
            $this->data->passwordHash = password_hash($password, self::HASH, ['cost' => self::COST]);
        }
        // Logear un usuario:
        public function login($password) {
            // Primero comprobamos si se ha empleado una contraseña correcta:
            echo "Login: ", $this->data->passwordHash, "\n";
            if (password_verify($password, $this->data->passwordHash)) {
                // Exito, ahora se comprueba si la contraseña necesita un rehash:
                if (password_needs_rehash($this->data->passwordHash, self::HASH, ['cost' => self::COST])) {
                    // Tenemos que hacer rehash en la contraseña y guardarla.  Simplemente se llama a setPassword():
                    $this->setPassword($password);
                    $this->save();
                }
                return true; // O hacer lo necesario para indicar que el usuario se ha logeado.
            }
            return false;
        }

        public function checkUser($user){
            $usuario = trim($user);
            $result = false;
            if( checkDNI($usuario) ){
                $result = getUsuarioPorDNI($usuario);
            } elseif( checkEmail($usuario) ){
                $result = getUsuarioPorEmail($usuario);
            }
            if( $result != false ){
                return $result->fetch_assoc();
            }
            return false;
        }

        public function checkDNI($dni){
            if( !preg_match("^[0-9]{8}[a-zA-Z]$^", $dni) ){
                return false;
            }
            return true;
        }

        public function checkEmail($email){
            if( !preg_match("^[a-zA-Z0-9]+@[a-zA-Z0-9]+\.[a-zA-Z]{2,}$^", $email) ){
                return false;
            }
            return true;
        }

    }

?>