import requests
import time

def Promedio(velocidades_acumuladas):
    return sum(velocidades_acumuladas) / len(velocidades_acumuladas)

API_KEY = "92ecbd09b28050e849ee353088902383"
BASE_URL = "https://api.openweathermap.org/data/2.5/weather?"

URL = BASE_URL + "q=" + "Maldonado" + "&appid=" + API_KEY

promedio_flag = 0
velocidades_acumuladas = []

while True:
    response = requests.get(URL)

    if response.status_code == 200:
        data = response.json()
        wind = data['wind']
        viento = wind['speed']
        print(f'Velocidad del Viento: {viento * 3.6} km/h.')
        velocidades_acumuladas.append(viento * 3.6)
    time.sleep(10)
    promedio_flag += 10
    if(promedio_flag % 300 == 0):
        velocidad_promedio = Promedio(velocidades_acumuladas)
        print(f'Velocidad Promedio del Viento : {velocidad_promedio} km/h.')
        velocidades_acumuladas = []
