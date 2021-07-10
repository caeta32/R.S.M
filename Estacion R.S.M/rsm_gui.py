import PySimpleGUI as sg
from PySimpleGUI.PySimpleGUI import theme
from datetime import datetime
import random
import os
import time
import csv
from multiprocessing import Process
import pymysql
import requests
from geopy.geocoders import Nominatim



class DataBase:
    def __init__(self):
        self.connection = pymysql.connect(
            host="localhost",
            user="root",
            password="agusabe12",
            db="informacionCentralizada"
        )
        
        self.cursor = self.connection.cursor()
        print("Conexion establecida")

    def select_all(self, nombre):
        sql = f"SELECT id FROM estaciones where nombre = '{nombre}'"

        try:
            self.cursor.execute(sql)
            todos = self.cursor.fetchall()
            for dato in todos:
                print (dato[0])
                return dato[0]
        except Exception as e:
            raise

    def select_id_from_estacion(self, nombre):
        sql = f"SELECT id FROM estaciones where nombre = '{nombre}'"

        try:
            self.cursor.execute(sql)
            todos = self.cursor.fetchall()
            for dato in todos:
                return dato[0]
        except Exception as e:
            raise

    def insert_log(self, localidad, temperatura, humedad, presion, viento):
        sql = "INSERT INTO `datos` (`Localidad`, `Temperatura`, `Humedad`, `Presion`, `Viento`) VALUES (%s, %s, %s, %s, %s)"
        try:
            self.cursor.execute(sql, (localidad, temperatura, humedad, presion, viento))
            self.connection.commit()
            print("se insertaron los datos")
        except Exception as e:
            raise



API_KEY = "92ecbd09b28050e849ee353088902383"
BASE_URL = "https://api.openweathermap.org/data/2.5/weather?"



font = ("Arial, 25")
temas = ['Black', 'BlueMono', 'BluePurple', 'BrightColors', 'BrownBlue', 'Dark', 'Dark2', 'DarkAmber', 'DarkBlack', 'DarkBlack1', 'DarkBlue', 'DarkBlue1', 'DarkBlue10', 'DarkBlue11', 'DarkBlue12', 'DarkBlue13', 'DarkBlue14', 'DarkBlue15', 'DarkBlue16', 'DarkBlue17', 'DarkBlue2', 'DarkBlue3', 'DarkBlue4', 'DarkBlue5', 'DarkBlue6', 'DarkBlue7', 'DarkBlue8', 'DarkBlue9', 'DarkBrown', 'DarkBrown1', 'DarkBrown2', 'DarkBrown3', 'DarkBrown4', 'DarkBrown5', 'DarkBrown6', 'DarkGreen', 'DarkGreen1', 'DarkGreen2', 'DarkGreen3', 'DarkGreen4', 'DarkGreen5', 'DarkGreen6', 'DarkGrey', 'DarkGrey1', 'DarkGrey2', 'DarkGrey3', 'DarkGrey4', 'DarkGrey5', 'DarkGrey6', 'DarkGrey7', 'DarkPurple', 'DarkPurple1', 'DarkPurple2', 'DarkPurple3', 'DarkPurple4', 'DarkPurple5', 'DarkPurple6', 'DarkRed', 'DarkRed1', 'DarkRed2', 'DarkTanBlue', 'DarkTeal', 'DarkTeal1', 'DarkTeal10', 'DarkTeal11', 'DarkTeal12', 'DarkTeal2', 'DarkTeal3', 'DarkTeal4', 'DarkTeal5', 'DarkTeal6', 'DarkTeal7', 'DarkTeal8', 'DarkTeal9', 'Default', 'Default1', 'DefaultNoMoreNagging', 'Green', 'GreenMono', 'GreenTan', 'HotDogStand', 'Kayak', 'LightBlue', 'LightBlue1', 'LightBlue2', 'LightBlue3', 'LightBlue4', 'LightBlue5', 'LightBlue6', 'LightBlue7', 'LightBrown', 'LightBrown1', 'LightBrown10', 'LightBrown11', 'LightBrown12', 'LightBrown13', 'LightBrown2', 'LightBrown3', 'LightBrown4', 'LightBrown5', 'LightBrown6', 'LightBrown7', 'LightBrown8', 'LightBrown9', 'LightGray1', 'LightGreen', 'LightGreen1', 'LightGreen10', 'LightGreen2', 'LightGreen3', 'LightGreen4', 'LightGreen5', 'LightGreen6', 'LightGreen7', 'LightGreen8', 'LightGreen9', 'LightGrey', 'LightGrey1', 'LightGrey2', 'LightGrey3', 'LightGrey4', 'LightGrey5', 'LightGrey6', 'LightPurple', 'LightTeal', 'LightYellow', 'Material1', 'Material2', 'NeutralBlue', 'Purple', 'Reddit', 'Reds', 'SandyBeach', 'SystemDefault', 'SystemDefault1', 'SystemDefaultForReal', 'Tan', 'TanBlue', 'TealMono', 'Topanga']
sg.theme(random.choice(temas))

