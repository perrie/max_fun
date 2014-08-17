package com.maxParser.CharDis;

import java.util.ArrayList;
import java.util.HashMap;
import java.util.Set;

public class CharJumbo {
	
	private ArrayList<String> chars;
	private HashMap<Integer, ArrayList<Integer>> entries;
	private HashMap<Integer, HashMap<String, Integer>> possNames;
	private double thres;
	private static int totalChars = 0;
	
	public CharJumbo(double t) {
		chars = new ArrayList<String>();
		entries = new HashMap<Integer, ArrayList<Integer>>();
		possNames = new HashMap<Integer, HashMap<String, Integer>>();
		thres = t;
	}
	public HashMap<Integer, ArrayList<Integer>> getEntries() {
		return entries;
	}
	
	public void addChar(String newChar, int entry_id) {
		
		newChar = newChar.toLowerCase();
		int char_id = -1;
		if (chars.size() == 0 || (char_id = simScore(newChar)) == -1)
			char_id = addCharOff(newChar);
		
		entries.get(char_id).add(entry_id);
		
		if (possNames.get(char_id).get(newChar) == null)
			possNames.get(char_id).put(newChar, 1);
		else
			possNames.get(char_id).put(newChar,
					possNames.get(char_id).get(newChar) + 1);
		
	}

	private int addCharOff(String newChar) {
		int id = chars.size();
		chars.add(newChar);
		entries.put(id, new ArrayList<Integer>());
		possNames.put(id, new HashMap<String, Integer>());
		totalChars++;
		return id;
	}

	private int simScore(String newChar) {
		for (int s = 0; s < chars.size(); s++) {
			
			// go through possible names
			ArrayList<String> names = (ArrayList<String>) possNames.get(s).keySet();
			for (int n = 0; n < names.size(); n++) {
				
				// equal match
				if (newChar.equals(chars.get(s)))
					return s;
				
				
				if (ngramSim(3, newChar, names.get(n)) 
						> thres * Math.min(newChar.length(), 
								names.get(n).length()))
					return s;
			}			
		}
		return -1;
	}

	private double ngramSim(int ngram, String newChar, String other) {
		int counter = 0;
		if (ngram < newChar.length())
			ngram = newChar.length();
		ArrayList<String> newCharGram = new ArrayList<String>();
		for (int i = 0; i < newChar.length() - ngram; i++)
			newCharGram.add(newChar.substring(i, i + ngram));
		for (int i = 0; i < other.length() - ngram; i++)
			if (newCharGram.contains(other.substring(i, i + 3)))
				counter++;
		return counter;
	}
	
	

}
