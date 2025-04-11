import requests
import time
import jwt
import os
from EnvironmentVariables import EnvironmentVariables as Env

# URL da API de autenticação
url_authenticate = "/users/authenticate"
# URL de consulta das entradas e saídas
url_check_entrances = "/car/queries/check-entrances-and-exits"

env_vars_from_file = Env("{}/core/.env".format(os.getcwd()))
MODULE_PARKING_BOOT = env_vars_from_file.get_value("MODULE_PARKING_BOOT").split("|")

url_api=MODULE_PARKING_BOOT[3]

def Api(resource_api):
    return "{0}{1}".format(url_api,resource_api)



# Dados de login
login_data = {
    "login":MODULE_PARKING_BOOT[1],
    "password": MODULE_PARKING_BOOT[2]
}

# Enviar a requisição de autenticação
response = requests.post(Api(url_authenticate), json=login_data)

# Verificar se a autenticação foi bem-sucedida
if response.status_code == 200:
    response_data = response.json()
    
    # Verificar se a resposta contém o token
    if 'data' in response_data and 'token' in response_data['data']:
        token = response_data['data']['token']
        print("Token de autenticação obtido com sucesso! = "+token)
        
        # Requisição para o endpoint /car/queries/check-entrances-and-exits a cada 30 segundos
        headers = {
            "Authorization": f"{token}",
            "Content-Type": "application/json"
        }
        decoded_payload = jwt.decode(token, options={"verify_signature": False})
        while True:
            try:

                input = {
                  	"module":MODULE_PARKING_BOOT[0],
                    "customer":decoded_payload['customer'],
                    "branch":decoded_payload['branch']
                }
                # Fazer a requisição a cada 30 segundos
                check_response = requests.post(Api(url_check_entrances), json=input,headers=headers)
                # Verificar o status da resposta
                if check_response.status_code == 200:
                    print("Consulta realizada com sucesso!")
                    # Você pode fazer algo com a resposta aqui, como exibir o conteúdo
                    print(check_response.json())  # Exibe a resposta JSON
                else:
                    print(f"Erro na requisição para 'check-entrances-and-exits': {check_response.status_code}")
            
            except Exception as e:
                print(f"Erro ao realizar requisição: {e}")
            
            # Esperar 30 segundos antes de enviar a próxima requisição
            time.sleep(30)
    else:
        print("Token não encontrado na resposta de autenticação.")
else:
    print(f"Falha na autenticação: {response.status_code}")