def make_ventana_config():
    layout = [[sg.Text("Configura tu Estacion", font=font)], [sg.Text("Nombre de la Estacion")], [sg.Input(key='nombre', size=(47, 1))], [sg.Text("Localidad de la Estacion")], [sg.Combo(['Artigas', 'Canelones', 'Cerro Largo', 'Colonia', 'Durazno', 'Flores', 'Florida', 'Lavalleja', 'Maldonado', 'Montevideo', 'Paysandu', 'Rio Negro', 'Rivera', 'Rocha', 'Salto', 'San Jose', 'Soriano', 'Tacuarembo', 'Treinta y Tres'],key='localidad', size=(45, 1))], [sg.Button("Poner en Marcha")],]
    return sg.Window("Estacion Meteorologica RMS", layout, element_justification='c', margins=(150, 50))

def make_ventana_config_error():
    layout = [[sg.Text("Una estacion con ese nombre ya existe", font=font)], [sg.Text("Configura tu Estacion nuevamente", font=font)], [sg.Text("Nombre de la Estacion")], [sg.Input(key='nombre', size=(47, 1))], [sg.Text("Localidad de la Estacion")], [sg.Combo(['Artigas', 'Canelones', 'Cerro Largo', 'Colonia', 'Durazno', 'Flores', 'Florida', 'Lavalleja', 'Maldonado', 'Montevideo', 'Paysandu', 'Rio Negro', 'Rivera', 'Rocha', 'Salto', 'San Jose', 'Soriano', 'Tacuarembo', 'Treinta y Tres'],key='localidad', size=(45, 1))], [sg.Button("Poner en Marcha")],]
    return sg.Window("Estacion Meteorologica RMS", layout, element_justification='c', margins=(150, 50))

def make_ventana_menu(nombre, localidad):
    global CIUDAD
    CIUDAD = localidad
    geolocator = Nominatim(user_agent="rsm")
    location = geolocator.geocode(CIUDAD+','+ 'Uruguay')
    lat = location.latitude
    lon = location.longitude
    URL = BASE_URL + "q=" + CIUDAD + "&appid=" + API_KEY
    URL_RAD_SOLAR = f'https://api.openweathermap.org/data/2.5/onecall?lat={lat}&lon={lon}&exclude=minutely,hourly,daily,alerts&appid={API_KEY}'
    response = requests.get(URL)
    response_rad = requests.get(URL_RAD_SOLAR)
    if response.status_code == 200:
        data = response.json()
        radiacion = response_rad.json()
        list_rad = radiacion['current']
        global uv_indice
        uv_indice = list_rad['uvi']
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
        global cardinal
        direccionViento = wind['deg']
        if 181 <= direccionViento <= 269:
            cardinal = "NE"

        elif 271 <= direccionViento <= 359:
            cardinal = "SE"

        elif 1 <= direccionViento <= 89:
            cardinal = "SO"

        elif 91 <= direccionViento <= 179:
            cardinal = "NO"

        elif direccionViento == 0 or direccionViento == 360:
            cardinal = "S"

        elif direccionViento == 90:
            cardinal = "O"

        elif direccionViento == 180:
            cardinal = "N"

        elif direccionViento == 270:
            cardinal = "E"

    layout = [[sg.Text('Datos de la Estacion', font=font)], [sg.Text(f'Nombre de la Estacion: {nombre}')], [sg.Text(f'Localidad: {localidad}')],
    [sg.Text(f"Temperatura: {int(temperatura - 273.15)} \xb0C")],
    [sg.Text(f"Humedad: {humedad}%")],
    [sg.Text(f"Presion Atmosferica: {presion} HPa")],
    [sg.Text(f"Viento Promedio: {int(viento * 3.6)} km/h")],
    [sg.Text(f"Direccion del Viento: {cardinal}")],
    [sg.Text(f"Indide UV: {uv_indice}")],
    [sg.Button("Actualizar")]
    ]
    return sg.Window("Estacion Meteorologica RMS", layout, element_justification='c', margins=(150, 50))

