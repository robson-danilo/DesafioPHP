<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html> 
<head>
	<title>SoftMarkers Contatos</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">


	<link rel="stylesheet" href="template/css/bootstrap.css">
	<link rel="stylesheet" href="template/css/style.css">

	<script src="template/js/jquery.min.js"></script>
	<script src="template/js/bootstrap.min.js"></script>
	<script type="text/javascript" src="template/js/jquery.mask.js"></script>
	<!-- Custom Theme Style -->
	<link href="/template/build/css/custom.min.css" rel="stylesheet">

</head>


<style type="text/css">
	h1, h2, h3, h4, h5 {
		color: #000000;
		font-weight: 200;
	}
	label {
		color: #000000;
	}
</style>

<body> 
	<nav class="navbar navbar-expand-lg site-navbar navbar-light bg-light" id="pb-navbar">
		<div class="container">
			<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExample09" aria-controls="navbarsExample09" aria-expanded="false" aria-label="Toggle navigation">
				<span class="navbar-toggler-icon"></span>
			</button>
			<div class="collapse navbar-collapse justify-content-md-center" id="navbarsExample09">
				<ul class="navbar-nav">
					<li class="nav-item"><button type="button" class="btn btn-primary" onclick="listarContatos()">Listar Contatos</button></li>
					<li class="nav-item"><button type="button" class="btn btn-success" onclick="cadastrarContatos()">Cadastrar Contatos</button></li>
				</ul>
			</div>
		</div>
	</nav>

	<section class="site-hero" style="background-image: url(images/contato.png);" id="inicio" data-stellar-background-ratio="0.5">
	</section> 

	<div  id = "contents" > <?php echo $contents ?> </div> 
</body> 










</html>