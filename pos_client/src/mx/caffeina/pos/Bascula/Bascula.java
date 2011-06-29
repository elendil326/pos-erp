package mx.caffeina.pos.Bascula;

import java.util.List;

import giovynet.nativelink.SerialPort;
import giovynet.serial.Baud;
import giovynet.serial.Com;
import giovynet.serial.Parameters;
import mx.caffeina.pos.Logger;

public class Bascula{
	
	
	private SerialPort serialPort ;
	private List<String> portsFree;
	private Com com;


	/**
	* 
	* 
	* */
	public Bascula() throws UnsatisfiedLinkError, Exception {
		
		Logger.log("Instanciando bascula !");
		
		serialPort = new SerialPort();
		portsFree = serialPort.getFreeSerialPort();
		
		// If there are free ports, use the first found. 
        if ( !(portsFree != null && portsFree.size() > 0) ) {
			Logger.warn("No Free ports!!!");
			return;
		}
	
		for (String free : portsFree) {
			Logger.log("Free port: "+free);
		}
			
		/****Open the port.****/
        Parameters parameters = null;
       	parameters = new Parameters();			

		parameters.setPort(portsFree.get(0));
		parameters.setBaudRate(Baud._9600);
		parameters.setByteSize("8");
		parameters.setParity("N");
		parameters.setStopBits("1");
		//parameters.setMinDelayWrite(1250);
		
		Logger.log("Open port: " + portsFree.get(0));
		com = new Com(parameters);			

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
			Logger.error(e);
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
			Logger.error(e);
			return;
		}
	}



}//class Bascula