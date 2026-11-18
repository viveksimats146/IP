
import java.io.*;
import javax.servlet.*;
import javax.servlet.http.*;
import javax.servlet.annotation.WebServlet;

@WebServlet("/JobServlet")
public class JobServlet extends HttpServlet {

    protected void doPost(HttpServletRequest request,
    HttpServletResponse response)
    throws ServletException, IOException {

        response.setContentType("text/html");
        PrintWriter out = response.getWriter();

        String name = request.getParameter("name");
        String email = request.getParameter("email");
        String qualification = request.getParameter("qualification");
        String job = request.getParameter("job");

        out.println("<html><body>");
        out.println("<h2>Job Application Submitted</h2>");
        out.println("Name: " + name + "<br>");
        out.println("Email: " + email + "<br>");
        out.println("Qualification: " + qualification + "<br>");
        out.println("Job Role: " + job + "<br>");
        out.println("</body></html>");
    }
}
