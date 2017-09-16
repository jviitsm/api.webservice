<?php 

namespace App\Models\Entity;

/**

* @Entity @Table(name="login")

**/

class Login {
    /**
     * @var int 
     * @id @Column(type="integer")
     * @GeneratedValue
     */
    protected $id_login;
    /** 
     * @var string
     * @Column(type="string", unique=true, length=50)
     */
    protected $email;
    /**
     *@var string
     * @Column(type="string", unique=true, length=20)
     */
    protected $login;
    /**
     *@var string
     * @Column(type="string", nullable=true, length=50)
     */
    protected $senha;
    /**
     *@var boolean
     * @Column(type="boolean")
     */
    protected $status_login;
    /**
     *@var string
     * @Column(type="boolean")
     */
    protected $administrador;
    
    function getId_login() {
        return $this->id_login;
    }

    function getEmail() {
        return $this->email;
    }

    function getLogin() {
        return $this->login;
    }

    function getSenha() {
        return $this->senha;
    }

    function getStatus_login() {
        return $this->status_login;
    }

    function getAdministrador() {
        return $this->administrador;
    }

    function setId_login($id_login) {
        $this->id_login = $id_login;
    }

    function setEmail($email) {
        $this->email = $email;
    }

    function setLogin($login) {
        $this->login = $login;
    }

    function setSenha($senha) {
        $this->senha = $senha;
    }

    function setStatus_login($status_login) {
        $this->status_login = $status_login;
    }

    function setAsAdministrador($administrador) {
        $this->administrador = $administrador;
    }


}


?>