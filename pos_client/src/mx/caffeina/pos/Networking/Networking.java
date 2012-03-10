package mx.caffeina.pos.Networking;

import java.net.InetAddress;
import java.net.NetworkInterface;
import java.net.SocketException;
import java.net.UnknownHostException;
import mx.caffeina.pos.Logger;

public class Networking{
	
 	public static String getMacAddd( ) {
        try {
            InetAddress address = InetAddress.getLocalHost();
            //InetAddress address = InetAddress.getByName("192.168.46.53");

            /*
             * Get NetworkInterface for the current host and then read the
             * hardware address.
             */
            NetworkInterface ni = NetworkInterface.getByInetAddress(address);
	    
            if (ni != null) {
                byte[] mac = ni.getHardwareAddress();
                if (mac != null) {
                    /*
                     * Extract each array of mac address and convert it to hexa with the
                     * following format 08-00-27-DC-4A-9E.
                     */
					String mac_add = "";
                    for (int i = 0; i < mac.length; i++) {
                        mac_add += String.format("%02X%s", mac[i], (i < mac.length - 1) ? "-" : "");
                    }

					return mac_add;
					
                } else {
                    Logger.warn("Address doesn't exist or is not accessible.");
		    return "Address doesn't exist or is not accessible.";
                }
            } else {
                Logger.warn("Network Interface for the specified address is not found.");
		return "Network Interface for the specified address is not found.";
            }
        } catch (UnknownHostException e) {

            Logger.error(e);
		return e.getMessage();
        } catch (SocketException e) {
            Logger.error(e);
		return e.getMessage();
        }
    }



}//class Networking
