from mysql.connector import(connection)

mydb = connection.MySQLConnection(
	user="root",
	password="",
	host="localhost",
    database="pensamientos"
	)

mycursor = mydb.cursor()

mycursor.execute("CREATE TABLE THOUGHTS(country_id  int(11) NOT NULL AUTO_INCREMENT,content VARCHAR(280),FOREIGN KEY(country_id) REFERENCES country(id))ENGINE=MyISAM DEFAULT CHARSET=latin1")

mydb.commit()