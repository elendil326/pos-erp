/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package printer;

import java.awt.print.Book;
import java.awt.print.PageFormat;
import java.awt.print.Paper;
import java.awt.print.PrinterException;
import java.awt.print.PrinterJob;
import java.io.FileInputStream;
import java.io.FileNotFoundException;
import java.io.IOException;
import java.io.InputStream;
import java.nio.ByteBuffer;

import com.sun.pdfview.PDFFile;
import java.io.File;
import javax.print.PrintService;
import javax.print.PrintServiceLookup;

/**
 *
 * @author Manuel
 */
public class ServidorImpresion {

    /**
     *
     */
    static boolean debug = false;
    /**
     * Trabajo de impresion
     */
    private PrinterJob pjob = null;
    /**
     * Nombre de la impresora con la cual se va a imprimir el pdf
     */
    private String printer = null;
    /**
     * Path del archivo que se quiere imprimir
     */
    private String file_path = null;

    /**
     *
     * @param file path del archivo que se quiere imprimir
     * @param printer nombre de la impresora con la cual se imprimira este documento
     */
    public ServidorImpresion(String file, String printer) throws PrinterException, FileNotFoundException, IOException {

        this.file_path = file;

        this.printer = printer;

        File f = new File(this.file_path);

        System.out.println("Imprimiendo : " + f.getName());

        PrintPdf(new FileInputStream(f.getPath()), f.getName());

    }

    /**
     * Construye el trabajo de impresión basado en input stream
     *
     * @param inputStream
     * @param jobName
     * @throws IOException
     * @throws PrinterException
     */
    private void PrintPdf(InputStream inputStream, String jobName) throws IOException, PrinterException {
        byte[] pdfContent = new byte[inputStream.available()];
        inputStream.read(pdfContent, 0, inputStream.available());
        initialize(pdfContent, jobName);
    }

    /**
     * CConstruye el trabajo de impresión basado en el contenido de matriz de bytes
     *
     * @param content
     * @param jobName
     * @throws IOException
     * @throws PrinterException
     */
    private void PrintPdf(byte[] content, String jobName) throws IOException, PrinterException {
        initialize(content, jobName);
    }

    /**
     * Inicializa el trabajo a imprimir
     * @param pdfContent
     * @param jobName
     * @throws IOException
     * @throws PrinterException
     */
    private void initialize(byte[] pdfContent, String jobName) throws IOException, PrinterException {

        ByteBuffer bb = ByteBuffer.wrap(pdfContent);

        // Crea PDF Print Page
        PDFFile pdfFile = new PDFFile(bb);
        PDFPrintPage pages = new PDFPrintPage(pdfFile);

        // Crea el Print Job
        pjob = PrinterJob.getPrinterJob();
        PageFormat pf = PrinterJob.getPrinterJob().defaultPage();
        pjob.setJobName(jobName);
        Book book = new Book();
        book.append(pages, pf, pdfFile.getNumPages());
        pjob.setPageable(book);

        // quita los margenes
        Paper paper = new Paper();
        paper.setImageableArea(0, 0, paper.getWidth(), paper.getHeight());
        pf.setPaper(paper);

        //verificamos que se encuentre a la impresora con la cual se va a imprimir
        PrintService[] printServices = PrintServiceLookup.lookupPrintServices(null, null);
        PrintService selectedService = null;
        for (int i = 0; i < printServices.length; i++) {
            if (printServices[i].getName().equals(this.printer)) {
                selectedService = printServices[i];
                break;
            }
        }

        if (selectedService != null) {

            this.pjob.setPrintService(selectedService);

            try {
                this.pjob.print();
                //System.exit(0);
            } catch (PrinterException exception) {
                System.err.println("La impresora no esta recibiendo documentos, información : " + exception + ".");
            }

        } else {
            System.err.print("Error : No se tiene disponible la impresora : " + this.printer);
        }

    }
}

