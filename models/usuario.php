<?php

class Usuario{

    private $conexionDataBase;
    private $idUsuario;
    private $idRol;
    private $nombre;
    private $apellidos;
    private $email;
    private $password;
    private $telefono;
    private $direccion;
    private $ciudad;
    private $codigoPostal;
    private $puntosFidelidad;
    private $fechaRegistro;

    public function __construct($db) {
        $this->conexionDataBase = $db;
    }

    /**
     * Get the value of idUsuario
     */ 
    public function getIdUsuario()
    {
        return $this->idUsuario;
    }

    /**
     * Set the value of idUsuario
     *
     * @return  self
     */ 
    public function setIdUsuario($idUsuario)
    {
        $this->idUsuario = $idUsuario;

        return $this;
    }

    /**
     * Get the value of idRol
     */ 
    public function getIdRol()
    {
        return $this->idRol;
    }

    /**
     * Set the value of idRol
     *
     * @return  self
     */ 
    public function setIdRol($idRol)
    {
        $this->idRol = $idRol;

        return $this;
    }

    /**
     * Get the value of nombre
     */ 
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * Set the value of nombre
     *
     * @return  self
     */ 
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Get the value of apellidos
     */ 
    public function getApellidos()
    {
        return $this->apellidos;
    }

    /**
     * Set the value of apellidos
     *
     * @return  self
     */ 
    public function setApellidos($apellidos)
    {
        $this->apellidos = $apellidos;

        return $this;
    }

    /**
     * Get the value of email
     */ 
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set the value of email
     *
     * @return  self
     */ 
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get the value of password
     */ 
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set the value of password
     *
     * @return  self
     */ 
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get the value of telefono
     */ 
    public function getTelefono()
    {
        return $this->telefono;
    }

    /**
     * Set the value of telefono
     *
     * @return  self
     */ 
    public function setTelefono($telefono)
    {
        $this->telefono = $telefono;

        return $this;
    }

    /**
     * Get the value of direccion
     */ 
    public function getDireccion()
    {
        return $this->direccion;
    }

    /**
     * Set the value of direccion
     *
     * @return  self
     */ 
    public function setDireccion($direccion)
    {
        $this->direccion = $direccion;

        return $this;
    }

    /**
     * Get the value of ciudad
     */ 
    public function getCiudad()
    {
        return $this->ciudad;
    }

    /**
     * Set the value of ciudad
     *
     * @return  self
     */ 
    public function setCiudad($ciudad)
    {
        $this->ciudad = $ciudad;

        return $this;
    }

    /**
     * Get the value of codigoPostal
     */ 
    public function getCodigoPostal()
    {
        return $this->codigoPostal;
    }

    /**
     * Set the value of codigoPostal
     *
     * @return  self
     */ 
    public function setCodigoPostal($codigoPostal)
    {
        $this->codigoPostal = $codigoPostal;

        return $this;
    }

    /**
     * Get the value of puntosFidelidad
     */ 
    public function getPuntosFidelidad()
    {
        return $this->puntosFidelidad;
    }

    /**
     * Set the value of puntosFidelidad
     *
     * @return  self
     */ 
    public function setPuntosFidelidad($puntosFidelidad)
    {
        $this->puntosFidelidad = $puntosFidelidad;

        return $this;
    }

    /**
     * Get the value of fechaRegistro
     */ 
    public function getFechaRegistro()
    {
        return $this->fechaRegistro;
    }

    /**
     * Set the value of fechaRegistro
     *
     * @return  self
     */ 
    public function setFechaRegistro($fechaRegistro)
    {
        $this->fechaRegistro = $fechaRegistro;

        return $this;
    }

    public function registrar(){
        try {
            $sql = "INSERT INTO usuarios(nombre, apellidos, email, password, rol_id) values (:nombre, :apellidos, :email, :password, :idRol)";
            $sentencia = $this->conexionDataBase->prepare($sql);
            $sentencia->execute([
                ":nombre" => $this->nombre,
                ":apellidos" => $this->apellidos,
                ":email" => $this->email,
                ":password" => password_hash($this->password, PASSWORD_DEFAULT),
                ":idRol" => $this->idRol
                ]);

                return true;
        } catch (PDOException ) {
            return false;
        }
    }

    public function login($email, $password){
        try {
            $sql = "SELECT * from usuarios where email = :email";
            $sentencia = $this->conexionDataBase->prepare($sql);
            $sentencia->execute([":email" => $email]);

            $datosUsu = $sentencia->fetch(PDO::FETCH_ASSOC);

            if($datosUsu && password_verify($password, $datosUsu["password"] )){
                return $datosUsu;
            }else{
                return false;
            }

        } catch (PDOException) {
            return false;
        }
    }

}

?>