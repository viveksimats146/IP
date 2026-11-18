
import java.io.*;
import javax.servlet.*;
import javax.servlet.http.*;
import javax.servlet.annotation.WebServlet;

@WebServlet("/TravelServlet")
public class TravelServlet extends HttpServlet {

    protected void doPost(HttpServletRequest request,
    HttpServletResponse response)
    throws ServletException, IOException {

        response.setContentType("text/html");
        PrintWriter out = response.getWriter();

        String name = request.getParameter("name");
        String destination = request.getParameter("destination");
        String date = request.getParameter("date");

        out.println("<html><body>");
        out.println("<h2>Travel Booking Successful</h2>");
        out.println("Customer Name: " + name + "<br>");
        out.println("Destination: " + destination + "<br>");
        out.println("Travel Date: " + date + "<br>");
        out.println("</body></html>");
    }
}
