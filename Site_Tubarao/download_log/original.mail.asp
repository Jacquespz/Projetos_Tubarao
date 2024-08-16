<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
<%@LANGUAGE="VBSCRIPT" CODEPAGE="1252"%>
<%
'vamos pegar as variaveis vindas
'atraves do objMail
nome = request.Form("nome")
email = request.Form("email")
assunto = request.Form("assunto")
mensagem = request.Form("mensagem")

'criando objeto que enviara os emails
set oMail = Server.CreateObject("CDONTS.NewMail")
'quem recebera os emails
oMail.To = "design4@terra.com.br"
'quem esta enviando os emails
oMail.From = nome&" <"&email&">"
'assunto
oMail.Subject = assunto
'mensagem
oMail.Body = mensagem
'formato do email
oMail.BodyFormat = 1
'enviando
oMail.Send
'agora enviamos para o asp a variavel ver=1 dizendo q tudo
'ocorreu bem
Response.Write("&ver=1&")
%>

