from tkinter.constants import S
import requests
payload = {
            'id':5,
            'nombre':"Maldonado Tiempo 1",
            'localidad':"localidad",
            'accion':'nuevaEstacion'}
r = requests.post('http://localhost/ws/',data=payload)

if(r.status_code == 409):
    print("Estacion ya existe")