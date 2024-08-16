<?php
header('Content-type: text/html; charset=ISO-8859-1');
if(IsSet($_COOKIE["logado"])){}
else{
echo '<meta http-equiv="refresh" content="0;url=login.html">';
exit; 

}
?>
<html>
<head>
<title>Tubar�o Transportes - Empresa</title>
   <link rel="icon" href="images/favicon.gif" type="image/x-icon"/>
    <!--[if lt IE 9]>
    <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
     <![endif]-->

   <link rel="shortcut icon" href="images/favicon.gif" type="image/x-icon"/> 
   <link rel="stylesheet" type="text/css" href="css/styles.css"/>

   </head>
   <body>

   <!--start container-->
   <div id="container">

   <!--start header-->
   <header>

 <!--start logo-->
   <a href="#" id="logo"><img src="images/logo.jpg" width="230" height="90" alt="logo"/></a>    
   <!--end logo-->

   
   <!--start menu-->

   <nav>
   <ul>
   <li><a href="index.html"class="current">Home</a></li>
   <li><a href="empresa.html">Empresa</a></li>
   <li><a href="servicos.html">Servi�os</a></li>
   <li><a href="contato.html">Contato</a></li>
   <li><a href="login.html">Area Restrita</a></li>
   </ul>
   </nav>
   <!--end menu-->

   <!--end header-->
   </header>

   <!--start intro-->

   <div id="intro">
   <img src="images/banner13.png"  alt="banner" width="979" height="273">
   </div>
   <!--end intro-->

   
   </header>

 <!--start holder-->

   <div class="holder_content">
   
<section class="group7">
 <h3>Empresa Institucional</h3>
   	<p>A Tubar�o Transportes iniciou suas atividades em 1� de fevereiro
de 1971, na cidade de Tubar�o, Estado de Santa Catarina. Com o desenvolvimento acelerado dos neg�cios, novos investimentos em 
estrutura e servi�os foram necess�rios, e em 1973 a matriz da 
empresa se trabsfere para a cidade de Porto Alegre, Rio Grande 
do Sul. Desde o come�o, sempre foi preocupa��o da empresa
prestar servi�os que superassem as expectativas dos clientes e
tivessem os mais altos padr�es de qualidade. Esta preocupa��o,
aliada a muito trabalho e dedica��o de toda sua equipe,
proporcionou a consolida��o de sua marca no mercado.
</p>
</section>

<section class="group8">

<a class="photo_hover2" href="#"><img src="images/logoempresa.jpg"></a>
  
   <!--start holder-->

  
  </section>

   </div>
   <!--end holder-->

   </div>
   <!--end container-->






   <footer>
   <div class="container">  
   <div id="FooterTwo"> copyright &copy; 2015 - by Jacques Pereira  </div>
   <div id="FooterTree"> Valid html5, css3, design and code by <a href="https://www.facebook.com/pages/Tubar%C3%A3o-Transportes/457092731103685?ref=hl" target="_blank">Facebook</a>   </div> 
   </div>
   </footer>
   </body>
</html>

</head>


<font face="Verdana" size="2">
<br><br> <a href="logout.php">Sair (finalizar) (logout)</a>
</font>
</body>
</html>
