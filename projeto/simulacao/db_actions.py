# db_actions.py
from datetime import datetime 
import mysql.connector
from db_connection import connect_to_db

def insert_data(name, age):
    """Função para inserir dados na tabela 'users'"""
    connection = connect_to_db()
    if connection:
        cursor = connection.cursor()
        query = "INSERT INTO users (name, age) VALUES (%s, %s)"
        values = (name, age)
        try:
            cursor.execute(query, values)
            connection.commit()  # Confirma as alterações
            print(f"Dados inseridos com sucesso! {cursor.rowcount} linha(s) afetada(s).")
        except mysql.connector.Error as err:
            print(f"Erro: {err}")
        finally:
            cursor.close()
            connection.close()

def select_data():
    """Função para selecionar dados da tabela 'users'"""
    connection = connect_to_db()
    if connection:
        cursor = connection.cursor()
        query = "SELECT codigo, placa,data  FROM movimentoscameras"
        try:
            cursor.execute(query)
            results = cursor.fetchall()
            print("Dados da tabela 'users':")
            for row in results:
                print(f"ID: {row[0]}, Nome: {row[1]}, Idade: {row[2]}")
        except mysql.connector.Error as err:
            print(f"Erro: {err}")
        finally:
            cursor.close()
            connection.close()



def insert_movimentoscameras(nsr, status, data, hora, nuvem, codigosensor, portatirasensor, placa):
    connection = connect_to_db()
    
    if connection:
        cursor = connection.cursor()
        # Prepare SQL Insert query
        query = """INSERT INTO movimentoscameras (nsr, status, data, hora, nuvem, codigosensor, portatirasensor, placa, created_at, update_at) 
                   VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s)"""
        
        # Use the current timestamp for created_at and update_at
        current_time = datetime.now().strftime('%Y-%m-%d %H:%M:%S')
        
        values = (nsr, status, data, hora, nuvem, codigosensor, portatirasensor, placa, current_time, current_time)

        try:
            cursor.execute(query, values)
            connection.commit()  # Commit the transaction
            print(f"Inserted {cursor.rowcount} row(s) successfully.")
        except mysql.connector.Error as err:
            print(f"Error: {err}")
        finally:
            cursor.close()
            connection.close()





def contar_placa(placa, data):
    connection = connect_to_db()
    if connection:
        cursor = connection.cursor()
        query = "SELECT * FROM movimentoscameras WHERE placa = %s AND `data` = %s"
        values = (placa, data)

        try:
            cursor.execute(query, values)
            result = cursor.fetchall()  # Obtém todos os resultados
            return result  # Retorna a lista de tuplas
        except mysql.connector.Error as err:
            print(f"Erro: {err}")
        finally:
            cursor.close()
            connection.close()
    
    return []  # Retorna uma lista vazia em caso de erro ou conexão falha