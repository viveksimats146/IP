
import java.io.*;
import javax.servlet.*;
import javax.servlet.http.*;
import javax.servlet.annotation.WebServlet;

@WebServlet("/WasteServlet")
public class WasteServlet extends HttpServlet {

    protected void doPost(HttpServletRequest request,
    HttpServletResponse response)
    throws ServletException, IOException {

        response.setContentType("text/html");
        PrintWriter out = response.getWriter();

        String name = request.getParameter("name");
        String address = request.getParameter("address");
        String waste = request.getParameter("waste");

        out.println("<html><body>");
        out.println("<h2>Waste Collection Request Submitted</h2>");
        out.println("Name: " + name + "<br>");
        out.println("Address: " + address + "<br>");
        out.println("Waste Type: " + waste + "<br>");
        out.println("</body></html>");
    }
}
