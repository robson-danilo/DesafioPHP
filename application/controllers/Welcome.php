<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */


	public function __construct()
	{
		parent::__construct();
		$this->load->helper('url'); //Carrega o helper de url(link)
		$this->load->helper('form'); //Carrega o helper de formul?rio
		$this->load->helper('array'); //Carrega o helper array
		$this->load->helper('encode');
		$this->load->library('session'); //Carrega a biblioteca de sess?o
		$this->load->library('table'); // Carrega a bibioteca de tabela
		$this->load->library('form_validation'); //Carrega a biblioteca de valida??o de formul?rio
		$this->load->model('inicio_model');//Carrega o model
		//Limpa o cache, não permitindo ao usuário visualizar nenhuma página logo depois de ter feito logout do sistema
		$this->output->set_header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, max-age=0, post-check=0, pre-check=0");
		$this->output->set_header("Pragma: no-cache");
	}


	public function index()
	{
		$this->template->load('template/Template_Base', 'inicio');
		//$this->load->view('welcome_message');
	}

	public function SalvarContato(){

	//print_r($this->input->post());exit;

		$destino = 'fotos/' . $_FILES['foto']['name'];

		$arquivo_tmp = $_FILES['foto']['tmp_name'];

		move_uploaded_file($arquivo_tmp, $destino);

		$dados = array('nome'=> $this->input->post('nome_contato'),
			'sobrenome'=> $this->input->post('sobrenome_contato'),
			'telefone'=> $this->input->post('telefone_contato'),
			'email'=> $this->input->post('email_contato'),
			'endereco'=> $this->input->post('endereco_contato'),
			'cep'=> $this->input->post('cep'),
			'logradouro'=> $this->input->post('logadrouro'),
			'numero'=> $this->input->post('num_logadrouro'),
			'complemento'=> $this->input->post('complemento'),
			'bairro'=> $this->input->post('bairro'),
			'municipio'=> $this->input->post('municipio'),
			'uf'=> $this->input->post('uf'),
			'foto'=> $_FILES['foto']['name']
		);

		$retorno = $this->inicio_model->salvarContato($dados);

		if ($retorno != false){
			$this->session->set_flashdata("evento_positivo", "Cadastrado com sucesso!");
		}else {
			$this->session->set_flashdata("evento_negativo", "Falha ao cadastrar!");
		}

		redirect('welcome');
	}

	public function EditarContato(){

		$destino = 'fotos/' . $_FILES['foto_editar']['name'];

		$arquivo_tmp = $_FILES['foto_editar']['tmp_name'];

		move_uploaded_file($arquivo_tmp, $destino);

		$dados = array('nome'=> $this->input->post('nome_contato_editar'),
			'sobrenome'=> $this->input->post('sobrenome_contato_editar'),
			'telefone'=> $this->input->post('telefone_contato_editar'),
			'email'=> $this->input->post('email_contato_editar'),
			'endereco'=> $this->input->post('endereco_contato_editar'),
			'cep'=> $this->input->post('cep_editar'),
			'logradouro'=> $this->input->post('logadrouro_editar'),
			'numero'=> $this->input->post('num_logadrouro_editar'),
			'complemento'=> $this->input->post('complemento_editar'),
			'bairro'=> $this->input->post('bairro_editar'),
			'municipio'=> $this->input->post('municipio_editar'),
			'uf'=> $this->input->post('uf_edit'),
			'foto'=> $_FILES['foto_editar']['name'],
			'id'=> $this->input->post('id_contato')
		);

		if (empty($_FILES['foto_editar']['name'])){
			$dados['foto'] = $this->input->post('foto_antiga');
		}

		$retorno = $this->inicio_model->editarContato($dados);


		if ($retorno != false){
			$this->session->set_flashdata("evento_positivo", "Editado com sucesso!");
		}else {
			$this->session->set_flashdata("evento_negativo", "Falha ao editar!");
		}

		redirect('welcome');
	}

	public function deletar($id_contato){
		$retorno = $this->inicio_model->deletar_contato($id_contato);

		if ($retorno != false){
			$this->session->set_flashdata("evento_positivo", "Deletado com sucesso!");
		}else {
			$this->session->set_flashdata("evento_negativo", "Falha ao deletar!");
		}

		redirect('welcome');
	}

	public function AjaxVerificarEmail(){
		$retorno = $this->inicio_model->verificarEmail($this->input->post('email'));

		echo json_encode($retorno, JSON_UNESCAPED_UNICODE);
	}

	public function AjaxlistaContato(){
		$inicio = $this->input->get('inicio');
		$nome = $this->input->get('nome');
		$maximo = 3;
		$registros = $this->inicio_model->get_contatos($inicio, $maximo,$nome);
		$total = $this->inicio_model->countContatos($nome);


		$total = $total['total'];
		$navegacao['anterior'] = null;
		$navegacao['proximo'] = null;	
		$numPaginas = null;	
		if (!empty($total)) {
			$numPaginas = $total/$maximo ;
			if ($numPaginas>5) {

				if ($inicio<=3*$maximo) {
					$numDaPagina[0] = 0;
					$numDaPagina[1] = $maximo;
					$numDaPagina[2] = 2*$maximo;
					$numDaPagina[3] = 3*$maximo;
					$numDaPagina[4] = 4*$maximo;
				}else{
					$numDaPagina[0] = $inicio-2*$maximo;
					$numDaPagina[1] = $inicio-$maximo;
					$numDaPagina[2] = $inicio;
					$numDaPagina[3] = $inicio+$maximo;
					$numDaPagina[4] = $inicio+2*$maximo;
				}
				
			}else{
				for ($i=0; $i < $numPaginas; $i++) { 
					$numDaPagina[$i] = $i*$maximo;
				}
			}
			
		}else{
			$numDaPagina[0] = $maximo;
		}
		
		if ($inicio<=3*$maximo) {
			$indexDaPagina=1;
		}else{
			$indexDaPagina=($inicio/$maximo)-1;
		}
		
		if($total < $maximo){
			$navegacao['anterior'] = null;
			$navegacao['proximo'] = null;				
		}else if($inicio == 0){
			$navegacao['anterior'] = null;
			$navegacao['proximo'] = $inicio + $maximo;				
		}else if($inicio >= $total){
			$navegacao['anterior'] = $inicio - $maximo;
			$navegacao['proximo'] = null;	
		}else if(($inicio > 0) && ($inicio < $total)){
			$navegacao['anterior'] = $inicio - $maximo;
			$navegacao['proximo'] = $inicio + $maximo;					
		}		
		$inicio==null?$inicio=0:'';
		echo json_encode(array('total'=>$total,'registros'=>$registros,'navegacao'=>$navegacao,'numPaginas'=>$numPaginas,'numDaPagina'=>$numDaPagina,'inicio'=>$inicio,'indexDaPagina'=>$indexDaPagina),JSON_UNESCAPED_UNICODE);
	}

	public function AjaxBuscarDadosContato(){
		$dados['contato'] = $this->inicio_model->get_contato($this->input->get('id_usuario'));

		$dados['estados'] = array(
			array("sigla" => "AC", "nome" => "Acre"),
			array("sigla" => "AL", "nome" => "Alagoas"),
			array("sigla" => "AM", "nome" => "Amazonas"),
			array("sigla" => "AP", "nome" => "Amapá"),
			array("sigla" => "BA", "nome" => "Bahia"),
			array("sigla" => "CE", "nome" => "Ceará"),
			array("sigla" => "DF", "nome" => "Distrito Federal"),
			array("sigla" => "ES", "nome" => "Espírito Santo"),
			array("sigla" => "GO", "nome" => "Goiás"),
			array("sigla" => "MA", "nome" => "Maranhão"),
			array("sigla" => "MT", "nome" => "Mato Grosso"),
			array("sigla" => "MS", "nome" => "Mato Grosso do Sul"),
			array("sigla" => "MG", "nome" => "Minas Gerais"),
			array("sigla" => "PA", "nome" => "Pará"),
			array("sigla" => "PB", "nome" => "Paraíba"),
			array("sigla" => "PR", "nome" => "Paraná"),
			array("sigla" => "PE", "nome" => "Pernambuco"),
			array("sigla" => "PI", "nome" => "Piauí"),
			array("sigla" => "RJ", "nome" => "Rio de Janeiro"),
			array("sigla" => "RN", "nome" => "Rio Grande do Norte"),
			array("sigla" => "RO", "nome" => "Rondônia"),
			array("sigla" => "RS", "nome" => "Rio Grande do Sul"),
			array("sigla" => "RR", "nome" => "Roraima"),
			array("sigla" => "SC", "nome" => "Santa Catarina"),
			array("sigla" => "SE", "nome" => "Sergipe"),
			array("sigla" => "SP", "nome" => "São Paulo"),
			array("sigla" => "TO", "nome" => "Tocantins")
		); 

		echo json_encode($dados, JSON_UNESCAPED_UNICODE);
	}

}
