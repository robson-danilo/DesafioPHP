<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$estadosBrasileiros = array(
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
?>

<script type="text/javascript">

	$(document).ready(function(){
		$('#telefone_contato').mask('(99) 99999-9999',{placeholder:'(XX) XXXXX-XXXX'}); 
		$('#cep').mask('99.999-999',{placeholder:'99.999-999'});
		$('#telefone_contato_editar').mask('(99) 99999-9999',{placeholder:'(XX) XXXXX-XXXX'}); 
		$('#cep_editar').mask('99.999-999',{placeholder:'99.999-999'});
		listarContatos();
	});


	function cadastrarContatos(){
		$('#cadastrarContato').modal('show');
	}

	function loadDataInTable(value){	
		value.telefone=value.telefone.replace(/^(\d{2})(\d)/g,"($1) $2"); 
		value.telefone=value.telefone.replace(/(\d)(\d{4})$/,"$1-$2"); 
		var event_data ='';
		
		event_data += '<div class="card" style="width: 16rem; border-radius: 4.25rem;">';
		if (value.foto == '' || value.foto == null){
			event_data += '<br><div style="text-align: center;"><img height="200" width="200" style="border-radius: 2.25rem;" src="<?php echo base_url()?>/images/user.png"></div>';
		}else {
			event_data += '<br><div style="text-align: center;"><img height="200" width="200" style="border-radius: 2.25rem;" src="<?php echo base_url()?>/fotos/'+value.foto+'"></div>';
		}
		event_data += '<div class="card-body">';
		event_data += '<h5 class="card-title" style="max-width: 34ch; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">'+value.nome+' '+value.sobrenome+'</h5>';
		event_data += '<p class="card-text">Telefone: '+value.telefone+'<br>Email: '+value.email+'</p>';
		event_data += '<button type="button" onclick="editar('+value.id+')" class="btn btn-success btn-xs">';
		event_data += 'Editar</button>';
		event_data += '<button type="button" onclick="deletar('+value.id+')" class="btn btn-danger btn-xs">';
		event_data += 'Deletar</button>';
		event_data += '</div>';
		event_data += '</div>';



		return event_data;
	}


	function pesquisarContatos(inicio=0){
		$("#div_funcionarios").html('');
		$("#div_funcionarios").append('<i class="fa fa-spinner fa-spin"></i>Carregando contatos...');
		var nome = $('#nome_pesquisar').val();
		$.ajax({
			url: "<?php echo base_url()?>welcome/AjaxlistaContato",
			dataType: 'json',
			type: 'get',
			data: {nome:nome, inicio:inicio},
			cache: false,
			success: function(data){     
				var event_data = '';

				$.each(data.registros, function(index, value){          
					event_data += loadDataInTable(value);

				}); 

				$("#divNavegacao").html('');
				var botaoNevegacao = '';

				if(data.numPaginas >1){
					botaoNevegacao += ' <div class="btn-toolbar" role="toolbar" aria-label="Toolbar with button groups">';
					botaoNevegacao += '<div class="btn-group mr-2" role="group" aria-label="First group">';
					if(data.navegacao.anterior != null){

						botaoNevegacao += '<button type="button" style="padding: 9px 8px;" class="btn btn-secondary" onclick="pesquisarContatos('+data.navegacao.anterior+')"><</button>';        
					}

					$.each(data.numDaPagina, function(index, value){ 
						paginaAtual=data.inicio==value?"disabled":'';
						paginaVazia=Math.ceil(data.numPaginas)<data.indexDaPagina+index?"disabled":'';
						if (!paginaVazia) {

							botaoNevegacao += ' <button type="button" '+paginaAtual+' class="btn btn-secondary" onclick="pesquisarContatos('+value+')">'+(data.indexDaPagina+index)+'</button>';
						}          
					});

					if(data.navegacao.proximo != null){
						botaoNevegacao += '<button type="button"  class="btn btn-secondary" style="padding: 9px 8px;" onclick="pesquisarContatos('+data.navegacao.proximo+')">></button>';          
					} 
					botaoNevegacao += '</div>';
					botaoNevegacao += '</div>';
					botaoNevegacao += '<p>';
				}



				$("#divNavegacao").html(botaoNevegacao);
				$("#divNavegacao2").html(botaoNevegacao);       

				if(event_data!=''){
					$("#div_funcionarios").html('');
					$("#div_funcionarios").append(event_data);
				}else{
					$("#div_funcionarios").html('');
					$("#div_funcionarios").append('<tr><td colspan="5">Nenhum contato encontrado.</td></tr>');
				}
			},
			error: function(d){
				$("#div_funcionarios").html('');
				$("#div_funcionarios").append('Nenhum contato encontrado');
			}
		});
	}


	function listarContatos(){
		pesquisarContatos();
		$('#listarContatos').modal('show');
	}

	function verificarEmail(tipo){
		let email = $('#email_contato').val();
		
		$.ajax({
			url: "<?php echo base_url()?>welcome/AjaxVerificarEmail",
			dataType: 'json',
			type: 'post',
			data: {email:email},
			cache:false,
			success: function(data){
				if (data == false){
					
					if (tipo == 1){
						$('#telefone_contato').val($('#telefone_contato').val().replace('(', '').replace(')', '').replace(' ', '').replace('-', ''));
						$('#cep').val($('#cep').val().replace('.', '').replace('-', ''));
						document.getElementById("new_contato").submit();
					}else {
						$('#telefone_contato_editar').val($('#telefone_contato_editar').val().replace('(', '').replace(')', '').replace(' ', '').replace('-', ''));
						$('#cep_editar').val($('#cep_editar').val().replace('.', '').replace('-', ''));
						document.getElementById("edit_contato").submit();
					}
					
				}else {
					$('#validar').html('');
					$('#validar').append('<div class="alert alert-danger alert-dismissible fade show" role="alert" id="enviado"><strong>Já existe um contato com esse email!</strong><button type="button" class="close" data-dismiss="alert" aria-label="Close"></button></div>');
					setTimeout(function () {  
						$('#validar').html('');
					}, 5 * 1000);
				}
			},
			error: function(d){

				return false;
			}
		});
	}

	function deletar(id_contato){
		window.location.href = "<?php echo site_url("welcome/deletar/"); ?>"+id_contato;
	}

	function editar(id_usuario){
		$.ajax({
			url: "<?php echo base_url()?>welcome/AjaxBuscarDadosContato",
			dataType: 'json',
			type: 'get',
			data: {id_usuario:id_usuario},
			cache:false,
			success: function(data){
				var event_data = '';
				var event_data_uf ='';
				if (data.contato.foto == '' || data.contato.foto == null){
					event_data += '<img height="200" width="200" style="border-radius: 2.25rem;" src="images/user.png">';
				}else {
					event_data += '<img height="200" width="200" style="border-radius: 2.25rem;" src="fotos/'+data.contato.foto+'">';
				}

				event_data_uf += '<label class="control-label"><label for="uf">UF</label></label>';
				event_data_uf += '<select class="form-control" id="uf_edit" name="uf_edit">';
				if (data.contato.uf == null){
					event_data_uf += '<option selected="selected" disabled>Selecione...</option> ';
				}else {
					event_data_uf += '<option disabled>Selecione...</option> ';
				}
				
				$.each(data.estados, function(index, value){
					if (value.sigla == data.contato.uf){
						event_data_uf += '<option selected="selected" value="'+value.sigla+'">'+value.nome+'</option>';
					}else {
						event_data_uf += '<option value="'+value.sigla+'">'+value.nome+'</option>';
					}
					
				});
				event_data_uf += '</select>';


				$('#editarContato #nome_contato_editar').val(data.contato.nome);
				$('#editarContato #sobrenome_contato_editar').val(data.contato.sobrenome);
				data.contato.telefone=data.contato.telefone.replace(/^(\d{2})(\d)/g,"($1) $2"); 
				data.contato.telefone=data.contato.telefone.replace(/(\d)(\d{4})$/,"$1-$2"); 
				$('#editarContato #telefone_contato_editar').val(data.contato.telefone);
				$('#editarContato #email_contato_editar').val(data.contato.email);
				$('#editarContato #endereco_contato_editar').val(data.contato.endereco);
				$('#editarContato #cep_editar').val(data.contato.cep);
				$('#editarContato #logadrouro_editar').val(data.contato.logradouro);
				$('#editarContato #num_logadrouro_editar').val(data.contato.numero);
				$('#editarContato #complemento_editar').val(data.contato.complemento);
				$('#editarContato #bairro_editar').val(data.contato.bairro);
				$('#editarContato #municipio_editar').val(data.contato.municipio);
				$('#editarContato #foto_antiga').val(data.contato.foto);
				$('#editarContato #id_contato').val(id_usuario);
				



				$("#imagem_editar").html('');
				$("#imagem_editar").append(event_data);

				$("#uf_editar").html('');
				$("#uf_editar").append(event_data_uf);

				$('#editarContato').modal('show');
			},
			error: function(d){

				return false;
			}
		});
	}


</script>

<div class="modal fade bd-example-modal-lg" id="listarContatos" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
	
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<?php if($this->session->flashdata("evento_positivo")) : ?>
				<div class="alert alert-success alert-dismissiblE" role="alert">
					<?php echo $this->session->flashdata("evento_positivo") ?>
					<button type="button" class="close" data-dismiss="alert" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
			<?php endif ?>
			<?php if($this->session->flashdata("evento_negativo")) : ?>
				<div class="alert alert-danger alert-dismissible" role="alert">
					<?php echo $this->session->flashdata("evento_negativo") ?>
					<button type="button" class="close" data-dismiss="alert" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
			<?php endif ?>
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Lista de Contatos</h5>
			</div><br>
			<div class="col-lg-12">
				<div class="col-md-7">
					<div>
						<input required="required"  style="color: white;" class="form-control" id="nome_pesquisar" name="nome_pesquisar" placeholder="Nome" type="text">
					</div>
				</div>
				<div class="col-md-2">      
					<button type="button" class="btn btn-default" onclick="pesquisarContatos()">Pesquisar</button>     
				</div> 
			</div>

			<div class="modal-body">
				<div class="row" style="float: right;" id="divNavegacao"></div> 			
				<br><br><br>
				<div class="row" id="div_funcionarios"></div>	
				<br>
				<br><div class="row" style="float: right;" id="divNavegacao2"></div>		

			</div>
		</div>
	</div>
</div>

<!-- Modal Cadatrar -->
<form accept-charset="UTF-8" onsubmit="verificarEmail(1);return false" enctype="multipart/form-data" action="<?php echo site_url("welcome/SalvarContato");?>" class="form-horizontal" id="new_contato" name="new_contato" method="post">
	<div class="modal fade" id="cadastrarContato" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">

				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel">Novo Contato</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-md-12">
							<div id="validar">
							</div>
						</div>

						<div class="col-lg-12">
							<label class="control-label"><label>Foto</label></label>
							<input class="form-control" id="foto" name="foto" type="file" accept=".gif,.jpg,.png">
						</div> 


						<div class="col-md-7">
							<div>
								<label for="consulta">Nome</label>
								<input required="required"  style="color: white;" class="form-control" id="nome_contato" name="nome_contato" placeholder="Nome" type="text">
							</div>
						</div>
						<div class="col-md-5">
							<div>
								<label for="consulta">Sobrenome</label>
								<input style="color: white;" class="form-control" id="sobrenome_contato" name="sobrenome_contato" placeholder="Sobrenome" type="text">
							</div>
						</div>

						<div  class="col-md-5">
							<div id="origemtelefone">
								<label for="consulta">Telefone</label>
								<input style="color: white;" class="form-control" onkeypress="return sem_acento(event);" id="telefone_contato" name="telefone_contato" placeholder="Telefone" type="text">
							</div>						
						</div>

						<div  class="col-md-7">
							<div id="origememail">
								<label for="consulta">Email</label>
								<input pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$" style="color: white;" class="form-control" id="email_contato" name="email_contato" placeholder="exemplo@gmail.com" type="text">
							</div>						
						</div>
						<div class="col-md-8">
							<label class="control-label"><label for="endereco_contato">Endereço</label></label>
							<input class="form-control" id="endereco_contato" name="endereco_contato" type="text" size="30">  
						</div>
						<div class="col-md-4">
							<label class="control-label"><label for="cep">CEP</label></label>
							<input class="form-control" id="cep" name="cep" type="text" size="30" onkeypress="return sem_acento(event);">  
						</div>
						<div class="col-md-8">
							<label class="control-label"><label for="logadrouro">Logradouro</label></label>
							<input class="form-control" id="logadrouro" name="logadrouro" type="text" size="30"> 
						</div>
						<div class="col-md-4">
							<label class="control-label"><label for="num_logadrouro">Numero</label></label>
							<input class="form-control" id="num_logadrouro" name="num_logadrouro" size="30" type="text">     
						</div>     


						<div class="col-md-6">
							<label class="control-label"><label for="complemento">Complemento</label></label>
							<input class="form-control" id="complemento" name="complemento" type="text" size="30"> 
						</div> 
						<div class="col-md-6">
							<label class="control-label"><label for="bairro">Bairro</label></label>
							<input class="form-control" id="bairro" name="bairro" size="30" type="text">     
						</div> 
						<div class="col-md-6">
							<label class="control-label"><label for="municipio">Município</label></label>
							<input class="form-control" id="municipio" name="municipio" type="text" size="30">  
						</div> 

						<div class="col-md-6"> 
							<label class="control-label"><label for="uf">UF</label></label>
							<select class="form-control" id="uf" name="uf">
								<option selected="selected" disabled>Selecione...</option> 
								<?php
								foreach($estadosBrasileiros as $estados)
								{
									echo "<option value='".$estados['sigla']."'>".$estados['nome']."</option>"; 
								}
								?>  
							</select>
						</div> 
					</div>

				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
					<button type="submit" class="btn btn-primary">Salvar Contato</button>
				</div>
			</div>
		</div>
	</div>
</form>

<!-- Modal Editar -->
<form accept-charset="UTF-8" onsubmit="verificarEmail(0);return false" enctype="multipart/form-data" action="<?php echo site_url("welcome/EditarContato");?>" class="form-horizontal" id="edit_contato" name="edit_contato" method="post">
	<div class="modal fade" id="editarContato" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<input type="hidden" id="id_contato" name="id_contato">
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel">Editar Contato</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-md-12">
							<div id="validar">
							</div>
						</div>

						<div class="col-lg-12">
							<div style="text-align: center;" id="imagem_editar"></div>
							<label class="control-label"><label>Foto</label></label>
							<input type="hidden" name="foto_antiga" id="foto_antiga">
							<input class="form-control" id="foto_editar" name="foto_editar" type="file" accept=".gif,.jpg,.png">
						</div> 


						<div class="col-md-7">
							<div>
								<label for="consulta">Nome</label>
								<input required="required"  style="color: white;" class="form-control" id="nome_contato_editar" name="nome_contato_editar" placeholder="Nome" type="text">
							</div>
						</div>
						<div class="col-md-5">
							<div>
								<label for="consulta">Sobrenome</label>
								<input style="color: white;" class="form-control" id="sobrenome_contato_editar" name="sobrenome_contato_editar" placeholder="Sobrenome" type="text">
							</div>
						</div>

						<div  class="col-md-5">
							<div id="origemtelefone">
								<label for="consulta">Telefone</label>
								<input style="color: white;" class="form-control" onkeypress="return sem_acento(event);" id="telefone_contato_editar" name="telefone_contato_editar" placeholder="Telefone" type="text">
							</div>						
						</div>

						<div  class="col-md-7">
							<div id="origememail">
								<label for="consulta">Email</label>
								<input pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$" style="color: white;" class="form-control" id="email_contato_editar" name="email_contato_editar" placeholder="exemplo@gmail.com" type="text">
							</div>						
						</div>
						<div class="col-md-8">
							<label class="control-label"><label for="endereco_contato">Endereço</label></label>
							<input class="form-control" id="endereco_contato_editar" name="endereco_contato_editar" type="text" size="30">  
						</div>
						<div class="col-md-4">
							<label class="control-label"><label for="cep">CEP</label></label>
							<input class="form-control" id="cep_editar" name="cep_editar" type="text" size="30" onkeypress="return sem_acento(event);">  
						</div>
						<div class="col-md-8">
							<label class="control-label"><label for="logadrouro">Logradouro</label></label>
							<input class="form-control" id="logadrouro_editar" name="logadrouro_editar" type="text" size="30"> 
						</div>
						<div class="col-md-4">
							<label class="control-label"><label for="num_logadrouro">Numero</label></label>
							<input class="form-control" id="num_logadrouro_editar" name="num_logadrouro_editar" size="30" type="text">     
						</div>     


						<div class="col-md-6">
							<label class="control-label"><label for="complemento">Complemento</label></label>
							<input class="form-control" id="complemento_editar" name="complemento_editar" type="text" size="30"> 
						</div> 
						<div class="col-md-6">
							<label class="control-label"><label for="bairro">Bairro</label></label>
							<input class="form-control" id="bairro_editar" name="bairro_editar" size="30" type="text">     
						</div> 
						<div class="col-md-6">
							<label class="control-label"><label for="municipio">Município</label></label>
							<input class="form-control" id="municipio_editar" name="municipio_editar" type="text" size="30">  
						</div> 

						<div class="col-md-6" id="uf_editar"> 
							
						</div> 

						
					</div>

				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
					<button type="submit" class="btn btn-primary">Atualizar Contato</button>
				</div>
			</div>
		</div>
	</div>
</form>



