-Os problemas que tive no começo do desafio, foi encaixar todas as funcionalidades de uma maneira que não fosse dificil dar manutenção no código em sí com o uso de apenas 1 view para todas as funções.

 -Para melhorar a perfomance do projeto tentei usar o minimo de recarregamento de pagina possível, utilizando o ajax, e mantendo a arquitetura do projeto mas parecida como uma agenda.

 -Para rodar a aplicação vai ser necessario a utilização do XAMPP já que ele vem com o pacote completo MySQL e PHP dentre outros, e para testar o CRUD da aplicação será necessario criar no MySQL um banco de dados com o nome contatos e importar o arquivo do tipo .sql que irá estar anexado junto tanto ao email, quando ao projeto no GitHub, logo após isso só apontar a configuração do Apache no Xampp -> Config -> Apache (httpd.conf) irá abrir um bloco de notas das configurações do apache e então só procurar o seguinte: DocumentRoot "C:\Users\user\Desktop\  aqui irá colocar o nome da pasta onde se encontra o projeto  "
<Directory "C:\Users\user\Desktop\  aqui irá colocar o nome da pasta onde se encontra o projeto   ">, logo após isso só dar start no Apache e no MySQL e testar a aplicação.
