
import java.io.*;
import javax.servlet.*;
import javax.servlet.http.*;
import javax.servlet.annotation.WebServlet;

@WebServlet("/BreakdownServlet")
public class BreakdownServlet extends HttpServlet {

    protected void doPost(HttpServletRequest request,
    HttpServletResponse response)
    throws ServletException, IOException {

        response.setContentType("text/html");
        PrintWriter out = response.getWriter();

        String name = request.getParameter("name");
        String vehicle = request.getParameter("vehicle");
        String location = request.getParameter("location");
        String problem = request.getParameter("problem");

        out.println("<html><body>");
        out.println("<h2>Assistance Request Submitted</h2>");
        out.println("Customer Name: " + name + "<br>");
        out.println("Vehicle Number: " + vehicle + "<br>");
        out.println("Location: " + location + "<br>");
        out.println("Problem: " + problem + "<br>");
        out.println("</body></html>");
    }
}
