from datetime import datetime
import random
import string
import time
import db_actions as db

'''

SELECT * FROM `movimentoscameras` WHERE `data`="20250323"


SELECT * FROM `movimentoscameras` WHERE `nsr`=-1

'''



def generate_plate():
    # Generate the entire plate in one call
    return ''.join(random.choices(string.ascii_uppercase, k=3) + 
                   random.choices(string.digits, k=1) + 
                   random.choices(string.digits, k=2) + 
                   random.choices(string.ascii_uppercase, k=2))



def random_time():
    # Generate a random time between 10 seconds (10) and 5 minutes (300 seconds)
    return random.randint(10, 1 * 60)



def autoplate():
    insert_movimentoscameras(
        nsr=-1, 
        status=state, 
        data='{}{}{}'.format(current_date.year, current_date.month, current_date.day),
        hora= current_time,
        nuvem=state, 
        codigosensor=1, 
        portatirasensor=1, 
        placa=db.generate_plate()
    )






def capturas():
    print("--Simulador de Placas--\n")
    current_time = datetime.now().strftime("%H%M%S")
    current_date = datetime.now().date()
    state="N"
    autoplate()
    while True:
        try:
            plate = db.generate_plate()
            time_to_wait = random_time()    
            print(f"Placa: {plate} - Tempo: {time_to_wait} segundos |  {time_to_wait / 60 } minutos")
            time.sleep(time_to_wait)
        except:
            pass



if __name__ ==  "__main__":
    print("Iniciando simulador de placas")
    placas = db.contar_placa("EAA2F56","20250327")
    for placa in placas:
        print(placa[9])









   ## print(db.contar_placa("EAA2F56","20250327"))
 
   
