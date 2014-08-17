package com.maxParser.getParts;

import java.io.BufferedReader;
import java.io.IOException;
import java.io.InputStreamReader;
import java.net.URL;
import java.util.ArrayList;
import java.util.HashMap;
import java.util.Set;

import org.json.JSONException;
import org.json.JSONObject;

import com.maxParser.saveDB.SaveDB;
import com.maxParser.tableClass.Row;
import com.privateinfo.Information;

public class TableManager {
	
	public static final String[] TYPE_URLs = {
		Information.URL_USERS,
		Information.URL_ROOUNDS,
		Information.URL_ENTRIES
	};
	
	// INSTANCE
	private ArrayList<HashMap<Integer, Row>> rows;
	
	public TableManager() {

		rows = new ArrayList<HashMap<Integer,Row>>(Row._NUM_TYPES);
		for (int i = 0; i < Row._NUM_TYPES; i++)
			rows.add(new HashMap<Integer, Row>());
	}
	/**
	 * adds data to rows from reading info from url
	 * @param type type of table
	 * @param data JSONobject data
	 */
	public void addDataURL(int type, JSONObject data) {
		
		switch (type) {
			case Row._ENTRY:
			{
				/*TODO:*/ update(Row._ROUND, data.getInt("entry_round"), -1);
				/*TODO:*/ update(Row._USER, data.getInt("entry_artist"), data.getInt("entry_recipient"));
				String img_url = (data.getString("entry_approved_file").length() < 2)
						? (data.getString("entry_temp_file").length() < 2)
								? Row.NO_IMAGE_FILE
								: data.getString("entry_temp_file")
						: data.getString("entry_approved_file");
				String[] values = {
						data.getInt("entry_id") + "",
						data.getInt("entry_recipient") + "", 
						data.getInt("entry_artist") + "",
						data.getInt("entry_round") + "", 
						data.getString("entry_submitted_date"),
						unEscapeString(data.getString("entry_title")),
						unEscapeString(data.getString("entry_description")),
						unEscapeString(data.getString("entry_character")),
						"-1",
						"0",
						img_url,
						rows.get(Row._USER).get(data.getInt("entry_recipient")).getValue(1),
						rows.get(Row._USER).get(data.getInt("entry_artist")).getValue(1),
						rows.get(Row._ROUND).get(data.getInt("entry_round")).getValue(1)
				};
				rows.get(type).put(data.getInt("entry_id"), 
						new Row(type, values));
				//rows.get(type).get(data.getInt("entry_recipient")).addEntry(data.getInt("entry_id"));
				
				
				break;
			}
			case Row._ROUND: 
			{
				String[] values = {
						data.getInt("round_id") + "",
						data.getString("round_name"), 
						data.getString("round_start_date"),
						data.getString("round_end_date"),
						"0",
						"0"
				};
				rows.get(type).put(data.getInt("round_id"),
						new Row(type, values));
				break;
			}
			case Row._USER:
	    		// Make sure URL exits-- good enough to add
	    		if (data.getString("user_max_url").length() > 0
	    				|| data.getString("user_max_characters").length() > 0) {
	    			String[] values = {
	    					data.getInt("user_id") + "",
	    					data.getString("username"),
	    					"0",
	    					"0"};
	    			rows.get(type).put(data.getInt("user_id"),
	    					new Row(type, values));
	    		}
	    		break;
	    	default:
	    		defaultExit("addData - JSONObject");
		}
		
	}
	/**
	 * Increment the additional values we're incrementing
	 * @param type type of table we're looking at
	 * @param id main id (only id for ROUND)
	 * @param id2 secondary id (for USER)
	 */
	private void update(int type, int id, int id2) {
		switch (type) {
		case Row._ROUND:
			if (rows.get(type).get(id) == null)
				rows.get(type).put(id,  new Row(type, id + "\tUnknown Round "
						+ id + "\t?\t?\t0"));
			rows.get(type).get(id).inc("num_users");
			break;
		case Row._USER:
			if (rows.get(type).get(id) == null)
				rows.get(type).put(id, resolveUser(id));
			if (rows.get(type).get(id2) == null)
				rows.get(type).put(id2, resolveUser(id2));
								
			rows.get(type).get(id).inc("num_give");
			rows.get(type).get(id2).inc("num_recu");
			break;
		default:
			defaultExit("update");
		}
	}

