import requests
import csv
import requests
import os
import time
from geopy.geocoders import Nominatim

API_KEY = "92ecbd09b28050e849ee353088902383"
BASE_URL = "https://api.openweathermap.org/data/2.5/weather?"
BASE_URL_RADIACION = "http://api.openweathermap.org/data/2.5/solar_radiation?lat={lat}&lon={lon}&appid={API key}"

while True:
    CIUDAD = "Maldonado" # Ciudad de Ejemplo
    a = '\u00b0'
    URL = BASE_URL + "q=" + CIUDAD + "&appid=" + API_KEY
    response = requests.get(URL)
    if response.status_code == 200:
        data = response.json()
        main = data['main']
        wind = data['wind']
        # obteniendo temperatura
        temperatura = main['temp']
        # obteniendo humedad
        humedad = main['humidity']
        # obteniendo presion atmosferica
        presion = main['pressure']
        # obteniendo viento
        viento = wind['speed']
        report = data['weather']
        temperatura = int(temperatura - 273.15)
        viento = int(viento * 3.6)
    if os.path.isfile('log_tiempo.csv'):
        with open('log_tiempo.csv', 'a', newline='') as infoCsv:
            writer = csv.writer(infoCsv)
            writer.writerow([CIUDAD, temperatura, humedad, presion, viento])
    else:
        with open('log_tiempo.csv', 'a', newline='') as infoCsv:
            writer = csv.writer(infoCsv)
            writer.writerow(["Ciudad", "Temperatura (\xb0C)", "Humedad (%)", "Presion Atmosferica (HPa)", "Viento (km/h)"])
    time.sleep(10)
