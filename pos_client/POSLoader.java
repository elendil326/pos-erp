class POSLoader{
	

	public static int runPOS()
	{
		Runtime r = Runtime.getRuntime();
		Process p = null;

		String cmd = "java -jar posClient.jar" ;

		try {
			p = r.exec(cmd);
			p.waitFor();

		} catch (Exception e) {

			return 1;
		}

		
		return p.exitValue();
	}





	public static void main(String args[]) 
	{

		int ret = 1;
		while(ret > 0)
		{
						
			
			System.out.println("corriendo...");
			ret = runPOS();
			System.out.println("POS ENDED WITH " + ret);
		}
  	}

}

