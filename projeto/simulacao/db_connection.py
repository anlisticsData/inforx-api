'''

{
"host":"162.214.76.190",
"user":"inforparkuser",
"pwd":"ASD7N#!a)k6a",
"port":3306,
"base":"InforPark_0011_0011",
"overnightstay":"20:00|06:00",
"tolerance":"00:05:00",
"cams":[1,2]
}



'''

# db_connection.py
import mysql.connector
from mysql.connector import Error

def connect_to_db():
    """Estabelece uma conexão com o banco de dados MySQL"""
    try:
        connection = mysql.connector.connect(
            host="162.214.76.190",       # Seu host do MySQL (ex: localhost ou IP)
            user="inforparkuser",            # Seu usuário do MySQL
            password="ASD7N#!a)k6a",    # Sua senha do MySQL
            database="InforPark_0011_0011" ,     # Seu nome de banco de dados,
            port=3306 # Porta padrão
        )
        
        if connection.is_connected():
            
            return connection
    except Error as e:
        print(f"Erro ao conectar ao MySQL: {e}")
        return None
