import requests
import pymysql

CIUDAD = "Maldonado"

API_KEY = "92ecbd09b28050e849ee353088902383"
BASE_URL = "https://api.openweathermap.org/data/2.5/weather?"
URL = BASE_URL + "q=" + CIUDAD + "&appid=" + API_KEY
response = requests.get(URL)
if response.status_code == 200:
        data = response.json()
        global uv_indice
        main = data['main']
        wind = data['wind']
        global temperatura
        temperatura = main['temp']
        global humedad
        humedad = main['humidity']
        global presion
        presion = main['pressure']
        global viento
        viento = wind['speed']

print(humedad)