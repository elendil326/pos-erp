package mx.caffeina.pos.Bascula;

import java.util.List;

import giovynet.nativelink.SerialPort;
import giovynet.serial.Baud;
import giovynet.serial.Com;
import giovynet.serial.Parameters;


public class Bascula{
	
	
	private SerialPort serialPort ;
	private List<String> portsFree;
	private Com com;


	/**
	* 
	* 
	* */
	public Bascula(){
		
		System.out.println("Instanciando bascula !");
		
		serialPort = new SerialPort();
		try{
			portsFree = serialPort.getFreeSerialPort();
		}catch(java.lang.UnsatisfiedLinkError usle){
			System.out.println("Imposible cargar las librerias de puerto serial !");
			return;
			
		}catch(Exception e){
			System.out.println(e);
			return;
		}
		
		// If there are free ports, use the first found. 
        if ( !(portsFree != null && portsFree.size() > 0) ) {
			System.out.println("No Free ports!!!");
		}
	
		for (String free : portsFree) {
			System.out.println("Free port: "+free);
		}
			
		/****Open the port.****/
        Parameters parameters = null;
		try{
        	parameters = new Parameters();			
		}catch(Exception e){
			System.out.println(e);
			return;
		}

		parameters.setPort(portsFree.get(0));
		parameters.setBaudRate(Baud._9600);
		parameters.setByteSize("8");
		parameters.setParity("N");
		parameters.setStopBits("1");
		//parameters.setMinDelayWrite(1250);
		
		System.out.println("Open port: " + portsFree.get(0));
		try{
			com = new Com(parameters);			
		}catch(Exception e){
			System.out.println(e);
			return;
		}

	}
	
	
	/**
	* 
	* 
	* */
	public String getRawData(){
		return getRawData(13);
	}
	
	
	/**
	* 
	* 
	* */
	public String getRawData(int bytes){
		try{
			return com.receiveToString(bytes);			
		}catch(Exception e){
			System.out.println(e);
			return null;
		}

	}
	
	
	/**
	* 
	* 
	* */	
	public void close(){
		try{
			com.close();
		}catch(Exception e){
			System.out.println(e);
			return;
		}
	}



}//class Bascula