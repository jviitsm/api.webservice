<?php 

namespace App\Models\Entity;


/**

* @Entity @Table(name="denuncia")

**/

class Denuncia{
     /** 
     * @var int
     * @Id @Column(type="integer")
     * @GeneratedValue
     */
	private $id_denuncia;
    /** 
     * @var string
     * @Column(type="string", length=400)
     */
	private $descricao_denuncia;
    /** 
     * @var string
     * @Column(type="string", length=200)
     */    
	private $dir_foto_denuncia;
    /** 
     * @var double
     * @Column(type="float")
     */    
	private $latitude_denuncia; 
    /** 
     * @var double
     * @Column(type="float")
     */ 
	private $longitude_denuncia;
    /** 
     * @Column(type="datetime")
     */
	private $data_denuncia;
    /** 
     * @var bool
     * @Column(type="boolean", nullable=true)
     */
	private $status_denuncia;
        /**
        * @OneToOne(targetEntity="Solucao")
        * @JoinColumn(name="fk_solucao_denuncia", referencedColumnName="id_solucao")
        */
	private $fk_solucao_denuncia;
        /**
        * @OneToOne(targetEntity="Categoria")
        * @JoinColumn(name="fk_categoria_denuncia", referencedColumnName="id_categoria")
        */
	private $fk_categoria_denuncia;
        /**
        * @OneToOne(targetEntity="Login")
        * @JoinColumn(name="fk_login_denuncia", referencedColumnName="id_login")
        */
	private $fk_login_denuncia;
        
        function getId_denuncia() {
            return $this->id_denuncia;
        }

        function getDescricao_denuncia() {
            return $this->descricao_denuncia;
        }

        function getDir_foto_denuncia() {
            return $this->dir_foto_denuncia;
        }

        function getLatitude_denuncia() {
            return $this->latitude_denuncia;
        }

        function getLongitude_denuncia() {
            return $this->longitude_denuncia;
        }

        function getData_denuncia() {
            return $this->data_denuncia;
        }

        function getStatus_denuncia() {
            return $this->status_denuncia;
        }

        function getFk_solucao_denuncia(){
            return $this->fk_solucao_denuncia;
        }

        function getFk_categoria_denuncia(){
            return $this->fk_categoria_denuncia;
        }

        function getFk_login_denuncia(){
            return $this->fk_login_denuncia;
        }

        function setId_denuncia($id_denuncia) {
            $this->id_denuncia = $id_denuncia;
        }

        function setDescricao_denuncia($descricao_denuncia) {
            $this->descricao_denuncia = $descricao_denuncia;
        }

        function setDir_foto_denuncia($dir_foto_denuncia) {
            $this->dir_foto_denuncia = $dir_foto_denuncia;
        }

        function setLatitude_denuncia($latitude_denuncia) {
            $this->latitude_denuncia = $latitude_denuncia;
        }

        function setLongitude_denuncia($longitude_denuncia) {
            $this->longitude_denuncia = $longitude_denuncia;
        }

        function setData_denuncia($data_denuncia) {
            $this->data_denuncia = $data_denuncia;
        }

        function setStatus_denuncia($status_denuncia) {
            $this->status_denuncia = $status_denuncia;
        }

        function setFk_solucao_denuncia(Denuncia $fk_solucao_denuncia) {
            $this->fk_solucao_denuncia = $fk_solucao_denuncia;
        }

        function setFk_categoria_denuncia(Categoria $fk_categoria_denuncia) {
            $this->fk_categoria_denuncia = $fk_categoria_denuncia;
        }

        function setFk_login_denuncia(Login $fk_login_denuncia) {
            $this->fk_login_denuncia = $fk_login_denuncia;
        }
}