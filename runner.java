import java.io.IOException;
/**
 * Created by benvo_000 on 24/3/2559.
 */
public class runner {
    public static void main(String[] args) throws IOException, InterruptedException {
        while(true){
            Runtime rt = Runtime.getRuntime();
            rt.exec("php artisan schedule:run >> logg.txt");
	    System.out.println("running");
            Thread.sleep(60000);
        }
    }
}
