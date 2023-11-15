    apt-get update
    
    DBPASS=1234
    DBNAME=slides


    sudo debconf-set-selections <<< "mysql-server mysql-server/root_password password $DBPASS"
    sudo debconf-set-selections <<< "mysql-server mysql-server/root_password_again password $DBPASS"

    apt-get install -y mysql-server    
    mysql -u root -p$DBPASS -e "GRANT ALL PRIVILEGES ON *.* TO 'root'@'%' IDENTIFIED BY '1234' WITH GRANT OPTION;"
    mysql -u root -p$DBPASS -e "CREATE DATABASE $DBNAME;"
    mysql -u root -p$DBPASS -e "USE $DBNAME; FLUSH PRIVILEGES;"
    
    mysql -u root -p$DBPASS -e "USE $DBNAME; CREATE TABLE Presentacions (ID_Presentacio INT AUTO_INCREMENT PRIMARY KEY, titol VARCHAR(30) NOT NULL, descripcio TEXT, estil VARCHAR(30),pin VARCHAR(100), publicada TINYINT(1), url_unica VARCHAR(255) DEFAULT NULL);"
    mysql -u root -p$DBPASS -e "USE $DBNAME; CREATE TABLE pregunta(ID_pregunta INT (11) AUTO_INCREMENT PRIMARY KEY, pregunta TEXT); "
    mysql -u root -p$DBPASS -e "USE $DBNAME; CREATE TABLE Diapositives (ID_Diapositiva INT(11) AUTO_INCREMENT PRIMARY KEY, titol VARCHAR(25) NOT NULL, contingut TEXT(640),imatge TEXT, orden INT(11) NOT NULL, ID_pregunta INT (11), ID_Presentacio INT(11), FOREIGN KEY (ID_Presentacio) REFERENCES Presentacions(ID_Presentacio), FOREIGN KEY(ID_pregunta) REFERENCES pregunta (ID_pregunta));"
    mysql -u root -p$DBPASS -e "USE $DBNAME; CREATE TABLE respuesta (ID_respuesta INT (11) AUTO_INCREMENT PRIMARY KEY, texto TEXT, correcta INT (11), ID_pregunta INT (11),  FOREIGN KEY (ID_pregunta) REFERENCES pregunta(ID_pregunta)); "

    sed -i 's/127.0.0.1/0.0.0.0/g'  /etc/mysql/my.cnf
    service mysql restart
