package com.maxParser.getParts;
import java.awt.Graphics2D;
import java.awt.image.BufferedImage;
import java.io.BufferedReader;
import java.io.File;
import java.io.FileNotFoundException;
import java.io.FileReader;
import java.io.IOException;
import java.io.InputStreamReader;
import java.io.PrintWriter;
import java.net.MalformedURLException;
import java.net.URL;
import java.util.Set;

import javax.imageio.ImageIO;

import org.json.JSONArray;
import org.json.JSONObject;

import com.maxParser.tableClass.Row;

public class Grabber {

	private TableManager tm;

	public static final String dEnd = ",";
	public static final String oStart = "output";
	public static final String oEnd = ".txt";
	
	public Grabber() {
		tm = new TableManager();
	}
	/**
	 * Adds different tables to be worked on
	 * @param added list of tables (ids) already selected
	 * @param add new table to add
	 */
	public void addToThese(StringBuilder added, int add) {
		added.append(add + dEnd);
	}
	/**
	 * Looks into the list of different tables to work on and
	 * grabs their values from text/url
	 * @param addthese list of tables (ids) to work on
	 */
	public void fillParts(StringBuilder addthese) {
		
		System.out.println("ADD THESE: " + addthese);
		String[] partsDo = (new String(addthese)).split(",");
		for (int i = 0; i < partsDo.length; i++) {
			int type = Integer.parseInt(partsDo[i]);
			System.out.println("Working on ... " + Row.NAME_PARTS[type]);
			File f = new File(oStart + type + oEnd);
			if(!f.exists()) 
				fillPartsFromURL(type);
			else 
				fillPartsFromFile(type);
		//	tm.printType(type);
			/*
			if (type == Row._ENTRY)
				tm.processCharacters();
				*/
		}		
	}
	/**
	 * Saves tables that are in the addthese list
	 * @param addthese list of tables (ids) that should have contents saved
	 */
	public void saveParts(StringBuilder addthese) {
		String[] partsDo = (new String(addthese)).split(",");
		for (int i = 0; i < partsDo.length; i++)
			savePart(Integer.parseInt(partsDo[i]));
		
		
	}
	/**
	 * Prints out table values to file
	 * @param type the type of table
	 */
	private void fillPartsFromFile(int type) {
		System.out.println("FILLING PARTS FROM FILE");
		BufferedReader reader;
		try {
			reader = new BufferedReader(new FileReader(oStart + type + oEnd));
			String line = null;
			while ((line = reader.readLine()) != null)
				tm.addDataTabs(type, line);
		} catch (IOException e) {
			e.printStackTrace();
		}
		System.out.println("DONE (" + tm.size(type) + " entries)");
		
	}
	/**
	 * Specifically saves each type of table to file
	 * @param type the type of table
	 */
	private void savePart(int type) {
		System.out.println("SAVING..." + Row.NAME_PARTS[type]);
		PrintWriter writer;
		int size = tm.size(type);
		try {
			if (size > 0) {
				writer = new PrintWriter(oStart + type + oEnd);
				writer.print(tm.getTabsOutput(type));
				writer.close();
			}
		} catch (FileNotFoundException e) {
			e.printStackTrace();
		}
	}
	public boolean databasePart(StringBuilder addthese) {
		return tm.databasePart(addthese);		
	}
	/**
	 * Grabs parts of table from url
	 * @param type type of table
	 */
	private void fillPartsFromURL(int type) {
		System.out.println("FILLING PARTS FROM URL");
		try {
			grabFromURL(type);
		} catch (MalformedURLException e) {
			e.printStackTrace();
		} catch (IOException e) {
			e.printStackTrace();
		}
	}
	/**
	 * Grab parts of table from url
	 * @param type type of table
	 * @throws IOException
	 * @throws MalformedURLException
	 */
	private void grabFromURL(int type) throws IOException, MalformedURLException {

		int offset = 0;
		String offsetStr = "";
		String url = tm.getURL(type);
		while (true) {
			
			System.out.println("OFFSET: " + offset + "\tSIZE: " + tm.size(type));
			//if (offset > 500)
			//	return;
			
			if (offset != 0)
				offsetStr = "?offset=" + offset;
			URL oracle = new URL(url + offsetStr);
	        BufferedReader in = new BufferedReader(
	        		new InputStreamReader(oracle.openStream()));
	        String inputLine;
	        
	        // Save response in list
	        if ((inputLine = in.readLine()) != null) {
	            //System.out.println(inputLine);
	            JSONArray array = new JSONArray(inputLine);
	            if (array.length() == 0)
	            	return;
	            
	            // Before adding, make sure check is ok
	            for (int i = 0; i < array.length(); i++) {
	            	JSONObject obj = (JSONObject) array.get(i);
	            	String[] keys = JSONObject.getNames(obj);
	            	for (int j = 0; j < keys.length; j++)
	            		if (obj.isNull(keys[j]))
	            			obj.put(keys[j],"");
	            		
	            	//System.out.println(obj.isNull("description"));
	            	tm.addDataURL(type, obj);
	            }
	            offset += array.length();
	        } else 
	        	break;
	        in.close();
	        
		}
	}
	/**
	 * Downloads and resizes images from the max site
	 * @param imgFolder 
	 * @param height height of images to be resized
	 * @param width 
	 * 
	 */
	public void getImages(String imgFolder, double maxHeight, int maxWidth)  {
		
		String url_base = "http://max.comicdish.com/gallery/";
		int type = Row._ENTRY;
		
		System.out.println("Starting getting images...");
		
		Set<Integer> entry_ids = tm.keys(type);
		// Go through each of the images
		int count =0;
		for (Integer id : entry_ids) {
			try {
				//if (count++ == 10) System.exit(0); // TODO: Unblock this catch to run for all images...
				count++;
				
				// Get original image
				String img_url = tm.getValueAt(type, id, "img_url");
				System.out.println(count + ".)\tID: " + id + "\tImage: " + img_url);
				if (img_url.equals(Row.NO_IMAGE_FILE))
					continue;
				String opFilePath = imgFolder + "/" + img_url;
				File outputfile = new File(opFilePath);
				
				if (outputfile.exists())
					continue;
				if (img_url.indexOf(" ") != -1)
					img_url = img_url.replaceAll(" ", "%20");
				//else
				//	System.exit(0);
				
				// Create original image
				BufferedImage image = ImageIO.read(new URL(
						url_base 
						+ img_url));
				
				// Calculate new height
				double orgHeight = (double) image.getHeight();
				double orgWidth = (double) image.getWidth();
				double width = (orgHeight >= orgWidth) ? orgWidth / orgHeight * maxHeight
						: maxWidth;
				double height = (orgHeight < orgWidth) ? orgHeight / orgWidth * maxWidth
						: maxHeight;
				
				// Draw resized image
				BufferedImage resized = new BufferedImage( (int) width, 
						(int) height, BufferedImage.TYPE_INT_RGB);
				Graphics2D g = resized.createGraphics();
			    g.drawImage(image, 0, 0, (int) width, (int) height, null);
			    g.dispose();
			    
			    // Save new image
			    ImageIO.write(resized, img_url.substring(img_url.lastIndexOf(".")+1), 
			    		outputfile);
			    
			    
			} catch (MalformedURLException e) {
				System.out.println("BAD URL");
				e.printStackTrace();
			} catch (IOException e) {
				System.out.println("Unable to write to file: " + imgFolder);
				e.printStackTrace();
			}
		}
		System.out.println("DONE CREATING " + count + " IMAGES");
	}
}
