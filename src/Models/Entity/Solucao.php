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
    protected $id_solucao;
    /** 
     * @var string
     * @Column(type="string")
     */ 
    protected $descricao_solucao;
    /** 
     * @var string
     * @Column(type="string")
     */ 
    protected $dir_foto_solucao;
    /** 
     * @var string
     * @Column(type="datetime")
     */ 
    protected $data_solucao;
}