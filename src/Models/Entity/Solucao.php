<?php 

namespace App\Models\Entity;

/**

* @Entity @Table(name="solucao")

**/

class Solucao{
     /** 
     * @var int
     * @Id @Column(type="integer")
     * @GeneratedValue
     */
    public $id_solucao;
    /** 
     * @var string
     * @Column(type="string")
     */ 
    public $descricao_solucao;
    /** 
     * @var string
     * @Column(type="string")
     */ 
    public $dir_foto_solucao;
    /** 
     * @var string
     * @Column(type="datetime")
     */ 
    public $data_solucao;
}