package com.maxParser.tableClass;

public class Row {
	
	public static final int _USER = 0;
	public static final int _ROUND = 1;
	public static final int _ENTRY = 2;
	public static final int _CHARACTER = 3;
	public static final int _CHAR_ENTRY = 4;
	public static final int _NUM_TYPES = 5;
	
	public static final String[] NAME_PARTS = {
		"User", "Round", "Entry",
		"Character", "Char_Entry"
	};
	public static final Class[][] TYPE_PARTS = {
		{Integer.class, String.class, Integer.class, Integer.class},
		{Integer.class, String.class, String.class, 
			String.class, Integer.class},
		{Integer.class, Integer.class, Integer.class, Integer.class,
			String.class, String.class, String.class, String.class,
			Integer.class, Integer.class, String.class, String.class,
			String.class, String.class},
		{Integer.class, String.class, Integer.class},
		{Integer.class, Integer.class, Integer.class}
		
	};	
	public static final String[][] TITLE_PARTS = {
		{"id", "name", "num_recu", "num_give"},
		{"id", "name", "start", "end", "num_users"},
		{"id", "recu_id", "give_id", "round_id", "date", "title",
			"description", "characters", "char_corr", "num_char", 
			"img_url", "recu_name", "give_name", "round_name"},
		{"id", "name", "user_id"},
		{"id", "entry_id", "char_id"}
	};
	
	public static final String NO_IMAGE_FILE = "no image file";
	
	
	// Instance Variables
	private int type;
	private String[] values;
	// Constructor
	public Row(int type) {
		this.type = type;
		values = new String[TITLE_PARTS[type].length];
		for (int i = 0; i < values.length; i++)
			values[i] = "";
	}
	public Row(int type2, String line) {
		this.type = type2;
		values = new String[TITLE_PARTS[type].length];
		String[] val = line.split("\t");
		if (val.length < values.length) {
			for (int i = 0; i < val.length; i++) {
				System.out.println(i + ") " + val[i]);
			}
			System.out.println("Input is line (" + line + ") is too small: "
					+ val.length + " < " + values.length);
			System.exit(0);
		}
		for (int i = 0; i < values.length; i++)
			values[i] = val[i];
	}
	public Row(int type, String[] para) {
		this.type = type;
		values = new String[TITLE_PARTS[type].length];
		setValues(para);
	}
	// Fill values
	public void setValues(String[] para) {
		if (para.length < values.length) {
			System.out.println("Para length is too small");
			System.exit(0);
		}
		for (int i = 0; i < values.length; i++) 
				values[i] = para[i];
	}
	// toString
	public String toString() {
		StringBuilder retStr = new StringBuilder();
		for (int i = 0; i < values.length; i++)
			retStr.append(TITLE_PARTS[type][i] + " = "
				+ values[i] + "\n");
		return new String(retStr);
	}
	public String tabs() {
		StringBuilder retStr = new StringBuilder();
		for (int i = 0; i <values.length-1; i++)
			retStr.append(values[i] + "\t");
		retStr.append(values[values.length-1]);
		return new String(retStr);
	}
	public void inc(String string) {
		int index = -1;
		if ((index = this.getColumnIndex(string)) == -1)
			return;
		values[index] = "" + (Integer.parseInt(values[index]) + 1);
		//System.out.println("Updated values: " + values[index]);
		
	}
	public String getValue(int i) {
		return values[i];
	}
	public int getColumnIndex(String string) {
		for (int i = 0; i < TITLE_PARTS[type].length; i++) {
			if (TITLE_PARTS[type][i].equals(string))
				return i;
		}
		return -1;
	}
	
}
