<?php 

namespace App\Models\Entity;

use App\Models\Entity\Login;
/**

* @Entity @Table(name="empresa")

**/
class Empresa{
        /** 
        * @var int
        * @Id @Column(type="integer")
        * @GeneratedValue
        */
	private $id_empresa;
        /** 
        * @var string
        * @Column(type="string", unique=true, length=14)
        */ 
	private $cnpj;
        /** 
         * @var string
         * @Column(type="string", unique=true, length=30)
         */ 
	private $razao_social;
        /** 
         * @var string
         * @Column(type="string", length=30)
         */     
	private $nome_fantasia;
        /** 
         * @var string
         * @Column(type="string", length=15)
         */ 
	private $estado;
        /** 
         * @var string
         * @Column(type="string", length=30)
         */ 
        private $cidade;
        /** 
         * @var string
         * @Column(type="string", length=200)
         */ 
	private $dir_foto_usuario;
        /**
        * @OneToOne(targetEntity="Login")
        * @JoinColumn(name="fk_login_empresa", referencedColumnName="id_login")
        */
	private $fk_login_empresa;
        
        function getId_empresa() {
            return $this->id_empresa;
        }

        function getCnpj() {
            return $this->cnpj;
        }

        function getRazao_social() {
            return $this->razao_social;
        }

        function getNome_fantasia() {
            return $this->nome_fantasia;
        }

        function getEstado() {
            return $this->estado;
        }

        function getDir_foto_usuario() {
            return $this->dir_foto_usuario;
        }

        function getFk_login_empresa() {
            return $this->fk_login_empresa;
        }

        function setId_empresa($id_empresa) {
            $this->id_empresa = $id_empresa;
        }

        function setCnpj($cnpj) {
            $this->cnpj = $cnpj;
        }

        function setRazao_social($razao_social) {
            $this->razao_social = $razao_social;
        }

        function setNome_fantasia($nome_fantasia) {
            $this->nome_fantasia = $nome_fantasia;
        }

        function setEstado($estado) {
            $this->estado = $estado;
        }

        function setDir_foto_usuario($dir_foto_usuario) {
            $this->dir_foto_usuario = $dir_foto_usuario;
        }

        function setFk_login_empresa(Login $fk_login_empresa) {
            $this->fk_login_empresa = $fk_login_empresa;
        }


}