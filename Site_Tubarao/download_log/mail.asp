<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
<%@LANGUAGE="VBSCRIPT" CODEPAGE="1252"%>

<%
Dim iConf
Dim Flds 
Set iConf = Server.CreateObject("CDO.Configuration")
Set Flds = iConf.Fields
With Flds
    .Item("http://schemas.microsoft.com/cdo/configuration/smtpserver") = "mail-fwd"
    .Item("http://schemas.microsoft.com/cdo/configuration/smtpserverport") = 25
    .Item("http://schemas.microsoft.com/cdo/configuration/sendusing") = 2
    .Item("http://schemas.microsoft.com/cdo/configuration/smtpconnectiontimeout") = 10
    .Update
End With
%>
<%
'vamos pegar as variaveis vindas
'atraves do objMail
nome = request.Form("nome")
email = request.Form("email")
assunto = request.Form("assunto")
mensagem = request.Form("mensagem")

'criando objeto que enviara os emails
set oMail = Server.CreateObject("CDO.Message")

Set oMail.Configuration = iConf
'quem recebera os emails
oMail.To = "design4@terra.com.br"
'quem esta enviando os emails
oMail.From = nome&" <"&email&">"
'assunto
oMail.Subject = assunto
'mensagem
oMail.TextBody = mensagem
'formato do email
'enviando
oMail.Send
'agora enviamos para o asp a variavel ver=1 dizendo q tudo
'ocorreu bem
Response.Write("&ver=1&")
%>

