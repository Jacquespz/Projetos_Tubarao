<%
'
'  FLASHMAIL 1.0
'
'  Autores:
'  Kali Romiglia (http://www.romiglia.com)
'  Miguel Mora (http://www.prodigia.com)
'  DUDAS O PREGUNTAS A KALI@ROMIGLIA.COM

'
'  INSTRUCCIONES ::
'
'  Este archivo debe ir en la misma carpeta donde este el swf.
'
'  Solo debes cambiar tres variables::
'  1_ Destino = "tuemail@tudominio.com"
'  2_ servidor = "mail.tuservidor.com"  si no sabes cual es, simplemente contactate con tu proveedor
'  3_ titulo = "el titulo que aparecerá en el email"
'
'

Destino = "design4@terra.com.br"
servidor = "smtp.tubaraotransportes.com.br"
titulo = "Formulario de contacto"


'Asignamos los valores recibidos del formulario a las variables
'Estas variables deben estar en el formulario dentro del swf
Origen = Request("email")
nombre = Request("nombre")
empresa = Request("empresa")
Mensaje = Request("mensaje")



'Creamos una instancia del objeto ASPMAIL
Set Mail = Server.CreateObject("Persits.MailSender")


' Creamos el cuerpo del mensaje
strBody = strBody & "<font face='Verdana, Arial, Helvetica, sans-serif' size='2' color='#555555'>" 
strBody = strBody & "Mi nombre es: "
strBody = strBody & "<b>" & nombre & "</b><br><br>"
strBody = strBody & "Mi e-mail es: "
strBody = strBody & "<b>" & Origen & "</b><br><br>"
strBody = strBody & "Trabajo en la empresa: "
strBody = strBody & "<b>" & empresa & "</b><br><hr><br>"
strBody = strBody & "Mi mensaje es: <br>"
strBody = strBody & Mensaje & chr(10)
strBody = strBody & "</font>" 


'Asignamos las propiedades al objeto con ASPMAIL
Mail.Host = servidor
Mail.Port = 25 ' Valor opcional. 25 es el puerto por defecto.
Mail.From = "design4@terra.com.br"
Mail.FromName = Email ' Opcional
Mail.Subject = titulo
Mail.Body = strBody
Mail.IsHTML = True
Mail.AddAddress Destino

On Error Resume Next

Mail.Send


' El control del error es con ASPMAIL

If Err <> 0 Then
      Response.Write "popup.mensaje=Error, envíelo mas tarde " & Err.Description
Else
		Response.Write  "popup.mensaje=Mensaje enviado correctamente."
End If

'Destruimos el objeto con aspmail
Set Mail = Nothing

%>
            
           