def guardar_en_log(nombre, ciudad):
    db = DataBase()
    idEstacion = db.select_id_from_estacion(nombre)
    while True:
            geolocator = Nominatim(user_agent="rsm")
            location = geolocator.geocode(ciudad+','+ 'Uruguay')
            lat = location.latitude
            lon = location.longitude
            URL = BASE_URL + "q=" + ciudad + "&appid=" + API_KEY
            URL_RAD_SOLAR = f'https://api.openweathermap.org/data/2.5/onecall?lat={lat}&lon={lon}&exclude=minutely,hourly,daily,alerts&appid={API_KEY}'
            response = requests.get(URL)
            response_rad = requests.get(URL_RAD_SOLAR)
            if response.status_code == 200:
                data = response.json()
                radiacion = response_rad.json()
                list_rad = radiacion['current']
                global uv_indice
                uv_indice = list_rad['uvi']
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
                global cardinal
                direccionViento = wind['deg']
                if 181 <= direccionViento <= 269:
                    cardinal = "NE"

                elif 271 <= direccionViento <= 359:
                    cardinal = "SE"

                elif 1 <= direccionViento <= 89:
                    cardinal = "SO"

                elif 91 <= direccionViento <= 179:
                    cardinal = "NO"

                elif direccionViento == 0 or direccionViento == 360:
                    cardinal = "S"

                elif direccionViento == 90:
                    cardinal = "O"

                elif direccionViento == 180:
                    cardinal = "N"

                elif direccionViento == 270:
                    cardinal = "E"
                payload = { 'temperatura':int(temperatura - 273.15),
                    'humedad':humedad,
                    'presion':presion,
                    'velocidadViento':int(viento * 3.6),
                    'direccionViento':cardinal,
                    'radiacionSolar':10,
                    'radiacionIndiceUV':uv_indice,
                    'indicePluviometrico':0,
                    'idEstacion':idEstacion,
                    'accion':'nuevaInfoEstacion'}
                session = requests.Session()
                session.post('http://localhost/server-central_JEE/ws/',data=payload)
                time.sleep(600)
        


if __name__ == '__main__':
    window1 = make_ventana_config()
    # Create an event loop
    database = DataBase()
    existe = 0
    while True:
        if existe == 0:   
            event, values = window1.read()
            # End program if user closes window or
            # presses the OK button
            if event == "Poner en Marcha":
                localidad = values['localidad']
                nombre = values['nombre']
                payload = {
                            'nombre':nombre,
                            'localidad':localidad,
                            'accion':'nuevaEstacion'}
                r = requests.post('http://localhost/server-central_JEE/ws/',data=payload)
                if(r.status_code != 409 and nombre is not None and localidad is not None):
                    window1.close()
                    window2 = make_ventana_menu(nombre, localidad)
                    p1 = Process(target=guardar_en_log, args=(nombre, localidad))
                    p1.start()
                    event, values = window2.read()
                    while True:
                        if event == "Actualizar":
                            window2.close()
                            window2 = make_ventana_menu(nombre, localidad)
                            event, values = window2.read()
                        elif event == sg.WIN_CLOSED:
                            p1.kill()
                            break
                else:
                    existe = 1
            elif event == sg.WIN_CLOSED:
                exit()
        elif existe == 1:
            window1.close()
            window1 = make_ventana_config_error()
            event, values = window1.read()
            # End program if user closes window or
            # presses the OK button
            if event == "Poner en Marcha":
                localidad = values['localidad']
                nombre = values['nombre']
                localidad = values['localidad']
                nombre = values['nombre']
                payload = {
                            'nombre':nombre,
                            'localidad':localidad,
                            'accion':'nuevaEstacion'}
                r = requests.post('http://localhost/server-central_JEE/ws/',data=payload)

                if(r.status_code != 409 and nombre is not None and localidad is not None):
                    window1.close()
                    window2 = make_ventana_menu(nombre, localidad)
                    p1 = Process(target=guardar_en_log, args=(nombre, localidad))
                    p1.start()
                    event, values = window2.read()
                    while True: 
                        if event == "Actualizar":
                            window2.close()
                            window2 = make_ventana_menu(nombre, localidad)
                            event, values = window2.read()
                        elif event == sg.WIN_CLOSED:
                            p1.kill()
                            exit()
                else:
                    existe = 1
            elif event == sg.WIN_CLOSED:
                exit()




