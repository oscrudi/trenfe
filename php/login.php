<?php

    class User {
        private $logged = false;
        private $dni;
        private $nombre;
        private $genero;
        private $nivel_permisos;

        public function __construct($user, $pass) {
            $usuario = $this->checkUser($user);
            if( $usuario != false && $usuario["activo"] == 1 ){
                if( checkPassword($usuario, $pass) ){
                    $this->logged = true;
                    $this->dni = $usuario["dni"];
                    $this->nombre = $usuario["nombre"];
                    $this->genero = $usuario["genero"];
                    $this->nivel_permisos = $usuario["nivel_permisos"];
                }
            }
        }

        public function checkUser($user){
            $usuario = trim($user);
            $result = false;
            if( $this->checkDNI($usuario) ){
                $result = getUsuarioPorDNI($usuario);
            } elseif( $this->checkEmail($usuario) ){
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

        public function checkPassword($user, $password) {
            if( password_verify($password, $user["contrasena"]) ){
                if( password_needs_rehash($password, HASH, ['cost' => COST]) ){
                    updateContrasenaUsuario($user[$dni], $password);
                }
                return true;
            }
            return false;
        }

    }

?>
