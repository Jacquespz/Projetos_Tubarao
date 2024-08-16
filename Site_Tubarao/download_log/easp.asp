<%@ Language=VBScript %>

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
dim oMail, fields, f1, f2, f3, f4, f5

 f1 = "Nome: "  & Request.Form("nome") & vbcrlf
 f2 = "Fone: "  & Request.Form("fone") & vbcrlf
 f3 = "Email: "  & Request.Form("email") & vbcrlf
 f4 = "Assunto: "  & Request.Form("assunto") & vbcrlf
 f5 = "Mensagem: " & Request.Form("mensagem")  & vbcrlf
 fields = f1 & f2 & f3 & f4 & f5

set oMail = Server.CreateObject("CDO.Message")

Set oMail.Configuration = iConf

oMail.From = request.Form("nome")
oMail.To = "design4@terra.com.br"
oMail.subject ="Contato via Site"
oMail.TextBody = fields
oMail.Send

set oMail = Mothing

 Response.write "status=MENSAGEM ENVIADA COM SUCESSO"
%>