	/**
	 * (Attempts to) grab username if id is not recognised
	 * @param id user id
	 * @return new Row with user parameters set
	 */
	private Row resolveUser(int id) {
		
		String username = "Unknown User " + id;
        try {		
			URL oracle = new URL("http://comicdish.com:1080/api/v1/users/" + id);
	        BufferedReader in = new BufferedReader(
	        		new InputStreamReader(oracle.openStream()));
	        String inputLine;
	        
	        // Save response in list
	        if ((inputLine = in.readLine()) != null) {
			    //System.out.println(inputLine);
			    JSONObject obj = new JSONObject(inputLine);
			    if (obj.has("username"))
			    	username = obj.getString("username");
			}
		} catch (JSONException | IOException e) {
			System.err.println(e.getMessage());
			//e.printStackTrace();
		}
		return 	new Row(Row._USER, id + "\t" + username + "\t0\t0");
	}
	/**
	 * Add data from reading in a tab delimited line
	 * @param type type of table
	 * @param line the tab-delimited line
	 */
	public void addDataTabs(int type, String line) {

		int id = Integer.parseInt(line.substring(0,line.indexOf("\t")));
		if (type < 0 || type >= Row._NUM_TYPES) {
			defaultExit("addData - line");
			return;
		}
		Row input = new Row(type, line);
		rows.get(type).put(id, input);
	}
	/**
	 * Return string of tabbed table data for output
	 * @param type type of table
	 * @return string containing tabbed data
	 */
	public String getTabsOutput(int type) {
		StringBuilder retStr = new StringBuilder("");	
		if (type < 0 || type >= rows.size()) {
			defaultExit("size");
			return "";
		}
		for (Row r : rows.get(type).values())
			retStr.append(r.tabs() + "\n");
		return new String(retStr);
	
	}
	/**
	 * Keys from a certain table
	 * @param type type of table
	 * @return Set of keys
	 */
	public Set<Integer> keys(int type) {
		if (type < 0 || type > rows.size()) {
			defaultExit("keys");
			return null;
		}
		return rows.get(type).keySet();
	}
	/**
	 * Returns number of entries in the table
	 * @param type type of table
	 * @return number of entries
	 */
	public int size(int type) {
		if (type < 0 || type >= rows.size()) {
			defaultExit("size");
			return -1;
		}
		return rows.get(type).size();
	}
	/**
	 * Prints the type of the type...?
	 * @param type table of table
	 */
	public void printType(int type) {
		if (type < 0 || type >= Row._NUM_TYPES)
			defaultExit("printType");
		System.out.println(rows.get(type));
	}
	/**
	 * Returns the url for the api to get the data for a certain type of 
	 * table
	 * @param type type of table
	 * @return url to get data
	 */
	public String getURL(int type) {
		if (type < 0 || type > Row._ENTRY) {
			System.out.println("Unknown url for type: " + type);
			return null;
		} else
			return TYPE_URLs[type];
	}
	/**
	 * To be called when an error occurs
	 * @param defn error type
	 */
	private void defaultExit(String defn) {
		System.out.println("BAD SWITCH:" + defn);
		System.exit(0);
	}
/*
	public void processCharacters() {
		
		Set<Integer> user_ids = users.keySet();
		for (Integer user_id : user_ids) {
			
			System.out.println("UseR:id: " + user_id);
			
			ArrayList<Integer> ents = users.get(user_id).getEntries();
			if (ents == null) continue;
			System.out.println( "ENTRY NUM: " + users.get(user_id)
					.getEntries().size());
			ArrayList<String> chars = new ArrayList<String>();
			
			for (Integer ent : ents) {
				
				System.out.print("\t" + ent);
				
				String chars_str = entries.get(ent).getCharacters()
						.replaceAll(" and ", ",");
				String char_parts[] = chars_str.split("[,\\/&]");
				for (int k = 0; k < char_parts.length; k++) {
					System.out.println("\t\t" + char_parts[k] + " from " + chars_str);
					
					if (chars.size() > 0) {
						boolean not_seen = true;
						for (int c = 0; c < chars.size() && not_seen; c++) {
							double score = charSimScore(char_parts[k], chars.get(k));
							// Char is encountered before
							if (score > 0.5)
								not_seen = false;
						if (not_seen)
							chars.add(char_parts[k]);
							
						}
					} else
						chars.add(char_parts[k]);
						
			//	}
			}
			System.out.println();
			System.out.println("Char: " + chars);
		}
		
		
	}

	private double charSimScore(String string, String string2) {
		if (string.toLowerCase().equals(string2.toLowerCase()))
			return 1.0;
		
		return 0;
	}
*/
	// Source: http://stackoverflow.com/questions/7888004/how-do-i-print-escape-characters-in-java
	public static String unEscapeString(String s){
		    StringBuilder sb = new StringBuilder();
		    for (int i=0; i<s.length(); i++)
		        switch (s.charAt(i)){
		            case '\n': sb.append("<br />"); break;
		            case '\t': sb.append("&nbsp;&nbsp;&nbsp;&nbsp;"); break;
		            case '\r': sb.append(""); break;
		            // ... rest of escape characters
		            default: sb.append(s.charAt(i));
		        }
		    return sb.toString();
		}
	/**
	 * Connects to database and saves information in appropriate tables
	 * @param addthese the list of ids of tables that should be included in the insers
	 * @return if the addition went well
	 */
	public boolean databasePart(StringBuilder addthese) {
		SaveDB db = new SaveDB(true, "jdbc:mysql://localhost:3306/" + Information.SCHEMA, 
				Information.USER, Information.PASS);
		String[] partsDo = (new String(addthese)).split(",");
		for (int i = 0; i < partsDo.length; i++) {
			System.out.println("Processing..." + Row.NAME_PARTS[i]);
			if (!db.insert(i, rows.get(i)))
				return false;
		}
		db.close();
		return true;
	}
	/**
	 * Returns the value of a certain type of table, given a certain
	 * ID (the key id in the table), and the column name. Checks that 
	 * column is round before returning
	 * @param type type (e.g., Row._ENTRY)
	 * @param id (e.g., 6212) of entry
	 * @param column name (e.g., img_url)
	 * @return String Value
	 */
	public String getValueAt(int type, int id, String column) {
		if (type < 0 || type >= Row._NUM_TYPES) {
			defaultExit("getValueAt");
		}
		int colID = -1;
		HashMap<Integer,Row> entries = rows.get(type);
		if ((colID = entries.get(id).getColumnIndex(column)) == -1) {
			System.out.println("column type is not supported in this type: "
					+ column + " in " + Row.NAME_PARTS[type]);
		}
		return entries.get(id).getValue(colID);
		
		
			
	}
}
