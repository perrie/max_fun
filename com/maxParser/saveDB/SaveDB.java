package com.maxParser.saveDB;

//STEP 1. Import required packages
import java.sql.*;
import java.util.HashMap;

import javax.sql.rowset.Predicate;

import com.maxParser.tableClass.Row;


//SOURCE: http://www.tutorialspoint.com/jdbc/jdbc-introduction.htm

public class SaveDB {
// JDBC driver name and database URL
static final String JDBC_DRIVER = "com.mysql.jdbc.Driver";

// Instant variables
private Connection conn = null;
private PreparedStatement[] stmts;
private int numTypes;


// Constructor
/** 
* Sets up database connection and this saving database instance
* @param setConnect if a connection should intentially be set
* @param DB_URL database host address
* @param USER user's name for access
* @param PASS user's password for access
*/
public SaveDB(boolean setConnect, String DB_URL, String USER, String PASS) {
	
	numTypes = 3;
	System.out.println("NUMBER OF SUPPORTED TABLES...");
	for (int i = 0 ; i < numTypes; i++)
		System.out.println("TABLE: " + Row.NAME_PARTS[i]);
	stmts = new PreparedStatement[numTypes];

 if (setConnect) {
   try {
     // STEP 2: Register JDBC driver
     Class.forName("com.mysql.jdbc.Driver");

     // STEP 3: Open a connection
     System.out.println("Connecting to a selected database...");
     conn = DriverManager.getConnection(DB_URL, USER, PASS);
     System.out.println("Connected database successfully...");
     System.out.println("Setting up sql statments");
     createStmts();
     System.out.println("Statements set up correctly...");
   } catch (SQLException se) {
     // Handle errors for JDBC
 	System.err.println("SaveDB:SaveDB" + "\t" +  "User: " + USER + "\nPASS: " + PASS);
     System.err.println("Gen:SaveDB:constructor" + "\t" +  "Unable to read in document for "
         + "some reason (" + se.getClass().getName() + ": " + se.getMessage() + ")");
     this.close();
   } catch (Exception e) {
     // Handle errors for Class.forName
     System.err.println("Gen:SaveDB:constructor" + "\t" + "Unable to read in document for "
         + "some reason (" + e.getClass().getName() + ": " + e.getMessage() + ")");
     this.close();
   }
 }
}

// Close all connections
/**
* Closes the database connection
*/
public void close() {
 if (conn == null)
   return;

 System.out.println("Closing Database connection");
 // finally block used to close resources
 try {
	boolean close = false;
	for (int i = 0; i < numTypes && !close; i++)
		if (stmts[i] != null)
			close = true;
	if (close)
     conn.close();
 } catch (SQLException se) {
 }// do nothing
 try {
   if (conn != null)
     conn.close();
 } catch (SQLException se) {
   System.err.println("Gen:SaveDB:close" + "\t" + "Unable to read in document for "
       + "some reason (" + se.getClass().getName() + ": " + se.getMessage() + ")");
 }// end finally try
}

// Check if connection is set
/** 
* Checks if the database connection is set up
* @return boolean indicating that the connection has been set
*/
public boolean connectionSet() {
 return (conn != null);
}

// Create statements
/**
* Creates variable-safe mySql statements
* @return boolean indicating that the statement has been set up correctly
*/
public boolean createStmts() {
 if (!connectionSet())
   return false;
 
 for (int type = 0; type < numTypes; type++) {
	 int numVal = Row.TITLE_PARTS[type].length;
	 StringBuilder sql = new StringBuilder();
	 sql.append("INSERT IGNORE INTO " + Row.NAME_PARTS[type].toLowerCase() + " VALUES (");
	 for (int j = 0; j < numVal; j ++)
		 sql.append((j == numVal - 1) ? "?" : "?,");
	 sql.append(")");
	 try {
		   stmts[type] = conn.prepareStatement(new String(sql));
		 } catch (SQLException e1) {
		   System.err.println("Gen:SaveDB:createStmts" + "\t" + 
		       "Unable to set up DB connection for statement for " + Row.NAME_PARTS[type] 
		    		   + " (" + e1.getClass().getName() + ")");
		   return false;
		 } 
 }
 return true;
}

// Run query
/**
* Fills safe query with Row detail's and inserts it into the database
* @param details the data to be inserted into the database
* @return boolean indicating that the data has been successfully inserted
*/
public boolean insert(int type, HashMap<Integer, Row> details) {

 if (details.isEmpty())
   return false;

 // Make sure that connection is open
 if (!this.connectionSet())
   return false;

 // STEP 4: Execute a query
 int i = 0;
 PreparedStatement stmt = stmts[type];
 if (stmt == null) {
   System.err.println("Gen:SaveDB:insert" + "\t" + "Unknown table type");
   return false;
 }
 // For each id/entry
 Object[] orderedKeys = details.keySet().toArray();
 for (i = 0; i < orderedKeys.length; i++) {

   try {
     // For each part
     if (fillStmt(stmt, details.get(orderedKeys[i]), type))
    	stmt.executeUpdate();

     // Catch exceptions
   } catch (SQLException e) {
     if (e.getClass().getName()
         .equals("com.mysql.jdbc.exceptions.jdbc4.MySQLIntegrityConstraintViolationException")) {
       System.err.println(orderedKeys[i]+ ":SaveDB:insert" + "\t" +
           "Duplicate ID: Primary Key exists in " + Row.NAME_PARTS[type] + "; line " + i);
     } else {
       System.err.println(orderedKeys[i] + ":SaveDB:insert" + "\t" +
           "Something wrong in " + Row.NAME_PARTS[type] + "; line " + i + " ("
               + e.getClass().getName() + ": " + e.getMessage() + ")");
     }
     System.err.println("Details in line: " + details.get(orderedKeys[i]).toString());

   }
 }

 return true;
}

// print query
/**
* Prints query part to a output file using the PrintUtils function
* @param details the data to be saved in the output file
* @return boolean indicating that the data has been successfully inserted
*/
/*
public boolean print(Row details) {

 if (details.isEmpty())
   return false;

 // Make sure that connection is open
 if (!this.connectionSet())
   return false;

 // STEP 4: Execute a query
 PreparedStatement stmt =
     (details.getType().equals(CONTS.TABLE_REF)) ? stmtRef : (details.getType()
         .equals(CONTS.TABLE_CIT)) ? stmtCit : null;
 if (stmt == null) {
   return System.err.println("Gen:SaveDB:print" + "\t" + "Unknown table type");
 }
 // For each id/entry
 for (int i = 0; i < details.getNumID(); i++) {
   try {
     // For each part
     if (fillStmt(details, stmt, i)) {
       String stmtStr = stmt.toString();
       System.err.out(details.getType() + "\t" +  stmtStr.substring(stmtStr.indexOf("(")));
     }
     // Catch exceptions
   } catch (Exception e) {
     System.err.println(details.getKey(i) + ":SaveDB:print",
         "Something wrong in " + details.getType() + " which results in error: "
             + e.getClass().getName() + ": " + e.getMessage(), 1);
     System.err.out("Details in line: " + details.getRow(i));
     return false;
   }
 }

 return true;
}
*/
// Fill Prepared statement
/**
* Fills the prepared statement stmt with the details
* @param details the data to be added to the statement
* @param stmt the statement
 * @param type 
* @param i
* @return
*/
public boolean fillStmt(PreparedStatement stmt, Row details, int type) {
 int numVal = Row.TITLE_PARTS[type].length;
 for (int j = 0; j < numVal; j++) {
   try {
	      
     if (Row.TYPE_PARTS[type][j] == String.class)
    	 stmt.setString(j+1, details.getValue(j));
     else if (Row.TYPE_PARTS[type][j] == Integer.class)
    	 stmt.setInt(j+1, Integer.parseInt(details.getValue(j)));
     
   } catch (NumberFormatException e) {
     System.err.println(details.getValue(0) + "SaveDB:fillStmt" + "\t" + ": "
         + "Error with setting up part " + details.getValue(0) + " resulting in "
         + e.getClass().getName() + ": " + e.getMessage());
     return false;
   } catch (SQLException e) {
     System.err.println(details.getValue(0) + "SaveDB:fillStmt"+ "\t" +
         "Error with setting up part " + details.getValue(0) + " resulting in "
             + e.getClass().getName() + ": " + e.getMessage());
     return false;
   }
 }
 return true;
}

}// end JDBCExample

