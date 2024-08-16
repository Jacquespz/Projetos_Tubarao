<%@LANGUAGE="VBSCRIPT"%>;
  <%
  response.Expires=-1000
  response.redirect?(request.QueryString?("pagina"))
  %> 
