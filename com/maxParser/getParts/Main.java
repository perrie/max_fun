package com.maxParser.getParts;

import com.maxParser.tableClass.Row;

public class Main {
	
	public static void main(String[] args) {
		Grabber grab = new Grabber();
		StringBuilder parts = new StringBuilder();
		grab.addToThese(parts, Row._ROUND);
		grab.addToThese(parts, Row._USER);
		grab.addToThese(parts, Row._ENTRY);
		grab.fillParts(parts);
		
		// Save to database
	//	grab.databasePart(parts);
		
		// Save to file
	//	grab.saveParts(parts);
		
		// Download images
		grab.getImages("images",100, 100);
	}
	
	

}
