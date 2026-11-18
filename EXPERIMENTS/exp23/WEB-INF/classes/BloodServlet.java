
import java.io.*;
import javax.servlet.*;
import javax.servlet.http.*;
import javax.servlet.annotation.WebServlet;

@WebServlet("/BloodServlet")
public class BloodServlet extends HttpServlet {

    protected void doPost(HttpServletRequest request,
    HttpServletResponse response)
    throws ServletException, IOException {

        response.setContentType("text/html");
        PrintWriter out = response.getWriter();

        String name = request.getParameter("name");
        String age = request.getParameter("age");
        String group = request.getParameter("group");
        String phone = request.getParameter("phone");

        out.println("<html><body>");
        out.println("<h2>Donor Registered Successfully</h2>");
        out.println("Name: " + name + "<br>");
        out.println("Age: " + age + "<br>");
        out.println("Blood Group: " + group + "<br>");
        out.println("Phone: " + phone + "<br>");
        out.println("</body></html>");
    }
}
