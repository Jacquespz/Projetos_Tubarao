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
'Emerson Rocha Programador Web
%>
<%
'As informações que serão enviadas para você usando objCDOMail
Dim strTo, strSubject, strBody 
Dim oMail 

'Digite seu e-mail abaixo
strTo = "design4@terra.com.br"
'Esta área recupra os dados enviados por seu filme como o assunto e a mensagem
strSubject = Request.Form("assunto") 
strBody = Request.Form("mensagem")


'Enviando as informações para seu e-mail usando CDO.Message

Set CDO.Message
.Configuration = iConf
Set oMail = Server.CreateObject("CDO.Message")

Set oMail.Configuration = iConf

'Coloque o titulo do e-mail que você vai receber no lugar de Email Emerson Freecode
oMail.From = "Mensagem via Site"
oMail.To = strTo
oMail.Subject = strSubject
oMail.TextBody = strBody
oMail.Send
Set oMail = Nothing
%>

