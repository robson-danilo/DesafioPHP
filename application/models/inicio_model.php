<?php
class Inicio_model extends CI_Model
{
	//Trata os caracteres para utf-8, tanto os de entrada como os de saída de dados.
	function __construct()
	{
		//$this->db->query( "SET NAMES 'utf8'" );
	}
	
	public function verificarEmail($email){
		$this->db->select('*');
		$this->db->from('contatos');
		$this->db->where('email', $email);
		$dados = $this->db->get()->row_array();
		if ($dados != '' || $dados != null){
			return true;
		}else {
			return false;
		}

	}

	public function salvarContato($dados){
		return $this->db->insert('contatos', $dados);
	}

	public function countContatos($nome=null){
		$this->db->select("count(contatos.id) as total");
		$this->db->from('contatos');
		if ($nome != '') {
			$this->db->like('nome', $nome);
		}
		$this->db->order_by('nome', 'ASC');

		return $this->db->get()->row_array();
	}

	public function get_contatos($inicio,$maximo,$nome=null){
		$this->db->select('*');
		$this->db->from('contatos');
		if ($nome!='') {
			$this->db->like('nome', $nome);
		}
		$this->db->limit($maximo,$inicio);
		$this->db->order_by('nome');
		return $this->db->get()->result_array();
	}

	public function get_contato($id_contato){
		$this->db->select('*');
		$this->db->from('contatos');
		$this->db->where('id', $id_contato);
		return $this->db->get()->row_array();
	}

	public function editarContato($dados){
		$this->db->where('id', $dados['id']);
		return $this->db->update('contatos', $dados);
	}

	public function deletar_contato($id_contato){
		$this->db->where('id', $id_contato);
		return $this->db->delete('contatos');
	}
	

}